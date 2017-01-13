<?php
//check for session
require '../../Library/SessionManager.php';
$session = new SessionManager();
//prevent accessing directly
if(!isset($_POST))
    header('Location: index.php');

require '../../Library/DBHelper.php';
require '../../Library/FolderDBHelper.php';
$DB = new FolderDBHelper();
$data = $_POST;
$action = htmlspecialchars($data['txtAction']);
$collection = htmlspecialchars($data['txtCollection']);
$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
$comments = null;
if($action != "delete") {
    //data pre-processing
    //Date
    require '../../Library/DateHelper.php';
    $date = new DateHelper();
    $startdate = $date->mergeDate($data['ddlStartMonth'], $data['ddlStartDay'], $data['ddlStartYear']);
    $enddate = $date->mergeDate($data['ddlEndMonth'], $data['ddlEndDay'], $data['ddlEndYear']);

}
$valid = false;
$msg = array();
$retval = false;
//review
if($action == "review" || $action == "catalog")
{
    $author_arrays = json_decode($_POST['authors']);
    $retval = $DB->SP_TEMPLATE_FOLDER_DOCUMENTAUTHOR_INSERT($collection,$data['txtDocID'],$author_arrays);

    if($retval != false) {
        if($action == "catalog")
            $retval = $DB->SP_TEMPLATE_FOLDER_DOCUMENT_UPDATE($collection, $data['txtDocID'], $data['txtLibraryIndex'], $data['txtTitle'], $data['rbInASubfolder'], $data['txtSubfolderComments'], $data['ddlClassification'], $data['txtClassificationComments'],0,$data['rbNeedsReview'],$data['txtComments'],$startdate, $enddate);
        $comments = "Library Index:" . $data['txtLibraryIndex'];
        $valid = true;
    }
    // array_push($msg,"Update Query: GOOD");
}
else if($action == "delete")
{
    $errors = 0;
    $info = $DB->SP_TEMPLATE_FOLDER_DOCUMENT_SELECT($data["txtCollection"], $data['txtDocID']);
    $comments = "Library Index: " . $info['LibraryIndex'];

    $frontScanPath = $config['StorageDir'].$info['FileNamePath'];
    $backScanPath = $config['StorageDir'].$info['FileNameBackPath'];

    $directory = $_SERVER['DOCUMENT_ROOT']."/BandoCat";

    $frontThumbnailPath = "../../" . $config['ThumbnailDir'] . str_replace(".tif",".jpg",$info['FileName']);
    $backThumbnailPath = "../../" . $config['ThumbnailDir']. str_replace(".tif",".jpg",$info['FileNameBack']);

    $retval = $DB->DELETE_DOCUMENT($collection,$data['txtDocID']);
    if($retval)
        $retval = $DB->TEMPLATE_FOLDER_DELETE_DOCUMENTAUTHOR($collection,$data['txtDocID']);
    if($retval) {
        if (file_exists($frontScanPath))
            unlink($frontScanPath);
        if (file_exists($frontThumbnailPath)) {
            unlink($frontThumbnailPath);
        }
        if (file_exists($backScanPath))
            unlink($backScanPath);
        if (file_exists($backThumbnailPath)) {
            unlink($backThumbnailPath);
        }
    }
}

//REPORT STATUS
if ($retval == false) {
    $logstatus = "fail";
    array_push($msg, "Failed to Submit!");
} else {
    $logstatus = "success";
    array_push($msg, "Success!");
}

//write log
$retval = $DB->SP_LOG_WRITE($action,$config['CollectionID'],$data['txtDocID'],$session->getUserID(),$logstatus,$comments);
if(!$retval)
    array_push($msg, "ERROR: Fail to write log!");

if($retval == false || $valid == false)
{
    require '../../Library/ErrorLogger.php';
    $LOG = new ErrorLogger();
    if ($action == "catalog" || $action == "review")
        $LOG->writeErrorLog($session->getUserName(),$collection,$data['txtDocID'],$msg,$comments);
    else if ($action == "delete")
        $LOG->writeErrorLog($session->getUserName(),$collection,$data['txtDocID'],$msg,$comments);
}
echo json_encode($msg);
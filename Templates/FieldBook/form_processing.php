<?php
//check for session
require '../../Library/SessionManager.php';
$session = new SessionManager();
//prevent accessing directly
if(!isset($_POST))
    header('Location: index.php');

require '../../Library/DBHelper.php';
require '../../Library/FieldBookDBHelper.php';
$DB = new FieldBookDBHelper();
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

    $DB->SP_TEMPLATE_FIELDBOOK_AUTHOR_GET_ID_FROM_NAME_WITH_INSERT($collection, $data['txtAuthor'], $authorID);
    $DB->SP_TEMPLATE_FIELDBOOK_COLLECTION_GET_ID_FROM_NAME_WITH_INSERT($collection, $data['txtCollection'], $collectionID);
    //CREWS
}
$valid = false;
$msg = array();
$retval = false;
//review
if($action == "review")
{
    $valid = true;
    $retval = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_UPDATE($collection,$data['txtDocID'],$data['txtLibraryIndex'],$data['txtTitle'],$data['txtSubtitle'],
        $data['rbIsMap'],$data['txtMapScale'],$data['rbHasNorthArrow'],$data['rbHasStreets'],
        $data['rbHasPOI'],$data['rbHasCoordinates'],$data['rbHasCoast'],$data['rbNeedsReview'],
        $data['txtComments'],$customerID,$startdate,$enddate,$data['txtFieldBookNumber'],$data['txtFieldBookPage'],$data['ddlReadability'],
        $data['ddlRectifiability'],$companyID,$data['txtType'],$mediumID,$authorID);
    $comments = "Library Index:" . $data['txtLibraryIndex'];
    // array_push($msg,"Update Query: GOOD");
}
//catalog (new document)
else if($action == "catalog") {
    $retval = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_UPDATE($collection,$data['txtDocID'],$data['txtLibraryIndex'],$data['txtTitle'],$data['txtSubtitle'],
        $data['rbIsMap'],$data['txtMapScale'],$data['rbHasNorthArrow'],$data['rbHasStreets'],
        $data['rbHasPOI'],$data['rbHasCoordinates'],$data['rbHasCoast'],$data['rbNeedsReview'],
        $data['txtComments'],$customerID,$startdate,$enddate,$data['txtFieldBookNumber'],$data['txtFieldBookPage'],$data['ddlReadability'],
        $data['ddlRectifiability'],$companyID,$data['txtType'],$mediumID,$authorID);
    $comments = "Library Index:" . $data['txtLibraryIndex'];
    // array_push($msg,"Update Query: GOOD");

}
else if($action == "delete")
{
    $errors = 0;
    $info = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT($data["txtCollection"], $data['txtDocID']);
    $comments = "Library Index: " . $info['LibraryIndex'];

    $frontScanPath = $config['StorageDir'].$info['FileNamePath'];

    //Thumbnail conversion to jpg and path detection
    //$thumbnailPath = str_replace('/','\\',$config['ThumbnailDir']);
    $directory = $_SERVER['DOCUMENT_ROOT']."/BandoCat";

    $frontThumbnailPathTIF = $config['ThumbnailDir'].$info['FileName'];

    $frontThumbnailPathJPG = "../../".str_replace(".tif", ".jpg", $frontThumbnailPathTIF);

    $retval = $DB->DELETE_DOCUMENT($collection,$data['txtDocID']);

    if (file_exists($frontScanPath))
        unlink($frontScanPath);
    if (file_exists($frontThumbnailPathJPG))
        unlink($frontThumbnailPathJPG);

//        if (file_exists($frontScanPath) || file_exists($frontThumbnailPathJPG) || file_exists($backScanPath) || file_exists($backThumbnailPathJPG))
//            $errors++;
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
    if($action == "review")
        $LOG->writeErrorLog($session->getUserName(),$collection,$data['txtDocID'],$msg,$comments);
    else if ($action == "catalog")
        $LOG->writeErrorLog($session->getUserName(),$collection,basename($_FILES['fileUpload']['name']),$msg,$comments);
    else if ($action == "delete")
        $LOG->writeErrorLog($session->getUserName(),$collection,$data['txtDocID'],$msg,$comments);
}
echo json_encode($msg);
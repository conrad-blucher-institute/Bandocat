<?php
//check for session
require '../../Library/SessionManager.php';
$session = new SessionManager();
//prevent accessing directly
if(!isset($_POST))
    header('Location: index_old.php');

require '../../Library/DBHelper.php';
require '../../Library/FieldBookDBHelper.php';
$DB = new FieldBookDBHelper();
//store passed info into data variable (txtAction: "delete", "txtCollection": collection, "txtDocID": documentID)
$data = $_POST;
//check for special characters in passed variables
$action = htmlspecialchars($data['txtAction']);
$collection = htmlspecialchars($data['txtCollection']);
//get appropriate db
$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
$comments = null;
//if the action is not delete
if($action != "delete")
{
    //data pre-processing
    //Date
    require '../../Library/DateHelper.php';
    //get the current date
    $date = new DateHelper();
    $startdate = $date->mergeDate($data['ddlStartMonth'], $data['ddlStartDay'], $data['ddlStartYear']);
    $enddate = $date->mergeDate($data['ddlEndMonth'], $data['ddlEndDay'], $data['ddlEndYear']);

}
$valid = false;
$msg = array();
$retval = false;
//if the action is review or catalog
if($action == "review" || $action == "catalog")
{
    //grab crew that was in the _POST
    $crew_arrays = json_decode($_POST['crews']);
    //attempt to insert crew into db
    $retval = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENTCREW_INSERT($collection,$data['txtDocID'],$crew_arrays);

    if($retval != false)
    {
        //insert retval is true, update the fieldbook document
        $retval = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_UPDATE($collection, $data['txtDocID'], $data['txtLibraryIndex'], $data['txtFBCollection'], $data['txtBookTitle'], $data['txtJobNumber'], $data['txtJobTitle'], $data['txtBookAuthor'], $startdate, $enddate, $data['txtComments'], $data['txtIndexedPage'], $data['rbBlankPage'], $data['rbSketch'], $data['rbLooseDocument'], 0, $data['rbNeedsReview']);
        $comments = "Library Index:" . $data['txtLibraryIndex'];
        $valid = true;
    }
    // array_push($msg,"Update Query: GOOD");
}
//if the action is delete
else if($action == "delete")
{
    $errors = 0;
    //select the fieldbook document template from the supplied collection, and docid
    $info = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT($data["txtCollection"], $data['txtDocID']);
    //store the library index into comments
    $comments = "Library Index: " . $info['LibraryIndex'];
    //store the filenamepath into frontscanpath
    $frontScanPath = $config['StorageDir'].$info['FileNamePath'];
    //store the directory
    $directory = $_SERVER['DOCUMENT_ROOT']."../../";
    //store the thumbnailpath
    $frontThumbnailPath = "../../" . $config['ThumbnailDir'].$info['Thumbnail'];
    //call the delete document function passing in the collection, and the documentId
    $retval = $DB->DELETE_DOCUMENT($collection,$data['txtDocID']);
    if($retval)
        //If document was deleted, delete the documents crew
        $retval = $DB->TEMPLATE_FIELDBOOK_DELETE_DOCUMENTCREW($collection,$data['txtDocID']);
    //If document was deleted, unlink all filepaths
    if($retval)
    {
        if (file_exists($frontScanPath))
            unlink($frontScanPath);
        if (file_exists($frontThumbnailPath))
        {
            unlink($frontThumbnailPath);
        }
    }
}

//REPORT STATUS
if ($retval == false)
{
    $logstatus = "fail";
    array_push($msg, "Failed to Submit!");
} else
    {
    $logstatus = "success";
    array_push($msg, "Success!");
}

//write log passing what happened to the db
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
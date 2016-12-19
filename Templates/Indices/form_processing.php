<?php
//check for session
require '../../Library/SessionManager.php';
$session = new SessionManager();
//prevent accessing directly
if(!isset($_POST))
    header('Location: index.php');

require '../../Library/DBHelper.php';
require '../../Library/IndicesDBHelper.php';
$DB = new IndicesDBHelper();
$data = $_POST;
$action = htmlspecialchars($data['txtAction']);
$collection = htmlspecialchars($data['txtCollection']);
$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
$comments = null;
$valid = false;
$msg = array();
$retval = false;


if($action == "review")
{
    $valid = true;
    $retval = $DB->SP_TEMPLATE_INDICES_DOCUMENT_UPDATE($collection, $data['txtDocID'], $data['txtLibraryIndex'], $data['ddlBookID'],
        $data['rbPageType'], $data['txtPageNumber'], $data['txtComments'], $data['rbNeedsReview'], $data['txtLibraryIndex']);
    $comments = "Library Index:" . $data['txtLibraryIndex'];
    if($retval)
        array_push($msg,"Update Query: Success");
    else
        //print_r($retval);
        array_push($msg, "Update Query: Fail", $retval);
}


if ($action == "catalog") {
    $valid = true;
//INSERT QUERY
    //Function parameters: Collection, Library Index, Book ID, Page Type, Page Number, Comments, Needs Review, Filename
    $retval = $DB->SP_TEMPLATE_INDICES_DOCUMENT_INSERT($collection, $data['txtLibraryIndex'], $data['ddlBookID'],
        $data['rbPageType'], $data['txtPageNumber'], $data['txtComments'], $data['rbNeedsReview'], $data['txtLibraryIndex']);
    //Stores the document id from the database to the variable for
    $data['txtDocID'] = $retval;
    $comments = "Library Index: " . $data['txtLibraryIndex'];
    if($retval == false){
        $valid = false;
    }


//VALIDATION
    //FILES error check //                      1
    //Existence of index by file name check//   2
    //Indices thumbnail exists check//          3

    //1
    if ($valid == true && $_FILES['file_array']['error'] == 0) {
        $valid = true;
        $filename = $_FILES['file_array']['name'];
        array_push($msg, "Indices File upload check: Success");
    } else {
        $valid = false;
        array_push($msg, "Indices File upload check: FAIL");
    }

    //2
    if ($valid == true && $_FILES['file_array']['error'] == 0) {
        $tempFilename = explode('_', $filename);
        $last = array_pop($tempFilename);
        $parts = array(implode('_', $tempFilename), $last);
        $folder = $parts[0];
        $filenamepath = $config['StorageDir'] . $folder;

        if (file_exists($filenamepath . '/' . $filename)) {
            array_push($msg, "Indices upload: EXISTED");
            $valid = false;
        } else array_push($msg, "Indices upload: Success");
    }
    else
        array_push($msg, "Indices upload: FAIL");

    //3
    if ($valid == true && $_FILES['file_array']['error'] == 0) {
        $thumbnail = $config['ThumbnailDir'] . str_replace('.tif', '.jpg', $filename);
        if (file_exists($thumbnail)) {
            array_push($msg, "Thumbnail: EXISTED");
            $valid = false;
        } else
            array_push($msg, 'Thumbnail upload: Success');
    } else
        array_push($msg, "Thumbnail upload: FAIL");


//Check folder, create folder for indices TIF
    if ($valid == true) {
        if (!is_dir($filenamepath))
            mkdir($filenamepath, 0777);
        move_uploaded_file($_FILES["file_array"]["tmp_name"], $filenamepath . '/' . basename($_FILES["file_array"]["name"]));

        //Script for creation of file and thumbnail
        if (!is_dir('../../' . $config['ThumbnailDir']))
            mkdir('../../' . $config['ThumbnailDir'], 0777);
        $exec1 = "convert " . $filenamepath . '/' . basename($_FILES["file_array"]["name"]) . " -deskew 40% -fuzz 50% -trim -resize 200 " . '../../' . $thumbnail;
        exec($exec1, $yaks1);
    }
}
    //REPORT STATUS
    if ($retval == false) {
        $logstatus = "fail";
        array_push($msg, "Failed to Submit!");
    } else {
        $logstatus = "success";
        array_push($msg, "Report Status: Success!");
    }

    //write log
    /*$retval = $DB->SP_LOG_WRITE($action,$config['CollectionID'],$data['txtDocID'],$session->getUserID(),$logstatus,$comments);
    if(!$retval)
        array_push($msg, "ERROR: Fail to write log!");

    if($retval == false || $valid == false)
    {
        require '../../Library/ErrorLogger.php';
        $LOG = new ErrorLogger();
        if($action == "review")
            $LOG->writeErrorLog($session->getUserName(),$collection,$data['txtDocID'],$msg,$comments);
        else if ($action == "catalog")
            $LOG->writeErrorLog($session->getUserName(),$collection,basename($_FILES['file_array']['name']),$msg,$comments);
        else if ($action == "delete")
            $LOG->writeErrorLog($session->getUserName(),$collection,$data['txtDocID'],$msg,$comments);
    }*/
echo json_encode($msg);

/*$files = $_FILES;
    $filenamepath = "";
    $msg = [];
    $valid = false;
    $thumbnail = "";

/*check if record exists on DB */
/*if ($valid == true) {
    $existed = $DB->SP_TEMPLATE_INDICES_DOCUMENT_CHECK_EXIST_RECORD($collection, $data['txtLibraryIndex']);
    if ($existed != "GOOD") {
        $valid = false;
        array_push($msg, "Database Check: EXISTED");
    }
}
/*
//Upload catalog form
if ($action == "catalog") {
    $valid = false;

//VALIDATION
    //check FILES errors //1
    //check existance of index by file name//2
    //check if indices thumbnail exists//3

    //1
    if ($_FILES['file_array']['error'] == 0) {
        $valid = true;
        $filename = $_FILES['file_array']['name'];
        array_push($msg, "Indices File upload check: Success");
    } else {
        $valid = false;
        array_push($msg, "Indices: FAIL");
    }

    //2
    if ($valid == true && $_FILES['file_array']['error'] == 0) {
        $tempFilename = explode('_', $filename);
        $last = array_pop($tempFilename);
        $parts = array(implode('_', $tempFilename), $last);
        $folder = $parts[0];
        $filenamepath = $config['StorageDir'] . $folder;

        if (file_exists($filenamepath . '/' . $filename)) {
            array_push($msg, "Indices upload: EXISTED");
            $valid = false;
        }
        else array_push($msg, "Indices upload: Success");
    }

    //3
    if($valid == true && $_FILES['file_array']['error'] == 0){
        $thumbnail = $config['ThumbnailDir'].str_replace('.tif', '.jpg', $filename);
        if(file_exists($thumbnail)){
            array_push($msg, "Thumbnail: EXISTED");
            $valid=false;
        }
        else
            array_push($msg, 'Thumbnail upload: Success');
    }
    else
        array_push($msg, "Thumbnail upload: FAIL");
    //INSERT QUERY
    //Function parameters: Collection, Library Index, Book ID, Page Type, Page Number, Comments, Needs Review, Filename
    $retval = $DB->SP_TEMPLATE_INDICES_DOCUMENT_INSERT($collection, $data['txtLibraryIndex'], $data['ddlBookID'],
        $data['rbPageType'], $data['txtPageNumber'], $data['txtComments'], $data['rbNeedsReview'], $data['txtLibraryIndex']);
    //Stores the document id from the database to the variable for
    $data['txtDocID'] = $retval;
    $comments = "Library Index: ".$data['txtLibraryIndex'];
    array_push($msg, $retval);
}




    //Check folder, create folder for indices TIF
    if ($valid == true) {
        if (!is_dir($filenamepath))
            mkdir($filenamepath, 0777);
        move_uploaded_file($_FILES["file_array"]["tmp_name"], $filenamepath . '/' . basename($_FILES["file_array"]["name"]));

        //Script for creation of file and thumbnail
        if (!is_dir('../../' . $config['ThumbnailDir']))
            mkdir('../../' . $config['ThumbnailDir'], 0777);
        $exec1 = "convert " . $filenamepath . '/' . basename($_FILES["file_array"]["name"]) . " -deskew 40% -fuzz 50% -trim -resize 200 " . '../../' . $thumbnail;
        exec($exec1, $yaks1);
    }
echo json_encode($msg);*/

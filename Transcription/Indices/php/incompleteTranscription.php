<?php
    include '../../../Library/SessionManager.php';
    $session = new SessionManager();
    require('../../../Library/DBHelper.php');
    require('../../../Library/IndicesDBHelper.php');
    $DB = new IndicesDBHelper();
	//error_reporting(E_ALL);
	$docID = $_POST['docID'];
    $collection = $_POST['collection'];
    $collectionID = $DB->SP_GET_COLLECTION_CONFIG($collection)['CollectionID'];
    $fileName = $_POST['fileName'];

    $action = "transcribe";
    $comments = "Not Finished";

   // $ret = $DB->SET_DOCUMENT_TRANSCRIBED($collection,$docID,0);

    $tempDir = "../../../Temp/";
    //delete file from Temp
    unlink("../" . $fileName);
    //Write Log
   // $ret = $DB->SP_LOG_WRITE($action,$collectionID,$docID,$session->getUserID(),"success",$comments);


    //clean old temporary images inside the Temp directory
    $fileArray = array_diff(scandir($tempDir), array('..', '.'));
    foreach($fileArray as $file) //deleting files that has the last modification time more than 8 hours
    {
        if (strtotime("now") - filemtime($tempDir . $file) > 28800) //28800 sec = 8 hours
            unlink($tempDir . $file);
    }





?>
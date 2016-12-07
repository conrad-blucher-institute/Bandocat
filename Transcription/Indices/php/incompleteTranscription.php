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


    //delete file from Temp
    unlink("../" . $fileName);
    //Write Log
   // $ret = $DB->SP_LOG_WRITE($action,$collectionID,$docID,$session->getUserID(),"success",$comments);
    echo "";




?>
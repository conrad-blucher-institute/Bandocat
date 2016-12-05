<?php
    include '../../Library/SessionManager.php';
    $session = new SessionManager();
    require('../../../Library/DBHelper.php');
    $DB = new DBHelper();
	//error_reporting(E_ALL);
	$docID = json_decode($_GET['id']);
    $collection = json_decode($_GET['col']);

    $DB->SET_DOCUMENT_TRANSCRIBED($collection,$docID,1);

		if($result != false)
			$msg = 'This document has been marked as complete.';
		else $msg = 'Failed to mark as complete.';
		
		echo json_encode($msg);
	
?>
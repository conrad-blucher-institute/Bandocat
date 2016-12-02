<?php
	error_reporting(E_ALL);
	$docID = json_decode($_GET['id']);

	
	$servername = "localhost";
	$username = "root";
	$password = "notroot";
	$dbname = "indicesinventory";
	
		$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
		$msg = "";
		$sql = "UPDATE documents SET `transcribed` = 1 WHERE id = $docID"; // MAKE THIS QUERIE THE DATABASE BY FILENAME
		$result = $conn->query($sql);
		if($result)
			$msg = 'This document has been marked as complete.';
		else $msg = 'Failed to mark as complete.';
		
		echo json_encode($msg);
	
?>
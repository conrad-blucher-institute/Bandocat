<?php
/* The purpose of this script is to execute the proccesses that are required to delete an entry from 
our database. It recieves the coordinates of the entry to be deleted from an AJAX call and performs 
a query to delete the entry that matches those coordinates and fileName in the database.  */

	$updateObject = json_decode($_POST['deleteObject']);
	
	//assigning all updateObject data to appropriate variable
	$docID = "'" . $updateObject -> docID . "'";
	$x1 = "'" . $updateObject -> x1 . "'";
	$y1 = "'" . $updateObject -> y1 . "'";
	$x2 = "'" . $updateObject -> x2 . "'";
	$y2 = "'" . $updateObject -> y2 . "'";
	
	
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

	$coords = array();
		
	$sql = "DELETE FROM transcription WHERE  document_id = $docID AND x1 = $x1 AND y1 = $y1 AND x2 = $x2 AND y2 = $y2";
			
	$result = $conn->query($sql);
	
	
	echo $docID;
?>
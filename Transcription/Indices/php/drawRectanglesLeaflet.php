<?php
/* 	This function is called when the page "Transcription_Status"(should probably be renamed) is loaded.
	The script querys the database for the coordinates for all the entries that match the fileName. These 
	coordinates are used to draw all the rectangles for entries that have alreaded been submitted. */
	
	error_reporting(E_ALL);
	
	$fileName = $_POST['fileName'];
	$fileName = "'" . $fileName . "'";
	
	$docID = $_POST['docID'];

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
		
	$sql = "SELECT x1, y1, x2, y2 FROM transcription WHERE document_id = $docID"; // MAKE THIS QUERIE THE DATABASE BY FILENAME
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$coords[] = $row;
		}
	} else {
		echo "0 results";
	}
	
	echo json_encode($coords);
?>
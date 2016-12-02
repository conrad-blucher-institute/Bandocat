<?php
/* 	This script is called when an entry rectangle is selected. This script recieves the fileName and
	coordinates of the entry from an AJAX call and executes an SQL statement that returns a JSON 
	containing all the data so that it can be displayed in the html form. */
	error_reporting(E_ALL);
	$entryObject = json_decode($_POST['entryObject']);
	$docID = "'" . $entryObject -> docID . "'";
	$fileName = "'" . $entryObject -> fileName . "'";
	$x1 = "'" . $entryObject -> x1 . "'";
	$y1 = "'" . $entryObject -> y1 . "'";
	$x2 = "'" . $entryObject -> x2 . "'";
	$y2 = "'" . $entryObject -> y2 . "'";

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
		
	$sql = "SELECT document_id, x1, y1, x2, y2, survey_or_section, block_or_tract, lot_or_acres,
			description, client, field_book_info, related_papers_file_no,
			map_info, date, job_number FROM transcription WHERE document_id = $docID AND x1 = $x1 AND y1 = $y1 AND x2 = $x2 AND y2 = $y2" ; // MAKE THIS QUERIE THE DATABASE BY FILENAME
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
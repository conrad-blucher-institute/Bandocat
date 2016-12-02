<?php
	/* The purpose of this script is to execute the proccesses that are required to update an entry in
	our database. It recieves the data, and coordinates of the entry to be updated from an AJAX call and performs 
	a query to update the entry that matches the coordinates with the new data.  */
	
	$updateObject = json_decode($_POST['updateObject']);
	
	//assigning all updateObject data to appropriate variable
	$id = "'" . $updateObject -> docID . "'";
	$fileName = "'" . $updateObject -> fileName . "'";
	$x1 = "'" . $updateObject -> x1 . "'";
	$y1 = "'" . $updateObject -> y1 . "'";
	$x2 = "'" . $updateObject -> x2 . "'";
	$y2 = "'" . $updateObject -> y2 . "'";
	
	$surveyOrSection = str_replace("'", "''", $updateObject -> surveyOrSection);
	$blockOrTract =  str_replace("'", "''", $updateObject -> blockOrTract);
	$lotOrAcres =  str_replace("'", "''", $updateObject -> lotOrAcres);
	$description =  str_replace("'", "''", $updateObject -> description);
	$client =  str_replace("'", "''", $updateObject -> client);
	$fieldBookInfo =  str_replace("'", "''", $updateObject -> fieldBookInfo );
	$relatedPapersFileNo =  str_replace("'", "''", $updateObject -> relatedPapersFileNo) ;
	$mapInfo = str_replace("'", "''",  $updateObject -> mapInfo) ;
	$date = str_replace("'", "''", $updateObject -> entryDate) ;
	$jobNumber = str_replace("'", "''", $updateObject -> jobNumber) ;
	
	$surveyOrSection = "'" . $surveyOrSection . "'";
	$blockOrTract = "'" . $blockOrTract. "'";
	$lotOrAcres = "'" . $lotOrAcres . "'";
	$description = "'" . $description . "'";
	$client = "'" . $client . "'";
	$fieldBookInfo = "'" . $fieldBookInfo . "'";
	$relatedPapersFileNo = "'" . $relatedPapersFileNo . "'";
	$mapInfo = "'" . $mapInfo. "'";
	$date = "'" . $date . "'";
	$jobNumber = "'" . $jobNumber . "'";
	
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
		
	$sql = "UPDATE transcription
			SET
			x1 = $x1,
			y1 = $y1,
			x2 = $x2,
			y2 = $y2,
			survey_or_section = $surveyOrSection,
			block_or_tract = $blockOrTract,
			lot_or_acres = $lotOrAcres,
			description = $description,
			client = $client,
			field_book_info = $fieldBookInfo,
			related_papers_file_no = $relatedPapersFileNo,
			map_info = $mapInfo,
			date = $date,
			job_number = $jobNumber
			WHERE document_id = $id AND x1 = $x1 AND y1 = $y1 AND x2 = $x2 AND y2 = $y2";
			
	if ($conn->query($sql) === TRUE) {
    echo "Successful Update";
} 

else {
	echo json_encode(array(
	'status' => 'error',
	'message'=> "Error: ". $conn->error.". Check for any mistakes in your Date field or any other field in the form."
    ));
}

?>
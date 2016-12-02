<?php
/* 	The purpose of this script is to be called when the user hits "Submit Entry". This script recieves the
	data for the new entry from an AJAX call and executes an SQL statement that inserts the data into
	a new row in our database. */
	
	$entryCoordinates = json_decode($_POST['Entry_Coordinates']);
	$x1 = $entryCoordinates -> x1;
	$y1 = $entryCoordinates -> y1;
	$x2 = $entryCoordinates -> x2;
	$y2 = $entryCoordinates -> y2;
	
	$docID = htmlspecialchars($_POST['Document_ID']);
	$File_Name = str_replace("'", "''", $_POST['File_Name']);
	$Survey_Or_Section = str_replace("'", "''", $_POST['Survey_Or_Section']);
	$Block_Or_Tract =str_replace("'", "''", $_POST['Block_Or_Tract']);
	$Lot_Or_Acres = str_replace("'", "''", $_POST['Lot_Or_Acres']);
	$Description = str_replace("'", "''", $_POST['Description']);
	$Client = str_replace("'", "''", $_POST['Client']);
	$Field_Book_Info = str_replace("'", "''", $_POST['Field_Book_Info']);
	$Related_Papers_File_No = str_replace("'", "''", $_POST['Related_Papers_File_No']);
	$Map_Info = str_replace("'", "''", $_POST['Map_Table_Info']);
	$Date = str_replace("'", "''", $_POST['Date']);
	$Job_Number = str_replace("'", "''", $_POST['Job_Number']);
	
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
	
	$sql = "INSERT INTO transcription (document_id,x1, y1, x2, y2, survey_or_section, block_or_tract, lot_or_acres,
			description, client, field_book_info, related_papers_file_no, map_info, date, job_number)
			VALUES ('$docID', '$x1', '$y1', '$x2', '$y2', '$Survey_Or_Section', '$Block_Or_Tract', '$Lot_Or_Acres',
			'$Description', '$Client', '$Field_Book_Info', '$Related_Papers_File_No', '$Map_Info', '$Date', '$Job_Number')";
			
	if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
	
?>
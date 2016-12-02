<?php
	$servername = "localhost";
	$username = "root";
	$password = "notroot";
	$dbname = "indicesinventory";

// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
	if ($conn->connect_error) { 
		die("Connection failed: " . $conn->connect_error);
	}
 
//Query that will selec the fields from the table
	$query = $conn->query("SELECT mp_name FROM mapkind");
						while($row = $query->fetch_array())
//Statement that echos the options into the select form with data stored in the database
							echo "<option  value='". $row[0] ."'>$row[0]</option>";
?>
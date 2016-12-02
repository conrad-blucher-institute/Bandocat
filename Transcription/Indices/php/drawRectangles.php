<?php
error_reporting(E_ALL);
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
$draw = 'convert ../images/test.jpg -stroke black -strokewidth 10 -fill "rgba( 40, 213, 187, 0.3 )" -draw "';

$sql = "SELECT x1, y1, x2, y2 FROM transcription";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $draw = $draw . ' rectangle '. $row["x1"]. "," . $row["y1"]. " " . $row["x2"]. "," . $row['y2'];
    }
} else {
    echo "0 results";
}
$conn->close();

$draw = $draw . '" ../images/output.jpg';

exec($draw);

print_r($draw);
	
?>
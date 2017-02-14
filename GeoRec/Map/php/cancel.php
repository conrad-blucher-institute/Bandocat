<?php
/* 	This script is called when the user selects the "cancel" button. 
	The script deletes the tiles directory that was created. */

	include('class.php');

	$script = json_decode( $_POST['jsonData']);
	$imageInfo = get_object_vars($script->fileName);

	A::deleteDir("../Temp/Tiles/" . $imageInfo['tempSubDirectory']);
?>

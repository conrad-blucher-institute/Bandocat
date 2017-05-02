<?php
/* 	This script is called when the user selects the "cancel" button. 
	The script deletes the tiles directory that was created. */

	include('class.php');

	$tilesDir = "../Temp/Tiles/";

	$script = json_decode( $_POST['jsonData']);
	$imageInfo = get_object_vars($script->fileName);

	A::deleteDir($tilesDir . $imageInfo['tempSubDirectory']);

	//clean old subdirectory inside the Temp/Tiles directory
	$dirArray = array_diff(scandir($tilesDir), array('..', '.'));
	foreach($dirArray as $dir) //deleting directory that has the last modification time more than 8 hours
	{
		if( strtotime("now") - filemtime($tilesDir . $dir) > 28800) //28800 sec = 8 hours
            A::deleteDir($tilesDir . $dir);
	}
?>

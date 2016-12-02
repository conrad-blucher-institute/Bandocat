<?php
	/* This script creates a JPEG from the tif so that it can be displayed 
	in our viewer on the transcription page. */
	session_start();
	error_reporting(E_ERROR);
	//Image variable which holds the filename of the document that was selected 
	//from the index page and was posted in the php file for convertion.
	$image = $_POST["fileName"];
	
	//Batch command that executes an Imagemagick conversion.
	//temp directory:
	$temp = "D:\\BlucherMaps\\xampp\\htdocs\\transcription_temp\\" . substr($image,strrpos($image,'\\')+1,strlen($image)-1);
	
	$command = "convert " . $image . ".tif " . $temp . ".jpg";
	echo $image;
	exec($command,$yaks1);
?>
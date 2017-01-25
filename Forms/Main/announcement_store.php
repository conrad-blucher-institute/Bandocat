<?php
$myFile = "announcement.json";
$fh = fopen($myFile, 'w') or die("can't open file");
$stringData = $_GET["announcement"];
$announcement = array();
$announcement = json_encode($stringData);
fwrite($fh, $announcement);
fclose($fh)
?>
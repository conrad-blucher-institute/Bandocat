<?php
require '../../Library/DBHelper.php';
require '../../Library/AnnouncementDBHelper.php';
$announceHelper = new AnnouncementDBHelper();
$data = $_POST;

$title = $data['title'];
$message = $data['message'];
$endDate = $data['endDate'];
$expDate = date("Y-m-d H:i:s", strtotime($endDate));
$user = $data['user'];

$announcement = $announceHelper->SP_ANNOUNCEMENT_INSERT($title, $message, $expDate, $user)
?>
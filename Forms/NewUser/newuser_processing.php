<?php
include '../../Library/SessionManager.php';
include '../../Library/DBHelper.php';
$session = new SessionManager();
$UserDB = new DBHelper();
$user= $_POST['data'];
$fullname = $user[0];
$username = $user[1];
$password = $user[2];
$email = $user[3];
$roleID = $user[4];
$oMessage = "";
$result = $UserDB->SP_USER_INSERT($username, $password, $fullname, $email, $roleID, $oMessage);
echo $oMessage;
?>
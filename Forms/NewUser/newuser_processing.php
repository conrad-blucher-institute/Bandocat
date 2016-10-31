<?php include '../../Library/DBHelper.php';
$UserDB = new DBHelper();
$user= $_POST['data'];
//$UserDB->SP_USER_INSERT($user[0],)
$fullname = $user[0];
$username = $user[1];
$email = $user[2];
//echo $user[3];
?>
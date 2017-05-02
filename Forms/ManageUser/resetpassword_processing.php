<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin() == false) {
    header('Location: ../../');
}
include '../../Library/DBHelper.php';
$DB = new DBHelper();

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$iNewPassword = generateRandomString(6);
$ret = $DB->USER_UPDATE_ADMIN_RESET_PASSWORD($_POST['ddl_user'],$iNewPassword); //given userID and new password, update
echo json_encode($iNewPassword);
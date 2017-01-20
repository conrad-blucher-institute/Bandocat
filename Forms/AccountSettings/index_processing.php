<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(!isset($_GET['action']))
    header('Location: ../../');
include '../../Library/DBHelper.php';
$DB = new DBHelper();

$action = $_GET['action'];
switch($action) {
    case "updateUserInfo":
        $ret = $DB->USER_UPDATE_INFO($session->getUserID(), $_POST['txtEmail'], $_POST['txtName']);
        if($ret != false)
            echo "Success!";
        else echo "Fail to update";
        break;
    case "updatePassword":
        $ret = $DB->USER_UPDATE_PASSWORD($session->getUserID(), $_POST['txtOldPassword'], $_POST['txtPassword']);
        echo $ret; //1 = update success, 0 = not update, false = fail to connect to db
        break;
    default:
        break;
}

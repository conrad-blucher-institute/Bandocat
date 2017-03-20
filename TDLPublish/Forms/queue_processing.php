<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin()) {
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    $DB->SWITCH_DB($_POST['ddlCollection']);

}
else header('Location: ../../');

switch($_GET['action'])
{
    case "load":
        echo json_encode($DB->PUBLISHING_GET_PUBLISH_QUEUE());
        break;
    case "reset":
        echo json_encode($DB->PUBLISHING_RESET_QUEUE());
        break;
    case "push":
        if($_POST['howMany'] == "")
            echo json_encode($DB->PUBLISHING_PUSH_TO_QUEUE(null));
        else json_encode($DB->PUBLISHING_PUSH_TO_QUEUE($_POST['howMany']));
        break;
    case "displaylog":
        echo nl2br(file_get_contents("../CRON/log.txt"));
        break;
    default: break;
}

?>
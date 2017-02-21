<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin() == false) {
    header('Location: ../../');
} 
require('../../Library/DBHelper.php');
$DB = new DBHelper();
$ret = $DB->USER_ROLE_UPDATE($_POST['ddl_user'],$_POST['
']);
echo $ret;
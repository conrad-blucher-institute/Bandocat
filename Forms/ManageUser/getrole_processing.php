<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin() == false) {
    header('Location: ../../');
}
require('../../Library/DBHelper.php');
$DB = new DBHelper();
$us =$_GET['user'];
$ret = $DB->GET_USER_ROLE($us);

echo $ret;
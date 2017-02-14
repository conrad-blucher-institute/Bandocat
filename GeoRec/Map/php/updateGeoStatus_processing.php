<?php
include '../../../Library/SessionManager.php';
$session = new SessionManager();
require('../../../Library/DBHelper.php');
require('../../../Library/MapDBHelper.php');
$DB = new MapDBHelper();
$ret =$DB->SWITCH_DB($_GET['col']);
if($ret)
    $ret = $DB->DOCUMENT_GEORECSTATUS_UPDATE($_POST['txtDocID'],$_POST['ddlGeoStatus']);
echo $ret;
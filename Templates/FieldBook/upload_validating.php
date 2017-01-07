<?php
require '../../Library/SessionManager.php';
require '../../Library/DBHelper.php';
require '../../Library/FieldBookDBHelper.php';
$session = new SessionManager();
$DB = new FieldBookDBHelper();
$fileNames = $_POST['fileNames'];
$ret = array();
foreach($fileNames as $f)
    array_push($ret,$DB->TEMPLATE_FIELDBOOK_CHECK_EXIST_RECORD_BY_FILENAME($_GET['col'],$f));
echo json_encode($ret);

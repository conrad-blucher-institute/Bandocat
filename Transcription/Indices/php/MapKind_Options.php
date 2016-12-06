<?php
include '../../../Library/SessionManager.php';
$session = new SessionManager();
require('../../../Library/DBHelper.php');
require('../../../Library/IndicesDBHelper.php');
require('../../../Library/ControlsRender.php');
$Render = new ControlsRender();
$DB = new IndicesDBHelper();
echo json_encode($DB->GET_INDICES_MAPKIND($_POST['collection']));

?>
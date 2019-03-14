<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
$userID = $session->getUserID();
$userName = $session->getUserName();
require('../../Library/DBHelper.php');
$DB = new DBHelper();

// SQL server connection information
$sql_details = array(
    'user' => $DB->getUser(),
    'pass' => $DB->getPwd(),
    'db'   => 'bandocatdb',
    'host' => $DB->getHost()
);

$data = $DB->GET_ALL_TICKET_DATA();

//var_dump($data);
echo json_encode(array("data" => $data));
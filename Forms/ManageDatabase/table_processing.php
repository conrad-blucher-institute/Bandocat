<?php
/**
 * Created by PhpStorm.
 * User: rortiz18
 * Date: 3/28/2019
 * Time: 4:35 PM
 */
include '../../Library/SessionManager.php';
$session = new SessionManager();
$userID = $session->getUserID();
$userName = $session->getUserName();
$iName =
require('../../Library/DBHelper.php');
$DB = new DBHelper();

// SQL server connection information
$sql_details = array(
    'user' => $DB->getUser(),
    'pass' => $DB->getPwd(),
    'db'   => 'bandocatdb',
    'host' => $DB->getHost()
);

$data = $DB->SP_GET_COLLECTION_CONFIG($iName);

//var_dump($data);
echo json_encode(array("data" => $data));
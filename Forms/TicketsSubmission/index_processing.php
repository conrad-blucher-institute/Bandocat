<?php
include '../../Library/SessionManager.php';
require '../../Library/DBHelper.php';
require '../../Library/Ticket.php';

$session = new SessionManager();
$DB = new DBHelper();
$ticket = new Ticket();

$collection_id = $_POST['dbname'];
$desc = $_POST['description'];
$subject = $_POST['subject'];
$libIndex = $_POST['libIndex'];
$error = $_POST['error'];
$index = json_decode($_POST['libIndex']); // decoding json

// Example print_r
/*Array
(
    [0] => stdClass Object
(
    [libraryIndex] => 100-_10
        )

    [1] => stdClass Object
(
    [libraryIndex] => 100-_9
        )

)*/
// print_r($libIndex);

// Getting database name
$collectionName = $DB->GET_COLLECTION_DATABASE_NAME($collection_id);
$flag = 0;
$message = "";
$documents = array();

// Preparing request and data
$request = array(
    "collectionID" => $collection_id,
    "description" => $desc,
    "subject" => $subject,
    "libraryIndex" => $libIndex,
    "error" => $error,
    "posterID" => $session->getUserID()
);

$response = $ticket->PROCESS_TICKET_EVENT("insert", $request);

echo json_encode($response);
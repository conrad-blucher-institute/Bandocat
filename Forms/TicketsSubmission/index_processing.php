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
// We need to check to make sure the library index exists
/*if(is_array($index))
{
    foreach($index as $libraryIndex)
    {
        // Getting rid of weird data structure
        $document = $libraryIndex->libraryIndex;

        $documentID = $DB->CHECK_DOCUMENT_EXISTS_LIBRARY_INDEX($collectionName, $document);

        if($documentID == false)
        {
            $message = "Library Index $document does not exists in $collectionName";
            echo json_encode($message);
            $flag = 0;
            break;
        }

        else
        {
            $flag = 1;
            $object = array(
                "libraryIndex" => $document,
                "documentID" => $documentID
            );

            // Creating 2d array to hold library index and document id of each one found
            array_push($documents, $object);
        }
    }
}

// Just a single library index
else
{
    // Only one library index, has to be first element of array
    $index = $index[0]->libraryIndex;

    $documentID = $DB->CHECK_DOCUMENT_EXISTS_LIBRARY_INDEX($collectionName, $index);

    if($documentID == false)
    {
        $message = "Library Index $index does not exist in $collectionName.";
        echo json_encode($message);
        $flag = 0;
    }

    else
    {
        $flag = 1;
        $object = array(
            "libraryIndex" => $document,
            "documentID" => $documentID
        );

        // Creating 2d array to hold library index and document id of each one found
        array_push($documents, $object);
    }
}

// Checking flag
if($flag)
{
    $result = $DB->SP_TICKET_INSERT_ERROR($subject,$session->getUserID(),$collection_id,$desc, $libIndex, $error, json_encode($documents));

    $object = array(
        "status" => $result
    );

    array_push($documents, $object);

    echo json_encode($documents);
}*/
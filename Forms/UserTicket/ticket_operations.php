<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 3/4/2019
 * Time: 3:09 PM
 */
require_once "../../Library/DBHelper.php";
require_once  "../../Library/Ticket.php";

$ticket = new Ticket();

if(isset($_POST["update"]) && isset($_POST["data"]))
{
    // Extracting data
    $json = $_POST["data"];
    $ticketID = $json[0]["value"];
    $collectionID = $json[1]["value"];
    $documents = array();
    $ticketError = "";
    $problemDescription = "";

    // Looping through all txtSubjects
    foreach($json as $array)
    {
        // For library indexes
        if($array["name"] == "txtSubject")
        {
            array_push($documents, $array["value"]);
        }

        // Error ticket
        else if($array["name"] == "errorTicket")
        {
            $ticketError = $array["value"];
        }

        // Comments
        else if($array["name"] == "txtDesc")
        {
            $problemDescription = $array["value"];
        }

        else
        {
            continue;
        }
    }

    // Building data array to make a request
    $data = array(
        "ticketID" => $ticketID,
        "collectionID" => $collectionID,
        "documents" => $documents,
        "errorID" => $ticketError,
        "problemDescription" => $problemDescription
    );

    echo json_encode($ticket->PROCESS_TICKET_EVENT("update", $data));
}

if(isset($_POST["delete"]) && isset($_POST["data"]))
{
    $ticketID = $_POST["data"][0]["value"];

    // Call ticket event handler
    echo json_encode($ticket->PROCESS_TICKET_EVENT("delete", $ticketID));
}
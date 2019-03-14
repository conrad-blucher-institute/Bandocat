<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 2/28/2019
 * Time: 11:39 AM
 */

class Ticket extends DBHelper
{
    // Represents one row from the ticket table in the database
    private $data;

    // Data table information
    private $poster;
    private $solvedBy;
    private $dateSubmitted;
    private $dateClosed;
    private $problemDescription;
    private $collectionName;

    // Boolean properties
    private $status;
    private $solved;

    // Array that will hold html tags for links to documents
    // The link will take the user to the edit/view page of the library index
    private $links;

    // Array of library indexes
    private $libraryIndex;

    /**
     * Ticket constructor.
     * @param $data
     */
    public function __construct()
    {
        // Need to run parent constructor
        // Running the constructor automatically connects this class to the bandocatdb
        parent::__construct();
    }

    /**
     * @return string
     */
    public static function getIniDir()
    {
        return self::$ini_dir;
    }

    /**
     * @param string $ini_dir
     */
    public static function setIniDir($ini_dir)
    {
        self::$ini_dir = $ini_dir;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param mixed $pwd
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    }

    /**
     * @return mixed
     */
    public function getMaindb()
    {
        return $this->maindb;
    }

    /**
     * @param mixed $maindb
     */
    public function setMaindb($maindb)
    {
        $this->maindb = $maindb;
    }

    /**
     * @return mixed
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * @param mixed $conn
     */
    public function setConn($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return mixed
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * @param mixed $collectionName
     */
    public function setCollectionName($collectionName)
    {
        $this->collectionName = $collectionName;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param mixed $poster
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    /**
     * @return mixed
     */
    public function getSolvedBy()
    {
        return $this->solvedBy;
    }

    /**
     * @param mixed $solvedBy
     */
    public function setSolvedBy($solvedBy)
    {
        $this->solvedBy = $solvedBy;
    }

    /**
     * @return mixed
     */
    public function getDateSubmitted()
    {
        return $this->dateSubmitted;
    }

    /**
     * @param mixed $dateSubmitted
     */
    public function setDateSubmitted($dateSubmitted)
    {
        $this->dateSubmitted = $dateSubmitted;
    }

    /**
     * @return mixed
     */
    public function getDateClosed()
    {
        return $this->dateClosed;
    }

    /**
     * @param mixed $dateClosed
     */
    public function setDateClosed($dateClosed)
    {
        $this->dateClosed = $dateClosed;
    }

    /**
     * @return mixed
     */
    public function getProblemDescription()
    {
        return $this->problemDescription;
    }

    /**
     * @param mixed $problemDescription
     */
    public function setProblemDescription($problemDescription)
    {
        $this->problemDescription = $problemDescription;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSolved()
    {
        return $this->solved;
    }

    /**
     * @param mixed $solved
     */
    public function setSolved($solved)
    {
        $this->solved = $solved;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param mixed $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }

    /**
     * @return mixed
     */
    public function getLibraryIndex()
    {
        return $this->libraryIndex;
    }

    /**
     * @param mixed $libraryIndexes
     */
    public function setLibraryIndex($libraryIndex)
    {
        $this->libraryIndex = $libraryIndex;
    }

    public function CHECK_DUPLICATE_LIBRARY_INDEXES($array)
    {
        return array_diff_key( $array , array_unique( $array ) );
    }

    /**********************************************
     * Function: PROCESS_TICKET_EVENT
     * Description: This function will be used to navigate the request to the correct function and will also handle preprocessing.
     * Parameter(s):
     * @param $event - string - determines postprocessing and which functions to call
     * @param $data - unknown - it depends on what action is taken
     * @return $response
     *********************************************/
    public function PROCESS_TICKET_EVENT($event, $data)
    {
        $this->setData($data);
        $status = 0; // Flag that will determine if the request was successful

        // Navigating through menu
        switch($event)
        {
            // Database operations
            case "insert":
                {
                    $response = $this->PROCESS_TICKET_INSERT();
                    // Check if process ticket insert failed
                    if(array_key_exists("message", $response))
                    {
                        // Library index doesn't exists, stop here and return the response
                        break;
                    }

                    // Insert ticket
                    $status = $this->SP_INSERT_NEW_TICKET($this->data["subject"], $this->data["posterID"],
                        $this->data["collectionID"], $this->data["description"], $this->data["libraryIndex"], $this->data["error"], json_encode($response));

                    // Writing response, this only occurs if the ticket was inserted correctly
                    $response = array(
                        "status" => $status,
                        "message" => "Ticket has been successfully submitted!"
                    );
                    break;
                }

            case "delete":
                {
                    // Data in here is just an integer, which is the tickets id
                    $response = $this->DELETE_TICKET($data);
                    break;
                }

            case "update":
                {
                    // Making sure ticket doesnt have duplicated values
                    if(count($this->CHECK_DUPLICATE_LIBRARY_INDEXES($this->data["documents"])) > 0)
                    {
                        $response = array(
                            "status" => false,
                            "libraryIndex" => "",
                            "message" => "One or more library indexes are the same. Each library index must be unique."
                        );
                        break;
                    }

                    $response = $this->PROCESS_TICKET_UPDATE();

                    // Check if process ticket insert failed
                    if(array_key_exists("message", $response))
                    {
                        // Library index doesn't exists, stop here and return the response
                        break;
                    }

                    // Execution only gets here if the documents all exist
                    //$this->UPDATE_TICKET(json_encode($response));
                    $this->data["subject"] = $this->BUILD_SUBJECT($this->data["documents"]);
                    $this->data["jsonLibraryIndex"] = $this->BUILD_JSON_LIBRARY_INDEX($this->data["documents"]);

                    $response = $this->UPDATE_TICKET(json_encode($response));

                    break;
                }
            default:
                $response = "Cannot process event '$event'.";
        }

        return $response;
    }

    public function PROCESS_TICKET_UPDATE()
    {
        /*          Array
            (
                [ticketID] => 2484
                [collectionID] => 1
                [documents] => Array
                    (
                        [0] => 100-_10
                        [1] => 100-_9
                    )

                [errorID] => 13
                [problemDescription] => test
            )
        print_r($array);*/

        // Set metadata
        $array = $this->getData();
        $this->setCollectionName($this->GET_COLLECTION_DATABASE_NAME($array["collectionID"]));
        $response = array();

        // Checking if documents exists
        foreach($array["documents"] as $document)
        {
            $documentID = $this->CHECK_DOCUMENT_EXISTS_LIBRARY_INDEX($this->getCollectionName(), $document);

            if($documentID != false)
            {
                $object = array(
                    "libraryIndex" => $document,
                    "documentID" => $documentID
                );

                // Add to documents array
                array_push($response, $object);
            }

            else
            {
                return array(
                    "status" => false,
                    "libraryIndex" => $document,
                    "message" => "Library Index $document does not exist in $this->collectionName."
                );
            }
        }

        return $response;
    }

    public function PROCESS_TICKET_INSERT()
    {
        // This is what the data needs to look like
        /*Array
        (
            [collectionID] => 1
            [description] => test
            [subject] => 100-_10
            [libraryIndex] => [{"libraryIndex":"100-_10"}]
            [error] => 14
        )
        print_r($this->data);
        */
        // Decoding JSON object
        $this->setLibraryIndex(json_decode($this->data["libraryIndex"]));
        $this->setCollectionName($this->GET_COLLECTION_DATABASE_NAME($this->data["collectionID"]));
        $response = array();

        // We need to check if this is an array because there can be multiple library indexes
/*      Array
        (
            [0] => stdClass Object
                (
                    [libraryIndex] => 100-_10
                )

            [1] => stdClass Object
            (
                [libraryIndex] => 100-_9
             )

        )
        print_r($this->data["libraryIndex"]);*/

        // We must get the collection name that the user selected


        if(is_array($this->libraryIndex))
        {
            foreach($this->libraryIndex as $document)
            {
                $documentID = $this->CHECK_DOCUMENT_EXISTS_LIBRARY_INDEX($this->collectionName, $document->libraryIndex);

                // Checking to make sure the library index exists
                if($documentID != false)
                {
                    $object = array(
                        "libraryIndex" => $document->libraryIndex,
                        "documentID" => $documentID
                    );

                    // Pushing item to array
                    array_push($response, $object);
                }

                else
                {
                    return array(
                        "status" => false,
                        "libraryIndex" => $document->libraryIndex,
                        "message" => "Library Index $document->libraryIndex does not exist in $this->collectionName."
                    );
                }
            }
        }

        else
        {
            // Only one library index, has to be first element of array
            $document = $this->libraryIndex[0]->libraryIndex;

            $documentID = $this->CHECK_DOCUMENT_EXISTS_LIBRARY_INDEX($this->collectionName, $document);

            // If we cannot find the document index, the ticket cannot be submitted
            if($documentID == false)
            {
                return array(
                    "status" => false,
                    "libraryIndex" => $document->libraryIndex,
                    "message" => "Library Index $document->libraryIndex does not exist in $this->collectionName."
                );
            }

            else
            {
                $object = array(
                    "libraryIndex" => $document->libraryIndex,
                    "documentID" => $documentID
                );

                // Creating 2d array to hold library index and document id of each one found
                array_push($response, $object);
            }
        }

        return $response;
    }

    public function BUILD_SUBJECT($documents)
    {
        return implode(", ", $documents);
    }

    public function BUILD_JSON_LIBRARY_INDEX($documents)
    {
        $json = array();

        foreach($documents as $libraryIndex)
        {
            // Building object then pushing it to json
            $object["libraryIndex"] = $libraryIndex;
            array_push($json, $object);
        }

        return json_encode($json);
    }

    public function DELETE_TICKET($ticketID)
    {
        //Switch to correct DB
        $this->getConn()->exec('USE ' . $this->maindb);
        $data = $this->getData();

        /* PREPARE STATEMENT */
        /* Prepares the SQL query, and returns a statement handle to be used for further operations on the statement*/
        //CALL is sql for calling the function built into the db at localhost/phpmyadmin
        $call = $this->getConn()->prepare("DELETE FROM `ticket` WHERE `ticketID` = :iTicketID");

        //Error handling
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        //bind parameters to the sql statement
        $call->bindParam("iTicketID", $ticketID, PDO::PARAM_INT);

        /* EXECUTE STATEMENT */
        $call->execute();
        if ($call)
            return true;
        return false;
    }

    public function UPDATE_TICKET($jsonlink)
    {
        //Switch to correct DB
        $this->getConn()->exec('USE ' . $this->maindb);
        $data = $this->getData();

        /* PREPARE STATEMENT */
        /* Prepares the SQL query, and returns a statement handle to be used for further operations on the statement*/
        //CALL is sql for calling the function built into the db at localhost/phpmyadmin
        $call = $this->getConn()->prepare("UPDATE `ticket` SET `subject`= :iSubject, `collectionID`= :iCollectionID,`description`= :iDescription,
`errorID`= :iError,`jsonlibindex`= :iLibraryIndex,`jsonlink`= :iDocuments WHERE `ticketID` = :iTicketID");

        //Error handling
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        //bind parameters to the sql statement
        $call->bindParam("iSubject", $data["subject"], PDO::PARAM_STR, strlen($data["subject"]));
        $call->bindParam("iCollectionID", $data["collectionID"], PDO::PARAM_INT);
        $call->bindParam("iDescription", $data["problemDescription"], PDO::PARAM_STR, strlen($data["problemDescription"]));
        $call->bindParam("iLibraryIndex", $data["jsonLibraryIndex"], PDO::PARAM_STR, strlen($data["jsonLibraryIndex"]));
        $call->bindParam("iError", $data["errorID"], PDO::PARAM_INT);
        $call->bindParam("iDocuments", $jsonlink, PDO::PARAM_STR, strlen($jsonlink));
        $call->bindParam("iTicketID", $data["ticketID"], PDO::PARAM_INT);

        /* EXECUTE STATEMENT */
        $call->execute();
        if ($call)
            return true;
        return false;
    }

    /**********************************************
     * Function: SP_INSERT_NEW_TICKET
     * Description: Inserts a ticket into the DB when a user submits a ticket but includes an error selected from a ddl
     * Parameter(s):
     * $iSubject (in string) - Subject or library index
     * $iPosterID (in int) - userID of submitter
     * $iCollectionID (in int) - collectionID in which the ticket submit for
     * $iDescription (in string) - description of what goes wrong
     * $iErrorID (in int) - the errorID of the error the user selected
     * $iDocuments (in string) - a json object that holds the library index and documentID of a document
     * Return value(s): true if success, false if fail
     ***********************************************/
    public function SP_INSERT_NEW_TICKET($iSubject, $iPosterID, $iCollectionID, $iDescription, $iLibraryIndex, $error, $iDocuments)
    {
        //Switch to correct DB
        $this->getConn()->exec('USE ' . $this->maindb);

        /* PREPARE STATEMENT */
        /* Prepares the SQL query, and returns a statement handle to be used for further operations on the statement*/
        //The ? in the functions parameter list is a variable that we bind a few lines down.
        //CALL is sql for calling the function built into the db at localhost/phpmyadmin
        $call = $this->getConn()->prepare("CALL SP_TICKET_INSERT_ERROR(?,?,?,?,?,?,?)");

        //Error handling
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        //bind parameters to the sql statement
        $call->bindParam(1, $iSubject, PDO::PARAM_STR, strlen($iSubject));
        $call->bindParam(2, $iPosterID, PDO::PARAM_INT);
        $call->bindParam(3, $iCollectionID, PDO::PARAM_INT);
        $call->bindParam(4, $iDescription, PDO::PARAM_STR, strlen($iDescription));
        $call->bindParam(5, $iLibraryIndex, PDO::PARAM_STR, strlen($iLibraryIndex));
        $call->bindParam(6, $error, PDO::PARAM_INT);
        $call->bindParam(7, $iDocuments, PDO::PARAM_STR, strlen($iDocuments));

        /* EXECUTE STATEMENT */
        $call->execute();
        if ($call)
            return true;
        return false;
    }
}
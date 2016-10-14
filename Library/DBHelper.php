<?php
//NOTE:
/*
- BEFORE EXECUTING QUERIES OR CALL SPs, MUST EITHER:
    + Use prepare statement
    + OR Sanitize input using mysqli_real_escape_string

- BIND PARAMETER: Types: s = string, i = integer, d = double,  b = blob
*/

//PLEASE USE THIS TEMPLATE TO COMMENT ON FUNCTIONS
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/

class DBHelper
{
    //Members
    protected $conn;
    static private $host = "localhost";
    static private $user = "root";
    static private $pwd = "notroot";

    //Getter and setters
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
     * @return string
     */
    public static function getHost()
    {
        return self::$host;
    }

    /**
     * @return string
     */
    public static function getUser()
    {
        return self::$user;
    }

    /**
     * @return string
     */
    public static function getPwd()
    {
        return self::$pwd;
    }


    //Constructor
    function DBHelper()
    {
        if($this->getConn() == null)
            $this->DB_CONNECT(null);
    }

    //IMPORTANT: Default Host, username, password can be changed here!
    /**********************************************
     * Function: DB_CONNECT
     * Description: Connect to XAMPP db. All functions connect to db using this.
     * Parameter(s):
     * $db (string) - name of database to be connected to
     * Return value(s):
     * $conn (object) - return connection object if success
     * - return -1 if failed
     ***********************************************/

    function DB_CONNECT($db)
    {
        if ($db == "" || $db == null) //empty parameter = default = bandocatdb
            $db = "bandocatdb";
        $this->conn = new PDO('mysql:host=' . $this->getHost() . ';dbname=' . $db, $this->getUser(), $this->getPwd());
        return 0;
    }

    function DB_CLOSE()
    {
        $this->setConn(null);
    }

    /**********************************************
     * Function: SP_GET_COLLECTION_CONFIG
     * Description: GET COLLECTION CONFIGURATION
     * Parameter(s):
     * $iName (in string) - input DB Name
     * Return value(s):
     * $result (assoc array) - return above values in an assoc array
     ***********************************************/
    function SP_GET_COLLECTION_CONFIG($iName)
    {
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_GET_COLLECTION_CONFIG(?,@oDisplayName,@oDbName,@oStorageDir,@oPublicDir,@oThumbnailDir,@oTemplateID)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iName, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,50);
        /* EXECUTE STATEMENT */
        $call->execute();
        /* RETURN RESULT */
        $select = $this->getConn()->query('SELECT @oDisplayName AS DisplayName,@oDbName AS DbName,@oStorageDir AS StorageDir,@oPublicDir AS PublicDir,@oThumbnailDir AS ThumbnailDir,@oTemplateID as TemplateID');
        $result = $select->fetch(PDO::FETCH_ASSOC);
        $result["Name"] = htmlspecialchars($iName);
        return $result;
    }


    /**********************************************
     * Function: SP_USER_AUTH
     * Description: USER LOGIN AUTHENTICATION
     * Parameter(s):
     * $iUsername (in string) - input username
     * $iPassword (in string) - input password (before md5)
     * &$oMessage (out ref string) - output message response
     * &$oUserID (out ref int) - output UserID if success
     * &$oRole (out ref int) - output User Role if success
     * Return value(s): NONE
     ***********************************************/
    function SP_USER_AUTH($iUsername, $iPassword, &$oMessage, &$oUserID, &$oRole)
    {
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_USER_AUTH(?,?,@oMessage,@oUserID,@oRole)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iUsername, PDO::PARAM_STR,32);
        $call->bindParam(2, md5($iPassword), PDO::PARAM_STR,64);

        /* EXECUTE STATEMENT */
        $call->execute();

        /* RETURN RESULT */
        $select = $this->getConn()->query('SELECT @oMessage,@oUserID, @oRole');
        $result = $select->fetch(PDO::FETCH_ASSOC);
        $oMessage = $result['@oMessage'];
        $oUserID = $result['@oUserID'];
        $oRole = $result['@oRole'];
    }

    /**********************************************
     * Function: GET_COLLECTION_FOR_DROPDOWN
     * Description: GET COLLECTIONS INFO FOR DROPDOWN
     * Parameter(s): NONE
     * Return value(s):
     * $result  (associative array) - return associative array of collection info
     ***********************************************/
    function GET_COLLECTION_FOR_DROPDOWN()
    {
        $sth = $this->getConn()->prepare("SELECT `collectionID`,`displayname` FROM `bandocatdb`.`collection`");
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**********************************************
     * Function: SP_TICKET_INSERT
     * Description: Insert ticket when user submit new ticket
     * Parameter(s):
     * $iSubject (in string) - Subject or library index
     * $iPosterID (in int) - userID of submitter
     * $iCollectionID (in int) - collectionID in which the ticket submit for
     * $iDescription (in string) - description of what goes wrong
     * Return value(s): true if success, false if fail
     ***********************************************/
    function SP_TICKET_INSERT($iSubject, $iPosterID, $iCollectionID, $iDescription)
    {
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_TICKET_INSERT(?,?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iSubject, PDO::PARAM_STR,strlen($iSubject));
        $call->bindParam(2, $iPosterID, PDO::PARAM_INT);
        $call->bindParam(3, $iCollectionID, PDO::PARAM_INT);
        $call->bindParam(4, $iDescription, PDO::PARAM_STR,strlen($iDescription));
        /* EXECUTE STATEMENT */
        $call->execute();
        if($call)
            return true;
        return false;
    }

    /**********************************************
     * Function: SP_LOG_WRITE
     * Description: Insert new log entry
     * Parameter(s):
     * $iAction (in string) - input, edit or ....
     * $iCollectionID (in int) - collection id of the document
     * $iDocID (in int) - document ID
     * $iUserID (in string) -  userID of user who performs the action
     * $iStatus (in string) - success or fail
     * Return value(s): true if success, false if fail
     ***********************************************/
    function SP_LOG_WRITE($iAction,$iCollectionID,$iDocID, $iUserID,$iStatus)
    {
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_LOG_WRITE(?,?,?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iAction, PDO::PARAM_STR,10);
        $call->bindParam(2, $iCollectionID, PDO::PARAM_INT);
        $call->bindParam(3, $iDocID, PDO::PARAM_INT);
        $call->bindParam(4, $iUserID, PDO::PARAM_INT);
        $call->bindParam(5, $iStatus, PDO::PARAM_STR,7);
        /* EXECUTE STATEMENT */
        $call->execute();
        if($call)
            return true;
        return false;
    }

    /**********************************************
     * Function: SP_ADMIN_TICKET_SELECT
     * Description: GIVEN A TICKET ID, RETURN INFORMATION ABOUT TICKET
     * Parameter(s):
     * $iTicketID (in Integer) - ticket ID
     * Return value(s):
     * $result (assoc array) - return a ticket info into a associative array
     ***********************************************/
    function SP_ADMIN_TICKET_SELECT($iTicketID)
    {
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_ADMIN_TICKET_SELECT(?,@oSubject,@oSubmissionDate,@oSolvedDate,@oPoster,@oCollection,@oDescription,@oNotes,@oSolver,@oStatus)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, htmlspecialchars($iTicketID), PDO::PARAM_INT,11);
        /* EXECUTE STATEMENT */
        $call->execute();
        /* RETURN RESULT */
        $select = $this->getConn()->query('SELECT @oSubject AS Subject,@oSubmissionDate AS SubmissionDate,@oSolvedDate AS SolvedDate,@oPoster AS Submitter,@oCollection AS Collection,@oDescription AS Description,@oNotes AS Notes,@oSolver AS Solver,@oStatus AS Status');
        $result = $select->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**********************************************
     * Function: SP_TEMPLATE_MAP_DOCUMENT_SELECT
     * Description: GIVEN collection name & document ID, RETURN INFORMATION ABOUT Document
     * Parameter(s):
     * collection (in string) - name of the collection
     * $iDocID (in Integer) - document ID
     * Return value(s):
     * $result (assoc array) - return a document info in an associative array, or FALSE if failed
     ***********************************************/
    function SP_TEMPLATE_MAP_DOCUMENT_SELECT($collection,$iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if($dbname != null && $dbname != "")
        {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_DOCUMENT_SELECT(?,@oLibraryIndex,@oTitle,@oSubtitle,@oIsMap,@oMapScale,@oHasNorthArrow,@oHasStreets,@oHasPOI,@oHasCoordinates,@oHasCoast,@oFileName,@oFileNameBack,@oNeedsReview,@oComments,@oCustomerName,@oStartDate,@oEndDate,@oFieldBookNumber,@oFieldBookPage,@oReadability,@oRectifiability,@oCompanyName,@oType,@oMedium,@oAuthorName,@oFileNamePath,@oFileNameBackPath)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT,11);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oLibraryIndex AS LibraryIndex,@oTitle AS Title,@oSubtitle AS Subtitle,@oIsMap AS IsMap,@oMapScale AS MapScale,@oHasNorthArrow AS HasNorthArrow,@oHasStreets AS HasStreets,@oHasPOI AS HasPOI,@oHasCoordinates AS HasCoordinates,@oHasCoast AS HasCoast,@oFileName AS FileName,@oFileNameBack AS FileNameBack,@oNeedsReview AS NeedsReview,@oComments AS Comments,@oCustomerName AS CustomerName,@oStartDate AS StartDate,@oEndDate AS EndDate,@oFieldBookNumber AS FieldBookNumber,@oFieldBookPage AS FieldBookPage,@oReadability AS Readability,@oRectifiability AS Rectifiability,@oCompanyName AS CompanyName,@oType AS Type,@oMedium AS Medium,@oAuthorName AS AuthorName,@oFileNamePath AS FileNamePath,@oFileNameBackPath AS FileNameBackPath');
            $result = $select->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        else return false;
    }

    /**********************************************
     * Function: GET_TEMPLATE_MAP_MEDIUM_FOR_DROPDOWN
     * Description: GET DOCUMENT MEDIUMS FOR DROPDOWN LIST
     * Parameter(s): $collection (in String) - Name of the collection
     * Return value(s):
     * $result  (array) - return array of document medium
     ***********************************************/
    function GET_TEMPLATE_MAP_MEDIUM_FOR_DROPDOWN($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `mediumname` FROM `documentmedium`");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        }
        else return false;
    }

    function GET_AUTHOR_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `authorname` FROM `author`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        }
        else return false;
    }

    function GET_CUSTOMER_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `customername` FROM `customer`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        }
        else return false;
    }

    function GET_COMPANY_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `companyname` FROM `company`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        }
        else return false;
    }
}
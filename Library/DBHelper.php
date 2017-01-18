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
    static private $maindb = 'bandocatdb';

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
    function __construct()
    {
        /*if not currently connected, attempt to connect to DB*/
        if ($this->getConn() == null)
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
        /* assign conn as a PHP Data Object, concat the host, user and pwd */
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
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_GET_COLLECTION_CONFIG(?,@oDisplayName,@oDbName,@oStorageDir,@oPublicDir,@oThumbnailDir,@oTemplateID,@oCollectionID)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iName, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 50);
        /* EXECUTE STATEMENT */
        $call->execute();
        /* RETURN RESULT */
        $select = $this->getConn()->query('SELECT @oDisplayName AS DisplayName,@oDbName AS DbName,@oStorageDir AS StorageDir,@oPublicDir AS PublicDir,@oThumbnailDir AS ThumbnailDir,@oTemplateID as TemplateID,@oCollectionID as CollectionID');
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
        /* Prepares the SQL query, and returns a statement handle to be used for further operations on the statement*/

        $call = $this->getConn()->prepare("CALL SP_USER_AUTH(?,?,@oMessage,@oUserID,@oRole)");
        $iPassword = md5($iPassword);
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        /* bindParam is used to attach variables to the SQL query */
        $call->bindParam(1, $iUsername, PDO::PARAM_STR, 32);
        $call->bindParam(2, $iPassword, PDO::PARAM_STR, 64);

        /* EXECUTE STATEMENT */
        $call->execute();
        /* RETURN RESULT */
        /* selecting the DB message, userid, and roll */
        $select = $this->getConn()->query('SELECT @oMessage,@oUserID, @oRole');
        /* returning the selected data */
        $result = $select->fetch(PDO::FETCH_ASSOC);
        /*store data into variables */
        $oMessage = $result['@oMessage'];
        $oUserID = $result['@oUserID'];
        $oRole = $result['@oRole'];
    }

    /**********************************************
     * Function: GET_USER_INFO
     * Description: GIVEN USERID, RETURN USER INFO - Note: for future modification, do not include password in the output
     * Parameter(s):
     * $userID (in int) - user ID
     * Return value(s):
     * $result  (associative array) - return associative array of user info
     ***********************************************/
    function GET_USER_INFO($userID)
    {
        $this->getConn()->exec('USE' . DBHelper::$maindb);
        //r.name = role name
        $sth = $this->getConn()->prepare("SELECT `username`,`fullname`,`email`,r.`name` FROM `user` LEFT JOIN `role` AS r ON r.`roleID` = `user`.`roleID` WHERE `userID` = :userID LIMIT 1");
        $sth->bindParam(':userID',$userID,PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
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
        $this->getConn()->exec('USE' . DBHelper::$maindb);
        $sth = $this->getConn()->prepare("SELECT `collectionID`,`name`,`displayname` FROM `collection`");
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    /**********************************************
     * Function: GET_COLLECTION_TABLE
     * Description: GET COLLECTION TABLE
     * Parameter(s): NONE
     * Return value(s):
     * $result  (associative array) - return associative array of collection infos
     ***********************************************/
    function GET_COLLECTION_TABLE()
    {
        $this->getConn()->exec('USE' . DBHelper::$maindb);
        $sth = $this->getConn()->prepare("SELECT * FROM `collection`");
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**********************************************
     * Function: GET_USER_ROLE_FOR_DROPDOWN
     * Description: GET USERS ROLE INFO FOR DROPDOWN
     * Parameter(s): NONE
     * Return value(s):
     * $result  (associative array) - return associative array of role info
     ***********************************************/
    function GET_USER_ROLE_FOR_DROPDOWN()
    {
        $this->getConn()->exec('USE' . DBHelper::$maindb);
        $call = $this->getConn()->prepare("SELECT `roleID`,`name`, `description` FROM `bandocatdb`.`role`");
        $call->execute();

        $role = $call->fetchAll(PDO::FETCH_ASSOC);
        return $role;
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
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_TICKET_INSERT(?,?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iSubject, PDO::PARAM_STR, strlen($iSubject));
        $call->bindParam(2, $iPosterID, PDO::PARAM_INT);
        $call->bindParam(3, $iCollectionID, PDO::PARAM_INT);
        $call->bindParam(4, $iDescription, PDO::PARAM_STR, strlen($iDescription));
        /* EXECUTE STATEMENT */
        $call->execute();
        if ($call)
            return true;
        return false;
    }

    /**********************************************
     * Function: TICKET_UPDATE
     * Description: UPDATE TICKET ON ADMIN TICKET VIEW
     * Parameter(s):
     * $ticketID (in int) - ticket ID
     * $notes (in string) - $notes
     * $status (in int) - 0 = open, 1 = close
     * $solverID (in string) - user ID who submits
     * Return value(s): true if success, false if fail
     ***********************************************/
    function TICKET_UPDATE($ticketID,$notes,$status,$solverID)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        $sth = $this->getConn()->prepare("UPDATE `ticket` SET `notes` = :note, `status` = :stat,`solverID` = :uID,`solveddate` = NOW() WHERE `ticketID` = :id");
        if (!$sth)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $sth->bindParam(':id', $ticketID, PDO::PARAM_INT, 11);
        $sth->bindParam(':note', $notes, PDO::PARAM_STR);
        $sth->bindParam(':stat',$status,PDO::PARAM_INT);
        $sth->bindParam(':uID',$solverID,PDO::PARAM_INT);
        $ret =$sth->execute();
        return $ret;
    }


    /**********************************************
     * Under development.
     * Function:
     * Description:
     * Parameter(s):
     * $iAction (in string) - input, edit or ....
     * $iCollectionID (in int) - collection id of the document
     * $iDocID (in int) - document ID
     * $iUserID (in string) -  userID of user who performs the action
     ***********************************************/
    function SP_USER_INSERT($iUsername, $iPassword, $iFullname, $iEmail, $iRoleID, &$oMessage){
        $this->getConn()->exec('USE' . DBHelper::$maindb);
        /*PREPARE STATEMENT*/
        $call = $this->getConn()->prepare("CALL SP_USER_INSERT(?,?,?,?,?,@oMessage)");
        if (!$call)
            trigger_error("SQL failed: ". $this->getConn()->errorCode()."-".$this->conn->erorInfo()[0]);
        $iPassword = md5($iPassword);
        $call->bindParam(1, $iUsername, PDO::PARAM_STR, strlen($iUsername));
        $call->bindParam(2, $iPassword, PDO::PARAM_STR, strlen($iPassword));
        $call->bindParam(3, $iFullname, PDO::PARAM_STR, strlen($iFullname));
        $call->bindParam(4, $iEmail, PDO::PARAM_STR, strlen($iEmail));
        $call->bindParam(5, $iRoleID, PDO::PARAM_INT);

        /* EXECUTE STATEMENT */
        $call->execute();

        /* RETURN RESULT */
        $select = $this->getConn()->query('SELECT @oMessage');
        $result = $select->fetch(PDO::FETCH_ASSOC);
        $oMessage = $result['@oMessage'];
        if ($call){
            return true;
            return $oMessage;
        }
        return false;
        return $oMessage;
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
     * $iComment (in string) - comment
     * Return value(s): true if success, false if fail
     ***********************************************/
    function SP_LOG_WRITE($iAction, $iCollectionID, $iDocID, $iUserID, $iStatus,$iComment)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_LOG_WRITE(?,?,?,?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iAction, PDO::PARAM_STR, 10);
        $call->bindParam(2, $iCollectionID, PDO::PARAM_INT,11);
        $call->bindParam(3, $iDocID, PDO::PARAM_INT,11);
        $call->bindParam(4, $iUserID, PDO::PARAM_INT,11);
        $call->bindParam(5, $iStatus, PDO::PARAM_STR, 7);
        $call->bindParam(6, $iComment, PDO::PARAM_STR, 250);
        /* EXECUTE STATEMENT */
        $ret = $call->execute();
        return $ret;
    }
    /**********************************************
     * Function: SP_LOG_INSERT
     * Description: Slightly different version of SP_LOG_WRITE, allows to insert timestamp (use for log migration) and comments
     * Parameter(s):
     * $iAction (in string) - input, edit or ....
     * $iCollectionID (in int) - collection id of the document
     * $iDocID (in int) - document ID
     * $iUserID (in string) -  userID of user who performs the action
     * $iStatus (in string) - success or fail
     * $iTimestamp (in string) - timestamp
     * $iComments (in string) - comments (usually libraryindex if docID is missing)
     * Return value(s): true if success, false if fail
     ***********************************************/
    function SP_LOG_INSERT($iAction,$iCollectionID,$iDocID,$iUserID,$iStatus,$iTimestamp,$iComments)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_LOG_INSERT(?,?,?,?,?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iAction, PDO::PARAM_STR, 10);
        $call->bindParam(2, $iCollectionID, PDO::PARAM_INT,11);
        $call->bindParam(3, $iDocID, PDO::PARAM_INT,11);
        $call->bindParam(4, $iUserID, PDO::PARAM_INT,11);
        $call->bindParam(5, $iStatus, PDO::PARAM_STR, 7);
        $call->bindParam(6, $iTimestamp, PDO::PARAM_STR);
        $call->bindParam(7, $iComments, PDO::PARAM_STR,250);
        /* EXECUTE STATEMENT */
        $ret = $call->execute();
        return $ret;
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
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_ADMIN_TICKET_SELECT(?,@oSubject,@oSubmissionDate,@oSolvedDate,@oPoster,@oCollection,@oDescription,@oNotes,@oSolver,@oStatus)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iTicketID, PDO::PARAM_INT);
        /* EXECUTE STATEMENT */
        $call->execute();
        /* RETURN RESULT */
        $select = $this->getConn()->query('SELECT @oSubject AS Subject,@oSubmissionDate AS SubmissionDate,@oSolvedDate AS SolvedDate,@oPoster AS Submitter,@oCollection AS Collection,@oDescription AS Description,@oNotes AS Notes,@oSolver AS Solver,@oStatus AS Status');
        $result = $select->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**********************************************
     * Function:  GET_DOCUMENT_COUNT
     * Description: Count number of rows for the table specified by the Col parameter
     * Parameter(s):
     * collection (in string) - name of the collection
     * Return value(s):
     * $result (integer) - Number of rows as an integer
     ***********************************************/
    function GET_DOCUMENT_COUNT($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT COUNT(`documentID`) FROM `document`");
            $sth->execute();
            $result = $sth->fetchColumn();
            return $result;
        } else return false;
    }

    function GET_LOG_INFO($collection, $docID){
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        $dbID = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['CollectionID'];
        $logsth = $this->getConn()->prepare("SELECT `action`, `user`.`username`, `timestamp` FROM `log` LEFT JOIN `user` ON `user`.`userID`=`log`.`userID` WHERE `docID` = ? AND `collectionID` = ?");
        if (!$logsth)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $logsth->bindParam(1, $docID, PDO::PARAM_INT, 11);
        $logsth->bindParam(2, $dbID, PDO::PARAM_INT, 11);
        $logsth->execute();
        $logArray = $logsth->fetchAll();
        return $logArray;
    }


    /**********************************************
     * Function: GET_COLLECTION_TEMPLATE
     * Description: GIVEN collection name , return the collection's template data (dir, fulldir, name)
     * Parameter(s):
     * collection (in string) - name of the collection
     * Return value(s):
     * (assoc array) - return a template info in an associative array
     ***********************************************/
    function GET_COLLECTION_TEMPLATE($collection)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        $sth = $this->getConn()->prepare("SELECT * FROM `template` WHERE `template`.`templateID` = (SELECT `templateID` FROM `collection` WHERE `name` = ? LIMIT 1) LIMIT 1");
        $sth->bindParam(1,$collection, PDO::PARAM_STR,50);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**********************************************
     * Function: DELETE_DOCUMENT
     * Description: DELETE DOCUMENT GIVEN collection name and document ID
     * Parameter(s): $collection (in String) - Name of the collection
     *               $iDocID (in int) - document id
     * Return value(s):
     * $result  (array) - true if success, otherwise, false
     ***********************************************/
    function DELETE_DOCUMENT($collection,$iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);

            $sth = $this->getConn()->prepare("DELETE FROM `document` WHERE `documentID`=?");
            $sth->bindParam(1,$iDocID,PDO::PARAM_INT,11);
            $ret = $sth->execute();
            return $ret;
        }
        return false;
    }

    function GET_AUTHOR_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `authorname` FROM `author`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

    function GET_CUSTOMER_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `customername` FROM `customer`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

    function GET_COMPANY_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `companyname` FROM `company`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

    /**********************QUERIES FUNCTIONS************************/
    function GET_DOCUMENT_FILTEREDCOUNT($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT COUNT(`documentID`) FROM `document` WHERE `hascoast`='1'");
            $sth->execute();
            $result = $sth->fetchColumn();
            return $result;
        } else return false;
    }
    function GET_DOCUMENT_FILTEREDCOAST_COUNT($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT COUNT(`documentID`) FROM `document` WHERE `hascoast`='1'");
            $sth->execute();
            $result = $sth->fetchColumn();
            return $result;
        } else return false;
    }
    function GET_DOCUMENT_FILTEREDTITLE_COUNT($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT COUNT(`documentID`) FROM `document` WHERE `libraryindex` = `title`");
            $sth->execute();
            $result = $sth->fetchColumn();
            return $result;
        } else return false;
    }

    /**********************MAP FUNCTIONS************************/

    /**********************************************
     * Function: SP_TEMPLATE_MAP_DOCUMENT_SELECT
     * Description: GIVEN collection name & document ID, RETURN INFORMATION ABOUT Document
     * Parameter(s):
     * collection (in string) - name of the collection
     * $iDocID (in Integer) - document ID
     * Return value(s):
     * $result (assoc array) - return a document info in an associative array, or FALSE if failed
     ***********************************************/
    function SP_TEMPLATE_MAP_DOCUMENT_SELECT($collection, $iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_DOCUMENT_SELECT(?,@oLibraryIndex,@oTitle,@oSubtitle,@oIsMap,@oMapScale,@oHasNorthArrow,@oHasStreets,@oHasPOI,@oHasCoordinates,@oHasCoast,@oFileName,@oFileNameBack,@oNeedsReview,@oComments,@oCustomerName,@oStartDate,@oEndDate,@oFieldBookNumber,@oFieldBookPage,@oReadability,@oRectifiability,@oCompanyName,@oType,@oMedium,@oAuthorName,@oFileNamePath,@oFileNameBackPath)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iDocID, PDO::PARAM_INT, 11);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oLibraryIndex AS LibraryIndex,@oTitle AS Title,@oSubtitle AS Subtitle,@oIsMap AS IsMap,@oMapScale AS MapScale,@oHasNorthArrow AS HasNorthArrow,@oHasStreets AS HasStreets,@oHasPOI AS HasPOI,@oHasCoordinates AS HasCoordinates,@oHasCoast AS HasCoast,@oFileName AS FileName,@oFileNameBack AS FileNameBack,@oNeedsReview AS NeedsReview,@oComments AS Comments,@oCustomerName AS CustomerName,@oStartDate AS StartDate,@oEndDate AS EndDate,@oFieldBookNumber AS FieldBookNumber,@oFieldBookPage AS FieldBookPage,@oReadability AS Readability,@oRectifiability AS Rectifiability,@oCompanyName AS CompanyName,@oType AS Type,@oMedium AS Medium,@oAuthorName AS AuthorName,@oFileNamePath AS FileNamePath,@oFileNameBackPath AS FileNameBackPath');
            $result = $select->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else return false;
    }

    function SP_TEMPLATE_MAP_DOCUMENT_UPDATE($collection, $iDocID,
                                             $iLibraryIndex, $iTitle, $iSubtitle, $iIsMap, $iMapScale, $iHasNorthArrow,
                                             $iHasStreets, $iHasPOI, $iHasCoordinates, $iHasCoast, $iNeedsReview, $iComments,
                                             $iCustomerID, $iStartDate, $iEndDate, $iFieldBookNumber, $iFieldBookPage,
                                             $iReadability, $iRectifiability, $iCompanyID, $iType, $iMedium, $iAuthorID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_DOCUMENT_UPDATE(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iDocID, PDO::PARAM_INT);
            $call->bindParam(2, $iLibraryIndex, PDO::PARAM_STR);
            $call->bindParam(3, $iTitle, PDO::PARAM_STR);
            $call->bindParam(4, $iSubtitle, PDO::PARAM_STR);
            $call->bindParam(5, $iIsMap, PDO::PARAM_INT);
            $call->bindParam(6, $iMapScale, PDO::PARAM_STR);
            $call->bindParam(7, $iHasNorthArrow, PDO::PARAM_INT);
            $call->bindParam(8, $iHasStreets, PDO::PARAM_INT);
            $call->bindParam(9, $iHasPOI, PDO::PARAM_INT);
            $call->bindParam(10, $iHasCoordinates, PDO::PARAM_INT);
            $call->bindParam(11, $iHasCoast, PDO::PARAM_INT);
            $call->bindParam(12, $iNeedsReview, PDO::PARAM_INT);
            $call->bindParam(13, $iComments, PDO::PARAM_STR);
            $call->bindParam(14, $iCustomerID, PDO::PARAM_INT);
            $call->bindParam(15, $iStartDate, PDO::PARAM_STR);
            $call->bindParam(16, $iEndDate, PDO::PARAM_STR);
            $call->bindParam(17, $iFieldBookNumber, PDO::PARAM_INT);
            $call->bindParam(18, $iFieldBookPage, PDO::PARAM_STR);
            $call->bindParam(19, $iReadability, PDO::PARAM_STR);
            $call->bindParam(20, $iRectifiability, PDO::PARAM_STR);
            $call->bindParam(21, $iCompanyID, PDO::PARAM_INT);
            $call->bindParam(22, $iType, PDO::PARAM_STR);
            $call->bindParam(23, $iMedium, PDO::PARAM_INT);
            $call->bindParam(24, $iAuthorID, PDO::PARAM_INT);

            /* EXECUTE STATEMENT */
            return $call->execute();
        } else return false;
    }

    function SP_TEMPLATE_MAP_DOCUMENT_INSERT($collection,
                                             $iLibraryIndex, $iTitle, $iSubtitle, $iIsMap, $iMapScale, $iHasNorthArrow,
                                             $iHasStreets, $iHasPOI, $iHasCoordinates, $iHasCoast,$iFileName, $iFileNameBack,$iNeedsReview, $iComments,
                                             $iCustomerID, $iStartDate, $iEndDate, $iFieldBookNumber, $iFieldBookPage,
                                             $iReadability, $iRectifiability, $iCompanyID, $iType, $iMedium, $iAuthorID,$iFileNamePath,$iFileNameBackPath)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_DOCUMENT_INSERT(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iLibraryIndex, PDO::PARAM_STR);
            $call->bindParam(2, $iTitle, PDO::PARAM_STR);
            $call->bindParam(3, $iSubtitle, PDO::PARAM_STR);
            $call->bindParam(4, $iIsMap, PDO::PARAM_INT);
            $call->bindParam(5, $iMapScale, PDO::PARAM_STR);
            $call->bindParam(6, $iHasNorthArrow, PDO::PARAM_INT);
            $call->bindParam(7, $iHasStreets, PDO::PARAM_INT);
            $call->bindParam(8, $iHasPOI, PDO::PARAM_INT);
            $call->bindParam(9, $iHasCoordinates, PDO::PARAM_INT);
            $call->bindParam(10, $iHasCoast, PDO::PARAM_INT);
            $call->bindParam(11, $iFileName, PDO::PARAM_STR);
            $call->bindParam(12, $iFileNameBack, PDO::PARAM_STR);
            $call->bindParam(13, $iNeedsReview, PDO::PARAM_INT);
            $call->bindParam(14, $iComments, PDO::PARAM_STR);
            $call->bindParam(15, $iCustomerID, PDO::PARAM_INT);
            $call->bindParam(16, $iStartDate, PDO::PARAM_STR);
            $call->bindParam(17, $iEndDate, PDO::PARAM_STR);
            $call->bindParam(18, $iFieldBookNumber, PDO::PARAM_INT);
            $call->bindParam(19, $iFieldBookPage, PDO::PARAM_STR);
            $call->bindParam(20, $iReadability, PDO::PARAM_STR);
            $call->bindParam(21, $iRectifiability, PDO::PARAM_STR);
            $call->bindParam(22, $iCompanyID, PDO::PARAM_INT);
            $call->bindParam(23, $iType, PDO::PARAM_STR);
            $call->bindParam(24, $iMedium, PDO::PARAM_INT);
            $call->bindParam(25, $iAuthorID, PDO::PARAM_INT);
            $call->bindParam(26, $iFileNamePath, PDO::PARAM_STR);
            $call->bindParam(27, $iFileNameBackPath, PDO::PARAM_STR);

            /* EXECUTE STATEMENT */
            $ret = $call->execute();
            if($ret)
            {
                $select = $this->getConn()->query('SELECT LAST_INSERT_ID()');
                $ret = $select->fetch(PDO::FETCH_COLUMN);
                return $ret;
            }
            return 0;
        } else return false;
    }

    function SP_TEMPLATE_MAP_DOCUMENT_CHECK_EXIST_RECORD($collection, $iLibraryIndex)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_DOCUMENT_CHECK_EXIST_RECORD(?,@oReturnValue)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iLibraryIndex, PDO::PARAM_STR);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oReturnValue');
            $result = $select->fetch(PDO::FETCH_NUM);
            if($result[0] == 1)
                return "EXISTED";
            else if($result[0] == 0)
                return "GOOD";
        } else return false;
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
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `mediumname` FROM `documentmedium`");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }


    function SP_TEMPLATE_MAP_CUSTOMER_GET_ID_FROM_NAME_WITH_INSERT($collection, $iCustomerName, &$oCustomerID)
    {
        if ($iCustomerName == "") {
            $oCustomerID = 0;
            return $oCustomerID;
        }
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_CUSTOMER_GET_ID_FROM_NAME_WITH_INSERT(?,@oCustomerID)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iCustomerName, PDO::PARAM_STR, 100);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oCustomerID');
            $oCustomerID = $select->fetch(PDO::FETCH_ASSOC)['@oCustomerID'];
            return $oCustomerID;
        } else return false;
    }

    function SP_TEMPLATE_MAP_COMPANY_GET_ID_FROM_NAME_WITH_INSERT($collection, $iCompanyName, &$oCompanyID)
    {
        if ($iCompanyName == "") {
            $oCompanyID = 0;
            return $oCompanyID;
        }
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_COMPANY_GET_ID_FROM_NAME_WITH_INSERT(?,@oCompanyID)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iCompanyName, PDO::PARAM_STR, 100);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oCompanyID');
            $oCompanyID = $select->fetch(PDO::FETCH_ASSOC)['@oCompanyID'];
            return $oCompanyID;
        } else return false;
    }

    function SP_TEMPLATE_MAP_AUTHOR_GET_ID_FROM_NAME_WITH_INSERT($collection, $iAuthorName, &$oAuthorID)
    {
        if ($iAuthorName == "") {
            $oAuthorID = 0;
            return $oAuthorID;
        }
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_AUTHOR_GET_ID_FROM_NAME_WITH_INSERT(?,@oAuthorID)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iAuthorName, PDO::PARAM_STR, 100);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oAuthorID');
            $oAuthorID = $select->fetch(PDO::FETCH_ASSOC)['@oAuthorID'];
            return $oAuthorID;
        } else return false;
    }

    function SP_TEMPLATE_MAP_MEDIUM_GET_ID_FROM_NAME($collection, $iMediumName, &$oMediumID)
    {
        if ($iMediumName == "") {
            $oMediumID = "";
            return $oMediumID;
        }
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_MEDIUM_GET_ID_FROM_NAME(?,@oMediumID)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iMediumName, PDO::PARAM_STR, 20);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oMediumID');
            $oMediumID = $select->fetch(PDO::FETCH_ASSOC)['@oMediumID'];
            return $oMediumID;
        } else return false;
    }

    //******************************* STATISTICS FUNCTIONS ******************//


    /**********************************************
     * Function: SP_WEEKLYREPORT_INSERT
     * Description: INSERT INTO WEEKLYREPORT TABLE FROM COUNTING ENTRIES IN LOG TABLE
     * Parameter(s): $iYear (in int) - year
     *               $iWeek (in int) - week (1-52)
     *               $iCollectionID (in string) - collectionID
     * Return value(s): true if success, false if fail
     ***********************************************/
    function SP_WEEKLYREPORT_INSERT($iYear,$iWeek,$iCollectionID)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        $call = $this->getConn()->prepare("CALL SP_WEEKLYREPORT_INSERT(?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iYear, PDO::PARAM_INT);
        $call->bindParam(2, $iWeek, PDO::PARAM_INT);
        $call->bindParam(3, $iCollectionID,PDO::PARAM_INT);
        /* EXECUTE STATEMENT */
        $ret = $call->execute();
        return $ret;
    }

    function GET_WEEKLYREPORT($iYear,$iCollectionID)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        $sth = $this->getConn()->prepare('SELECT `weeklyreport`.`week`,`weeklyreport`.`count` FROM `weeklyreport` WHERE `weeklyreport`.`year` = ? AND `weeklyreport`.`collectionID` = ?');
        $sth->bindParam(1,$iYear,PDO::PARAM_INT);
        $sth->bindParam(2,$iCollectionID,PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    function GET_MONTHLYREPORT($iYear,$iCollectionID)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        $sth = $this->getConn()->prepare("SELECT 
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 1 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 2 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 3 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 4 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 5 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 6 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 7 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 8 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 9 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 10 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 11 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success'),
            (SELECT COUNT(*) FROM `log` WHERE MONTH(`log`.`timestamp`) = 12 AND YEAR(`log`.`timestamp`) = :y AND `log`.`collectionID`= :collection AND `log`.`action`='catalog' AND `log`.`status` = 'success')");
            $sth->bindParam(':y',$iYear,PDO::PARAM_INT);
            $sth->bindParam(':collection',$iCollectionID,PDO::PARAM_INT);

            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_NUM);
    }

    function SET_DOCUMENT_TRANSCRIBED($collection,$docID,$val)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        $sth = $this->getConn()->prepare("UPDATE `document` SET `transcribed` = :val WHERE `documentID` = :docID");
        $sth->bindParam(':val',$val,PDO::PARAM_INT,1);
        $sth->bindParam(":docID",$docID,PDO::PARAM_INT,11);
        $ret = $sth->execute();
        return $ret;
    }
}
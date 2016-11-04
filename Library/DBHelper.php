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
    function DBHelper()
    {
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
        $call = $this->getConn()->prepare("CALL SP_USER_AUTH(?,?,@oMessage,@oUserID,@oRole)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iUsername, PDO::PARAM_STR, 32);
        $call->bindParam(2, md5($iPassword), PDO::PARAM_STR, 64);

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
        $sth = $this->getConn()->prepare("SELECT `name`,`displayname` FROM `bandocatdb`.`collection`");
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**********************************************
     * Function: GET_USER_ROLE_FOR_DROPDOWN
     * Description: GET USERS ROLE INFO FOR DROPDOWN
     * Parameter(s): NONE
     * Return value(s):
     * $result  (associative array) - return associative array of collection info
     ***********************************************/
    function GET_USER_ROLE_FOR_DROPDOWN()
    {
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
        $call->bindParam(1, $iUsername, PDO::PARAM_STR, strlen($iUsername));
        $call->bindParam(2, md5($iPassword), PDO::PARAM_STR, strlen($iPassword));
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
     * Return value(s): true if success, false if fail
     ***********************************************/
    function SP_LOG_WRITE($iAction, $iCollectionID, $iDocID, $iUserID, $iStatus)
    {
        $this->getConn()->exec('USE ' . DBHelper::$maindb);
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_LOG_WRITE(?,?,?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iAction, PDO::PARAM_STR, 10);
        $call->bindParam(2, $iCollectionID, PDO::PARAM_INT,11);
        $call->bindParam(3, $iDocID, PDO::PARAM_INT,11);
        $call->bindParam(4, $iUserID, PDO::PARAM_INT,11);
        $call->bindParam(5, $iStatus, PDO::PARAM_STR, 7);
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
        $call->bindParam(1, htmlspecialchars($iTicketID), PDO::PARAM_INT, 11);
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
    function SP_TEMPLATE_MAP_DOCUMENT_SELECT($collection, $iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_MAP_DOCUMENT_SELECT(?,@oLibraryIndex,@oTitle,@oSubtitle,@oIsMap,@oMapScale,@oHasNorthArrow,@oHasStreets,@oHasPOI,@oHasCoordinates,@oHasCoast,@oFileName,@oFileNameBack,@oNeedsReview,@oComments,@oCustomerName,@oStartDate,@oEndDate,@oFieldBookNumber,@oFieldBookPage,@oReadability,@oRectifiability,@oCompanyName,@oType,@oMedium,@oAuthorName,@oFileNamePath,@oFileNameBackPath)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
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
            $call->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
            $call->bindParam(2, htmlspecialchars($iLibraryIndex), PDO::PARAM_STR, 40);
            $call->bindParam(3, htmlspecialchars($iTitle), PDO::PARAM_STR, 200);
            $call->bindParam(4, htmlspecialchars($iSubtitle), PDO::PARAM_STR, 300);
            $call->bindParam(5, htmlspecialchars($iIsMap), PDO::PARAM_INT, 1);
            $call->bindParam(6, htmlspecialchars($iMapScale), PDO::PARAM_STR, 1);
            $call->bindParam(7, htmlspecialchars($iHasNorthArrow), PDO::PARAM_INT, 1);
            $call->bindParam(8, htmlspecialchars($iHasStreets), PDO::PARAM_INT, 1);
            $call->bindParam(9, htmlspecialchars($iHasPOI), PDO::PARAM_INT, 1);
            $call->bindParam(10, htmlspecialchars($iHasCoordinates), PDO::PARAM_INT, 1);
            $call->bindParam(11, htmlspecialchars($iHasCoast), PDO::PARAM_INT, 1);
            $call->bindParam(12, htmlspecialchars($iNeedsReview), PDO::PARAM_INT, 1);
            $call->bindParam(13, htmlspecialchars($iComments), PDO::PARAM_LOB);
            $call->bindParam(14, htmlspecialchars($iCustomerID), PDO::PARAM_INT, 11);
            $call->bindParam(15, htmlspecialchars($iStartDate), PDO::PARAM_STR, 10);
            $call->bindParam(16, htmlspecialchars($iEndDate), PDO::PARAM_STR, 10);
            $call->bindParam(17, htmlspecialchars($iFieldBookNumber), PDO::PARAM_INT, 5);
            $call->bindParam(18, htmlspecialchars($iFieldBookPage), PDO::PARAM_STR, 10);
            $call->bindParam(19, htmlspecialchars($iReadability), PDO::PARAM_STR, 10);
            $call->bindParam(20, htmlspecialchars($iRectifiability), PDO::PARAM_STR, 10);
            $call->bindParam(21, htmlspecialchars($iCompanyID), PDO::PARAM_INT, 11);
            $call->bindParam(22, htmlspecialchars($iType), PDO::PARAM_STR, 200);
            $call->bindParam(23, htmlspecialchars($iMedium), PDO::PARAM_INT, 11);
            $call->bindParam(24, htmlspecialchars($iAuthorID), PDO::PARAM_INT, 11);

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
            $call->bindParam(1, htmlspecialchars($iLibraryIndex), PDO::PARAM_STR, 40);
            $call->bindParam(2, htmlspecialchars($iTitle), PDO::PARAM_STR, 200);
            $call->bindParam(3, htmlspecialchars($iSubtitle), PDO::PARAM_STR, 300);
            $call->bindParam(4, htmlspecialchars($iIsMap), PDO::PARAM_INT, 1);
            $call->bindParam(5, htmlspecialchars($iMapScale), PDO::PARAM_STR, 1);
            $call->bindParam(6, htmlspecialchars($iHasNorthArrow), PDO::PARAM_INT, 1);
            $call->bindParam(7, htmlspecialchars($iHasStreets), PDO::PARAM_INT, 1);
            $call->bindParam(8, htmlspecialchars($iHasPOI), PDO::PARAM_INT, 1);
            $call->bindParam(9, htmlspecialchars($iHasCoordinates), PDO::PARAM_INT, 1);
            $call->bindParam(10, htmlspecialchars($iHasCoast), PDO::PARAM_INT, 1);
            $call->bindParam(11, htmlspecialchars($iFileName), PDO::PARAM_STR, 50);
            $call->bindParam(12, htmlspecialchars($iFileNameBack), PDO::PARAM_STR, 50);
            $call->bindParam(13, htmlspecialchars($iNeedsReview), PDO::PARAM_INT, 1);
            $call->bindParam(14, htmlspecialchars($iComments), PDO::PARAM_LOB);
            $call->bindParam(15, htmlspecialchars($iCustomerID), PDO::PARAM_INT, 11);
            $call->bindParam(16, htmlspecialchars($iStartDate), PDO::PARAM_STR, 10);
            $call->bindParam(17, htmlspecialchars($iEndDate), PDO::PARAM_STR, 10);
            $call->bindParam(18, htmlspecialchars($iFieldBookNumber), PDO::PARAM_INT, 5);
            $call->bindParam(19, htmlspecialchars($iFieldBookPage), PDO::PARAM_STR, 10);
            $call->bindParam(20, htmlspecialchars($iReadability), PDO::PARAM_STR, 10);
            $call->bindParam(21, htmlspecialchars($iRectifiability), PDO::PARAM_STR, 10);
            $call->bindParam(22, htmlspecialchars($iCompanyID), PDO::PARAM_INT, 11);
            $call->bindParam(23, htmlspecialchars($iType), PDO::PARAM_STR, 200);
            $call->bindParam(24, htmlspecialchars($iMedium), PDO::PARAM_INT, 11);
            $call->bindParam(25, htmlspecialchars($iAuthorID), PDO::PARAM_INT, 11);
            $call->bindParam(26, htmlspecialchars($iFileNamePath), PDO::PARAM_STR, 200);
            $call->bindParam(27, htmlspecialchars($iFileNameBackPath), PDO::PARAM_STR, 200);

            /* EXECUTE STATEMENT */
            return $call->execute();
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
            $call->bindParam(1, htmlspecialchars($iLibraryIndex), PDO::PARAM_STR, 40);
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


}
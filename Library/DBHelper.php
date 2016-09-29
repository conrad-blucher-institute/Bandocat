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
     * &$oDisplayName (out string) - Collection Display Name
     * &$oDbName (out ref string) - db name
     * &$oStorageDir (out ref string) - Document storage dir
     * &$oPublicDir (out ref string) - Collection public dir (might not need)
     * &$oThumbnailDir (out ref string) - Thumbnail dir
     * &$oTemplateID (out ref int) - Collection template id (template table)
     * Return value(s):
     * $result (assoc array) - return above values in an assoc array
     ***********************************************/
    function SP_GET_COLLECTION_CONFIG($iName)
    {
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_GET_COLLECTION_CONFIG(?,@oDisplayName,@oDbName,@oStorageDir,oPublicDir,oThumbnailDir,oTemplateID)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iName, PDO::PARAM_STR,50);
        /* EXECUTE STATEMENT */
        $call->execute();
        /* RETURN RESULT */
        $select = $this->getConn()->query('SELECT @oDisplayName,@oDbName,@oStorageDir,@oPublicDir,@oThumbnailDir,@oTemplateID');
        $result = $select->fetch(PDO::FETCH_ASSOC);
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
        $sth = $this->getConn()->prepare("SELECT `ID`,`displayname` FROM bandocatdb.`collection`");
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}
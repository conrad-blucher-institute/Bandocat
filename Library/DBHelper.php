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

    //Getter and setters


    //Constructor
    function DBHelper()
    {
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
        if ($db == "") //empty parameter = default = bandocatdb
            $db = "bandocatdb";
        $host = "localhost";
        //$port = ":";
        $user = "root";
        $pwd = "notroot";

        $this->conn = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pwd);
        return 0;
    }

    function DB_CLOSE()
    {
        $this->setConn(null);
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
    function SP_USER_AUTH($iUsername, $iPassword, &$oMessage, &$oUserID, &$oRoleID)
    {
        /* PREPARE STATEMENT */
        $call = $this->getConn()->prepare("CALL SP_USER_AUTH(?,?,@oMessage,@oUserID,@oRoleID)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode()  . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, $iUsername, PDO::PARAM_STR,32);
        $call->bindParam(2, md5($iPassword), PDO::PARAM_STR,64);

        /* EXECUTE STATEMENT */
        $call->execute();

        /* RETURN RESULT */
        $select = $this->conn->query('SELECT @oMessage,@oUserID, @oRoleID');
        $result = $select->fetch(PDO::FETCH_ASSOC);
        $oMessage = $result['@oMessage'];
        $oUserID = $result['@oUserID'];
        $oRoleID = $result['@oRoleID'];
    }

}
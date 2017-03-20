<?php
/*******************************************
 * Removes all global session variables
 *******************************************/
    session_unset();
/*******************************************
 * Creates a new session
 *******************************************/
    session_start();
 /* Uses DBHelper.php */
    require("../../Library/DBHelper.php");

/*******************************************
 * The built in function htmlspecialchars()
 * converts some predefined characters to
 * HTML entities. I.E & = &amp; Receives
 * username and assigns it to variable
 * username.
 *******************************************/
    $username = htmlspecialchars($_POST["username"]);
    $pwd = $_POST["password"];
    if($username == "" || $pwd == "")
    {
        echo "Invalid";
        return -1;
    }
 /*create a new instance of DBHelper*/
/*******************************************
 * Create a new instance of DBHelper
 * DBHelper constructor attempts to establish
 * connection to the database by constructing
 * the connection string.
 *******************************************/
    $db = new DBHelper();
    $retval = $db->USER_AUTH($username,$pwd,$msg,$uID,$role);
/******************************************
 * Assuming the returned &msg parameter from
 * SP_USER_AUTH will dictate what case is
 * selected in the following switch statement
 * if the case is Successful we update the
 * current session with the user's information
 ******************************************/
    switch($msg)
    {
        case "Invalid":
            break;
        case "Inactive";
            break;
        case "Success":
            if($retval) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['userID'] = $uID;
                $_SESSION['start'] = time();
                $_SESSION['end'] = $_SESSION['start'] + (60 * 480); //session = 8 hours
            }
            break;
            default: break;
        }

    echo $msg;

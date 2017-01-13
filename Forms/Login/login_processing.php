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
 /*create a new instance of DBHelper*/
/*******************************************
 * Create a new instance of DBHelper
 * DBHelper constructor attempts to establish
 * connection to the database by constructing
 * the connection string.
 *******************************************/
    $db = new DBHelper();
    $db->SP_USER_AUTH($username,$pwd,$msg,$uID,$role);

    switch($msg)
    {
        case "Invalid":
            break;
        case "Inactive";
            break;
        case "Success":
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['userID'] = $uID;
            $_SESSION['start'] = time();
            $_SESSION['end'] = $_SESSION['start'] + (60*480); //session = 8 hours
            break;
            default: break;
        }

    echo $msg;

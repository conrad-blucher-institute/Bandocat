<?php
    session_unset();
    session_start();
    require("../../Library/DBHelper.php");
    $username = htmlspecialchars($_POST["username"]);
    $pwd = $_POST["password"];
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

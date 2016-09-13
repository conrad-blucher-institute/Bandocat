<?php
    session_start();
    require("../../Library/DBHelper.php");
    $username = $_POST["username"];
    $pwd = $_POST["password"];

    $db = new DBHelper();
    $db->DB_CONNECT("");
    $db->SP_USER_AUTH(htmlspecialchars($username),$pwd,$msg,$uID,$rID);

    if($rID == "" || $rID == null)
        ;//return "Inactive";
    else if ($rID == 0)
        ;//return "Invalid";
    else {
        $_SESSION['username'] = $uID;
        $_SESSION['role'] = $rID;
    }
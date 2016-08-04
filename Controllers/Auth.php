<?php
    require( dirname(__DIR__) . "/Library/DBHelper.php");
    $username = $_POST["name"];
    $pwd = $_POST["password"];

    $db = new DBHelper();
    $db->DB_CONNECT("");
    $db->SP_USER_AUTH(htmlspecialchars($username),$pwd,$msg,$uID,$rID);


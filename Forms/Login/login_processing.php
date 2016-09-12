<?php
    require("../../Library/DBHelper.php");
    $username = $_POST["username"];
    $pwd = $_POST["password"];

    $db = new DBHelper();
    $db->DB_CONNECT("");
    $db->SP_USER_AUTH(htmlspecialchars($username),$pwd,$msg,$uID,$rID);


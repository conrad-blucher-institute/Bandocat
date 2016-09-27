<?php
include 'Library/SessionManager.php';
$session = new SessionManager();
if($session->getLoggedIn() == true)
    header('Location: Forms/Main/');
else header('Location: Forms/Login/');
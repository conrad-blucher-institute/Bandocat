<?php
//for admin to view ticket and update ticket status
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['id'])) {
    $tID = $_GET['id']; //ticket ID
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
}
else header('Location: ../../');
$notes = $_POST['txtNotes'];
$status = $_POST['Status'];

$ret = $DB->TICKET_UPDATE($tID,$notes,$status,$session->getUserID());
if($ret != false)
    echo "Update successfully.";
else echo "Update failed";
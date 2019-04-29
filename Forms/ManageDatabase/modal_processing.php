<?php
/**
 * Created by PhpStorm.
 * User: rortiz18
 * Date: 4/15/2019
 * Time: 1:32 PM
 */
require_once('../../Library/DBHelper.php');

if(isset($_POST["delete"]) && isset($_POST["columnID"]) && isset($_POST["tblname"]) && isset($_POST["idNumber"]) && isset($_POST["dbname"]))
{
    $DB = new DBHelper();
    $columnID = $_POST["columnID"];
    $tblname = $_POST["tblname"];
    $idNumber = $_POST["idNumber"];
    $dbname = $_POST["dbname"];

    $deleteContent = $DB->MODAL_DELETE_ROW($tblname, $columnID, $idNumber, $dbname);
    echo $deleteContent[0];
}
else header('Location: ../../');
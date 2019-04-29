<?php
/**
 * Created by PhpStorm.
 * User: rortiz18
 * Date: 4/10/2019
 * Time: 4:26 PM
 */
require_once('../../Library/DBHelper.php');

if(isset($_POST["dbname"]))
{
    $DB = new DBHelper();
    $dbname = $_POST["dbname"];

    $collectionName = $DB->MODAL_LINK_PATH($dbname);
    echo $collectionName[0];
}
else header('Location: ../../');

<?php
/**
 * Created by PhpStorm.
 * User: rortiz18
 * Date: 4/1/2019
 * Time: 4:07 PM
 */
require_once "../../Library/DBHelper.php";

if(isset($_POST["dbname"]))
{
    $DB = new DBHelper();
    $dbname = $_POST["dbname"];

    // Call function
    $tables = $DB->SHOW_TABLES($dbname);
    $HTML = "";
}
else
{

}
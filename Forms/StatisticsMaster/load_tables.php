<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 11/6/2018
 * Time: 4:26 PM
 */
require("../../Library/DBHelper.php");
require("C:/xampp/htdocs/TonyAmos/Classes/TonyDBHelper.php");
$DB = new DBHelper();
$TDB = new TonyDBHelper();

if(isset($_POST["bandocat"]))
{
    $data = $DB->GET_STORAGE_STATISTICS();
    echo json_encode($data);
}

else if(isset($_POST["tonyamos"]))
{
    $dbName = "tonyamosdb";
    $data = array();
    $sand = $TDB->COUNT_OBSERVED_AMOUNT("SAND", $dbName);
    $rgb = $TDB->COUNT_OBSERVED_AMOUNT("RBG", $dbName);
    $lgul = $TDB->COUNT_OBSERVED_AMOUNT("LGUL", $dbName);
    $hgul = $TDB->COUNT_OBSERVED_AMOUNT("HGUL", $dbName);
    $total = $TDB->SUM_OBSERVED_AMOUNT($dbName);

    array_push($data, $sand);
    array_push($data, $rgb);
    array_push($data, $lgul);
    array_push($data, $hgul);
    array_push($data, $total);

    echo json_encode($data);
}

else
{
    echo "Something went wrong.";
}
?>
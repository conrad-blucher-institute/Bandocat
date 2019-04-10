<?php
/**
 * Created by PhpStorm.
 * User: rortiz18
 * Date: 3/28/2019
 * Time: 4:35 PM
 */
require('../../Library/DBHelper.php');
$DB = new DBHelper();

if(isset($_POST["dbname"]) && isset($_POST["tblname"]))
{
    $dbname = $_POST["dbname"];
    $tblname = $_POST["tblname"];
    $object = array();
    $data = $DB->DATABASE_MANAGER($dbname, $tblname);

    // Obtaining data and pushing to columns array
    $temp = array_keys($data[0]);
    $columns = [];
    foreach($temp as $value)
    {
        $object = array(
            "data" => $value,
            "title" => $value,
        );

        array_push($columns, $object);
    }


    // Manually pushing Delete to the last column
    /*$object = array(
        "data" => 'Delete',
        "title" => "Delete"
    );
    array_push($columns, $object);*/


    echo json_encode(array(
        "data" => $data,
        "columns" => $columns
    ));
}
else header('Location: ../../');




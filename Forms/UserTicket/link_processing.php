<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 2/26/2019
 * Time: 3:04 PM
 */
require('../../Library/DBHelper.php');
$DB = new DBHelper();

// Check for the data being posted
if(isset($_POST["libraryIndex"]) && isset($_POST["collection"]) && isset($_POST["templateID"]))
{
    // Library index might have a comma and an extra library index, lets clean it up
    $libraryIndex = substr($_POST["libraryIndex"], 0, strpos($_POST["libraryIndex"], ','));
    $colelction = $_POST["collection"];

    // If the string becomes empty, then there were not more than library index
    if($libraryIndex == "")
    {
        $libraryIndex = $_POST["libraryIndex"];
    }

    // Getting the documents id
    $id = $DB->FIND_DOCUMENT_BY_LIBRARY_INDEX($libraryIndex, $colelction);
    $response = array(
        "id" => $id,
        "libraryIndex" => $libraryIndex,
        "collection" => $colelction,
        "templateID" => $_POST["templateID"]
    );
    echo json_encode($response);
}
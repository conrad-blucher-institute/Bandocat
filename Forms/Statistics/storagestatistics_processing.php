<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 12/28/2018
 * Time: 1:52 PM
 */

// Getting DB Helper
require "../../Library/DBHelper.php";
$DB = new DBHelper();

$data = $DB->GET_STORAGE_STATISTICS();
echo json_encode(array("data" => $data));
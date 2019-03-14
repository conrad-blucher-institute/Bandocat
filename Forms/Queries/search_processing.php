<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 10/19/2018
 * Time: 9:39 AM
 */
require '../../Library/DBHelper.php';
require '../../Library/MapDBHelper.php';
require '../../Library/DateHelper.php';
require '../../Library/ControlsRender.php';
include '../../Library/SessionManager.php';

$session = new SessionManager();

if(isset($_POST) && isset($_GET["col"]))
{
    $where = "";
    $collection = $_GET["col"];
    $Render = new ControlsRender();
    $DB = new MapDBHelper();
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);

    // We are looping through each value, the keys tell us what column we need to go to
    // The keys are the id's from the form
    foreach($_POST as $key => $value)
    {
        /*echo "Key $key and value $value<br>";*/

        // Switch statement to go through all elements in the $_POST array
        if(isset($value) && $value !== "")
        {
            switch($key)
            {
                case "docid":
                    $where .= "`documentID` = $value AND ";
                    break;

                case "libindex":
                    $where .= "`libraryindex` = \"$value\" AND ";
                    break;

                case "title":
                    $where .= "`title` = \"$value\" AND ";
                    break;

                case "subtitle":
                    $where .= "`subtitle` = \"$value\" AND ";
                    break;

                case "mapscale":
                    $where .= "`mapscale` = \"$value\" AND ";
                    break;

                case "comments":
                    $where .= "`comments` = \"$value\" AND ";
                    break;

                case "customer":
                    $where .= "c.`customername` = \"$value\" AND ";
                    break;

                case "author":
                    $where .= "a.`authorname` = \"$value\" AND ";
                    break;

                case "hasnortharrow":
                    $where .= "`hasnortharrow` = $value AND ";
                    break;

                case "has_streets":
                    $where .= "`hasstreets` = $value AND ";
                    break;

                case "poi":
                    $where .= "`hasPOI` = $value AND ";
                    break;

                case "coordinates":
                    $where .= "`hascoordinates` = $value AND ";
                    break;

                case "coast":
                    $where .= "`hascoast` = $value AND ";
                    break;

                case "review":
                    $where .= "`needsreview` = $value AND ";
                    break;

                case "medium":
                    $where .= "d.`mediumID` = $value AND ";
                    break;

                case "geoFront":
                    $where .= "d.`geoRecFrontStatus` = $value AND ";
                    break;

                case "geoBack":
                    $where .= "d.`geoRecBackStatus` = $value AND ";
                    break;

                case "rect":
                    $where .= "d.`rectifiability` = \"$value\" AND ";
                    break;

                case "read":
                    $where .= "d.`readability` = \"$value\" AND ";
                    break;

                case "company":
                    $where .= "cm.`companyID` = $value AND ";
                    break;

                case "ismap":
                    $where .= "d.`ismap` = $value AND ";
                    break;

                default:
                    break;
            }
        }
    }

    $where = substr($where, 0, -4);
    /*echo "$where<br><br>";*/

    $data = $DB->SEARCH_COLLECTION($where, $config["DbName"]);
    echo json_encode(array('data' => $data));
}

else
{
    echo "Something went wrong.";
}
?>
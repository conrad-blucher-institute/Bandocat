<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();

if(isset($_GET['col']) && isset($_GET['action'])) {
    require('../../Library/DBHelper.php');
    //Get passed variables, use htmlspecialchars to verify col
    $collection = htmlspecialchars($_GET['col']);
    $action = $_GET['action'];
    //create new instance of DBHelper
    $DB = new DBHelper();
    //Selects appropriate database, and counts the number of columns to return an accurate count.
    $count = $DB->GET_DOCUMENT_COUNT($collection);
    //Switch statement for "action" parameter to determine which document we are counting
}
else header('Location: ../../');
        switch ($action)
        {
            case 'Coast':
                $countfilter = $DB->GET_DOCUMENT_FILTEREDCOAST_COUNT($collection);
                $pagestat = "that Have Coasts,";
                break;
            case 'Title':
                $countfilter = $DB->GET_DOCUMENT_FILTEREDTITLE_COUNT($collection);
                $pagestat = "Without Titles,";
                break;
            default:
                break;
        }
        //calculates the percentage of the total
        $percentage = round((($countfilter/$count)*100),2);
echo "There are $countfilter Maps $pagestat out of a Total $count ($percentage%)";
?>
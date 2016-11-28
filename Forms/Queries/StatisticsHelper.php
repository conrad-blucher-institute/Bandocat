<?php
$array_db_value = ["","greenmaps","bluchermaps"];
if(isset($_GET['q'])) {
    $queryvar = $_GET['q'];
    $queryinspection = substr($queryvar, -5);
    $querycollection= substr($queryvar,0,-6);
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
//HMM
    if($querycollection == 'bluchermaps'){
        $collection= $array_db_value[2];
        $collstat = "Blucher Maps Collection";
        if($queryinspection == 'Coast'){
            $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
            $count = $DB->GET_DOCUMENT_COUNT($collection);
            $countfilter =$DB->GET_DOCUMENT_FILTEREDCOAST_COUNT($collection);
            $pagestat ="that Have Coasts,";
        }
        elseif ($queryinspection == 'Title'){
            $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
            $count = $DB->GET_DOCUMENT_COUNT($collection);
            $countfilter =$DB->GET_DOCUMENT_FILTEREDTITLE_COUNT($collection);
            $pagestat ="Without Titles,";
        }
    }
    if($querycollection == 'greenmaps'){
        $collection= $array_db_value[1];
        $collstat = "Green Maps Collection";
        if($queryinspection == 'Coast'){
            $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
            $count = $DB->GET_DOCUMENT_COUNT($collection);
            $countfilter =$DB->GET_DOCUMENT_FILTEREDCOAST_COUNT($collection);
            $pagestat ="that Have Coasts,";
        }
        elseif ($queryinspection == 'Title'){
            $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
            $count = $DB->GET_DOCUMENT_COUNT($collection);
            $countfilter =$DB->GET_DOCUMENT_FILTEREDTITLE_COUNT($collection);
            $pagestat ="Without Titles,";
        }
    }
    $percentage = round((($countfilter/$count)*100),2);
}
echo "There are $countfilter Maps $pagestat out of a Total $count ($percentage%)";
?>
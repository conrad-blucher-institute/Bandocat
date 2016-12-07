<?php
    require '../../Library/DBHelper.php';
    $DB = new DBHelper();
    $collections = $DB->GET_COLLECTION_FOR_DROPDOWN();
    $count = array();
    foreach($collections as $col)
    {
        if($col['name'] == "bluchermaps" || $col['name'] == "greenmaps" || $col['name'] == "mapindices") //for testing purpose
            array_push($count,array( "collection" => $col['displayname'],"count" => $DB->GET_DOCUMENT_COUNT($col['name'])));
    }
    echo json_encode($count);

?>
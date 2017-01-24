<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
require '../../Library/DBHelper.php';
$DB = new DBHelper();

if(isset($_POST['submit'])){
    if(!empty($_POST['Collection'])) {
        echo "<span>You have selected :</span><br/>";

        //As output of $_POST['Color'] is an array we have to use foreach Loop to display individual value
        foreach ($_POST['Collection'] as $select)
        {
            echo $select;

        }
        $config = $DB->SP_GET_COLLECTION_CONFIG($select);

        if($select == "bluchermaps"){
            $name = "Blucher Maps";
            $dbname = "bandocat_bluchermapsinventory";
            $tblname = "document";
            $indexfield = "libraryindex";
            $titlefield = "title";
        }
    }
    else { echo "Please Select Atleast One Collection.";}
}
?>
<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
require '../../Library/DBHelper.php';
$DB = new DBHelper();

if(isset($_POST['submit'])){
    if(!empty($_POST['Collection'])) {

        //As output of $_POST['Collection'] is an array we have to use foreach Loop to generate a CSV for each Selected Collection
        foreach ($_POST['Collection'] as $collection)
        {
            //Set variables based on the Collection
            $name = "";
            $dbname = "";
            $tblname = "";
            $indexfield = "";
            $titlefield = "";
            switch($collection)
            {
                case "bluchermaps":
                    $name = "Blucher Maps";
                    $dbname = "bandocat_bluchermapsinventory";
                    $tblname = "document";
                    $indexfield = "libraryindex";
                    $titlefield = "title";
                    break;
                case "greenmaps":
                    $name = "Green Maps";
                    $dbname = "bandocat_greenmapsinventory";
                    $tblname = "document";
                    $indexfield = "libraryindex";
                    $titlefield = "title";
                    break;
                case "jobfolder":
                    $name = "Job Folder";
                    $dbname = "bandocat_jobfolderinventory";
                    $tblname = "mapinformation";
                    $indexfield = "library_index";
                    $titlefield = "title";
                    break;
                case "mapindices":
                    $name = "Map Indices";
                    $dbname = "bandocat_indiceslinventory";
                    $tblname = "mapinformation";
                    $indexfield = "library_index";
                    $titlefield = "title";
                    break;
                case "blucherfieldbook":
                    $name = "Blucher Field Book";
                    $dbname = "bandocat_fieldbookinventory";
                    $tblname = "mapinformation";
                    $indexfield = "library_index";
                    $titlefield = "title";
                    break;

                default: break;
            }

            //Retrieve the Index and Document Title for the selected collections
            $list = $DB->GET_DOCUMENT_LISTS($collection);

            $juan = json_encode($list);
            //print_r($juan);

            // Create CSV Filename based on the Collection
            $filename = $collection . "_" . date('Ymd') . ".csv";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");

            $out = fopen("php://output", 'w');
            fwrite($out, "sep=\t".PHP_EOL);
            $delimiter = "\t";
            $current = "";

            fputcsv($out, array("Library Index"=>strtoupper($name) . " COLLECTION","Document Title" => ""),$delimiter);
            foreach ($list as $line)
            {
                //fputcsv($out,$line,'\t');
               // fputcsv($out, array("Library Index"=>strtoupper($name) . " COLLECTION","Document Title" => ""),$delimiter);
                $temp = $line['libraryindex'];
                $folder = explode('-',$temp)[0];
                if(strcmp($folder,$current) != 0)
                {
                    $current = $folder;
                    fputcsv($out, array("Library Index"=>"","Document Title" => ""),$delimiter);
                    fputcsv($out, array("Library Index"=>"","Document Title" => ""),$delimiter);
                    fputcsv($out, array("Library Index"=>"Library Index","Document Title" => "Document Title"),$delimiter);
                }

                $title = $line['title'];
                if(strpos($line['title'],'"') >= 0) ;
                else $title = '="' . $line['title'] . '"';
                fputcsv($out, array("Library Index"=>'="' . $line['libraryindex'] . '"',"Document Title" => $title),$delimiter);
            }

            fclose($out);

        }
    }
    else { echo "Please Select a Collection.";}
}
?>
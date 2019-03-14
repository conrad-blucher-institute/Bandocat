<?php
//check for session
require '../../Library/SessionManager.php';
$session = new SessionManager();
//prevent accessing directly
if(!isset($_POST))
    header('Location: index_old.php');
require '../../Library/DBHelper.php';
require '../../Library/FieldBookDBHelper.php';
$DB = new FieldBookDBHelper();
$rets = array();
//get collection name
$config = $DB->SP_GET_COLLECTION_CONFIG($_GET['col']);
//Create Thumbnails dir if it doesn't exist
if(!is_dir("../../Thumbnails"))
    exec(mkdir("../../Thumbnails", 0777));

foreach($_FILES as $f)
{
    //Check to see if uploaded file already exists in the DB using filename and db name;
    $ret = $DB->TEMPLATE_FIELDBOOK_CHECK_EXIST_RECORD_BY_FILENAME($_GET['col'],$f['name']);
    //If it does not exist
    if($ret == 0)
    {
        //sample upload 0164-188 explode grabs 0164
        $folder = explode("-", $f['name'])[0];
        //set filename
        $filename = $f['name'];
        //concat filepath
        $filenamepath = $folder . "/" . $filename;
        //change thumbnail from tif to jpg
        $thumbnail = str_replace(".tif", ".jpg", $filename);
        //create fullpath
        $fullpath = $config['StorageDir'] . $folder . "/" . $filename;
        //check if the directory exists
        if (!is_dir($config['StorageDir'] . $folder))
            mkdir($config['StorageDir'] . $folder, 0777);
        //check if the file already exists
        if(file_exists($fullpath))
        {
            chmod($fullpath, 0755); //Change the file permissions if allowed
            unlink($fullpath); //remove the file
        }
        //This function checks to ensure that the file
        // designated by filename is a valid upload file
        // (meaning that it was uploaded via PHP's HTTP POST upload mechanism).
        // If the file is valid, it will be moved to the filename given by destination.
        move_uploaded_file($f["tmp_name"], $fullpath);
        //Thumbnail directory
        $thumbFilenamepath = "../../" . $config['ThumbnailDir'];
        //check if thumbnail dir exists
        if (!is_dir($thumbFilenamepath))
            mkdir($thumbFilenamepath, 0777);
        //remove thumbnail if it exists
        if (file_exists($thumbFilenamepath . $thumbnail))
        {
            chmod($thumbFilenamepath . $thumbnail, 0755); //Change the file permissions if allowed
            unlink($thumbFilenamepath . $thumbnail); //remove the file
        }
        //executes a php function to convert the image supplied to the supplied jpg
        //also trims the jpg for thumbnail viewing
        $exec1 = "convert " . $fullpath . " -deskew 40% -fuzz 50% -trim -resize 200 ./" . $thumbFilenamepath . $thumbnail;
        exec($exec1, $yaks1);

        $ret = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_INSERT($_GET['col'],str_replace(".tif","",$f['name']),(int)$folder,$filenamepath,$filename,$thumbnail);
        //error checking for image
        if($ret != false)
            array_push($rets,array($f['name'],"Uploaded"));
        else array_push($rets,array($f['name'],"Error"));
    }
    else array_push($rets,array($f['name'],"Ignored"));
}

echo json_encode($rets);


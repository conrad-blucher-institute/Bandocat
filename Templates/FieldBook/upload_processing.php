<?php
//check for session
require '../../Library/SessionManager.php';
$session = new SessionManager();
//prevent accessing directly
if(!isset($_POST))
    header('Location: index.php');
require '../../Library/DBHelper.php';
require '../../Library/FieldBookDBHelper.php';
$DB = new FieldBookDBHelper();
$rets = array();
$config = $DB->SP_GET_COLLECTION_CONFIG($_GET['col']);
foreach($_FILES as $f)
{
    $ret = $DB->TEMPLATE_FIELDBOOK_CHECK_EXIST_RECORD_BY_FILENAME($_GET['col'],$f['name']);
    if($ret == 0) {
        //if doc not existed
        $folder = explode("-", $f['name'])[0];
        $filename = $f['name'];
        $filenamepath = $folder . "/" . $filename;
        $thumbnail = str_replace(".tif", ".jpg", $filename);
        $fullpath = $config['StorageDir'] . $folder . "/" . $filename;
        if (!is_dir($config['StorageDir'] . $folder))
            mkdir($config['StorageDir'] . $folder, 0777);
        if(file_exists($fullpath)) {
            chmod($fullpath, 0755); //Change the file permissions if allowed
            unlink($fullpath); //remove the file
        }
        move_uploaded_file($f["tmp_name"], $fullpath);

        $thumbFilenamepath = "../../" . $config['ThumbnailDir'];
        if (!is_dir($thumbFilenamepath))
            mkdir($thumbFilenamepath, 0777);
        if (file_exists($thumbFilenamepath . $thumbnail))
        {
            chmod($thumbFilenamepath . $thumbnail, 0755); //Change the file permissions if allowed
            unlink($thumbFilenamepath . $thumbnail); //remove the file
        }
        $exec1 = "convert " . $fullpath . " -deskew 40% -fuzz 50% -trim -resize 200 ./" . $thumbFilenamepath . $thumbnail;
        exec($exec1, $yaks1);

        $ret = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_INSERT($_GET['col'],str_replace(".tif","",$f['name']),(int)$folder,$filenamepath,$filename,$thumbnail);
        if($ret != false)
            array_push($rets,array($f['name'],"Uploaded"));
        else array_push($rets,array($f['name'],"Error"));
    }
    else array_push($rets,array($f['name'],"Ignored"));
}

echo json_encode($rets);


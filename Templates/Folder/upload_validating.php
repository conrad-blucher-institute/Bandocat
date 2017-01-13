<?php
require '../../Library/SessionManager.php';
require '../../Library/DBHelper.php';
require '../../Library/FolderDBHelper.php';
$session = new SessionManager();
$DB = new FolderDBHelper();
$fileNames = $_POST['fileNames'];
$ret = array();
foreach($fileNames as $f) {
    $retval = $DB->TEMPLATE_FOLDER_CHECK_EXIST_RECORD_BY_FILENAME($_GET['col'], $f);
    if(strpos($f,"back") !== false && $retval == "0") //back
    {
        $hasFront = false;
        foreach($fileNames as $f2)
        {
            if(strpos($f,str_replace(".tif","",$f2)) !== false && strpos($f2,"back") === false ) //if no front, return 2
            {
                $hasFront = true;
                break;
            }
        }
        if($hasFront == false)
            $retval = "2";
    }
    array_push($ret, $retval);
}
echo json_encode($ret);

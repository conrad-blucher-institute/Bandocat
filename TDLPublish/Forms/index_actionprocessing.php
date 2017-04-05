<?php
//This performs server side action when user select an action on Action column in index.php
//This performs server side action when user select an action on Action column in index.php
require_once '../../Library/SessionManager.php';
require_once '../../Library/DBHelper.php';
require_once '../../Library/DBHelper.php';
require_once '../../Library/MapDBHelper.php';
require_once '../../Library/FolderDBHelper.php';
require_once '../../Library/FieldBookDBHelper.php';
require_once '../../Library/IndicesDBHelper.php';

require_once '../../Library/TDLPublishDB.php';
require_once '../../Library/TDLSchema.php';
require_once '../../Library/TDLPublishJob.php';
date_default_timezone_set("America/Chicago");
$session = new SessionManager();
$Schema = new TDLSchema();
$TDL = new TDLPublishJob();
$DB = new DBHelper();
$collectionName = $_POST['ddlCollection'];
$collection = $DB->GET_COLLECTION_INFO($collectionName);
$docID = $_POST['docID'];
switch($collection["templateID"])
{
    case 1: //map template
        $DB = new MapDBHelper();
        $doc = $DB->SP_TEMPLATE_MAP_DOCUMENT_SELECT($collectionName,$docID);
        break;
    case 2: //jobfolder template
        $DB = new FolderDBHelper();
        $doc = $DB->SP_TEMPLATE_FOLDER_DOCUMENT_SELECT($collectionName,$docID);
        break;
    case 3: //fieldbook template
        $DB = new FieldBookDBHelper();
        $doc = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT($collectionName,$docID);
        break;
    case 4: //indices template
        $DB = new IndicesDBHelper();
        $doc = $DB->SP_TEMPLATE_INDICES_DOCUMENT_SELECT($collectionName,$docID);
        break;
    default:
        $doc = null;
        echo "Error initializing class!";
        return; //error
}
$doc["Collection"] = $collection["TDLname"];
$doc += $collection;

$DB->SWITCH_DB($collectionName);
//GET ITEM INFO
$dspaceDocInfo = $DB->PUBLISHING_DOCUMENT_GET_DSPACE_INFO($docID);
$dspaceID = $dspaceDocInfo['dspaceID'];

switch($_POST['action'])
{
    case "push":
        $ret = $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,2); //push to queue;
        break;
    case "pop":
        $ret = $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,0);
        break;
    case "unpublish":
        //FIND ALL BITSTREAMS, DELETE ALL BITSTREAMS BELONG TO THIS ITEM
        $bitstreams = json_decode($TDL->TDL_CUSTOM_GET("items/" . $dspaceID . "/bitstreams"),true);
        foreach($bitstreams as $b)
        {
            $ret = $TDL->TDL_CUSTOM_DELETE("bitstreams/" . $b["id"]);
            if($ret != "200") {
                echo "Deleting bitstreams, return value: " . $ret . "\n";
                return;
            }
        }
        //DELETE ITEM
        $ret = $TDL->TDL_CUSTOM_DELETE("items/" . $dspaceID);

        //reset status flag
        //write log to DB
        $ret = $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,0);
        //reset
        if($ret) $ret = $DB->PUBLISHING_DOCUMENT_TDL_UPDATE($docID,0,null);
        break;
    case "update":
        $convertedSchema = $Schema->convertSchema($collection['templateID'],$doc,false); //convert from BandoCat to TDL schema using method in TDLSchema
        //save the converted schema in a temporary json file
        $jsonfilename = __DIR__ . '\\' . $docID . '.json';
        $fp = fopen($jsonfilename, 'w');
        fwrite($fp, $convertedSchema);
        fclose($fp);
        $ret = $TDL->TDL_CUSTOM_PUT("items/" . $dspaceID . "/metadata",$jsonfilename,"application/json",null);
        unlink($jsonfilename);
        break;
    default:
        echo "Error! Unclassified action!";
        return;
}
    if($ret)
        $ret = $DB->SP_LOG_WRITE($_POST['action'] . " (tdl)",$collection["collectionID"],$docID,$session->getUserID(),"success","");

    if($ret)
        echo "Success!";
    else echo "Error!";
    return;
?>
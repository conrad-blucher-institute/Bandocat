<?php
require_once __DIR__ . '\..\..\Library\SessionManager.php';
require_once __DIR__ . '\..\..\Library\DBHelper.php';
require_once __DIR__ . '\..\..\Library\DBHelper.php';
require_once __DIR__ . '\..\..\Library\MapDBHelper.php';
require_once __DIR__ . '\..\..\Library\FolderDBHelper.php';
require_once __DIR__ . '\..\..\Library\FieldBookDBHelper.php';
require_once __DIR__ . '\..\..\Library\IndicesDBHelper.php';

require_once __DIR__ . '\..\..\Library\TDLPublishDB.php';
require_once __DIR__ . '\..\..\Library\TDLSchema.php';
require_once __DIR__ . '\..\..\Library\TDLPublishJob.php';
    /*
     * TDL Status:
     * -1: Error
     * 0 : not published
     * 1 : published
     * 2 : In Queue
     *10: publishing front
     * 11 publishing back
     */

    //debug flag
    $debug = true;
    if($debug)
        echo "DEBUG MODE IS ON\n";
    else echo "DEBUG MODE IS OFF\n";

    //prepare
    $DB = new DBHelper();
    $DS = new TDLPublishJob();
    $Schema = new TDLSchema();

    //create a log file if not exists
    $logfilename = "log.txt";
    if(!file_exists($logfilename))
        $logfile = fopen($logfilename,"w"); //create log file if not exists (write)
    else $logfile = fopen($logfilename,"a"); //open log file and append to it if file already exists

    fwrite($logfile,date(DATE_RFC2822) . ": " . "Job Started.\r\n");

    echo "TDL Publishing job\nLast updated: 04/14/2017\nBy Son Nguyen\n";
    echo "TDL Publishing job is ready....\n\n...\n...\n";

//*********************************************************************************************************************
    //Current collection: Blucher Maps (bluchermaps), ID = 1,template Map (use MapDBHelper)
    $collectionName = "bluchermaps";//change the variable if publishing to new collection
//*********************************************************************************************************************
    fwrite($logfile,date(DATE_RFC2822) . ": Targeted collection: " . $collectionName . "\r\n");
    //Dspace Community and collection info:
    $collection = $DB->GET_COLLECTION_INFO($collectionName);

    $TDLCommunityID = $collection["TDLcommunityID"];
    $TDLCollectionID = $collection["TDLcollectionID"];

    $isEmptyQueue = false;
    while(!$isEmptyQueue) {
        //use this database
        $DB->SWITCH_DB($collectionName);
        $docID = $DB->PUBLISHING_DOCUMENT_GET_PUBLISHING_ID(); //look for abandoned job
        //grab the next one
        if ($docID == null) {
            $docID = $DB->PUBLISHING_DOCUMENT_GET_NEXT_IN_QUEUE_ID();
            if ($docID == null) {
                echo "\nThere is no item in the Queue";
                fwrite($logfile,date(DATE_RFC2822) . ": " . "No item in the Queue.\r\n");
                $isEmptyQueue = true;
                break;
            }
        }
        //add more case if there are new templates
        //get document info
        switch($collection["templateID"])
        {
            case 1: //map template
                $DB = new MapDBHelper();
                $doc = $DB->SP_TEMPLATE_MAP_DOCUMENT_SELECT($collectionName,$docID) + $DB->DOCUMENT_GEOREC_INFO_SELECT($docID);
                break;
            case 2: //jobfolder template
                $DB = new FolderDBHelper();
                $doc = $DB->SP_TEMPLATE_FOLDER_DOCUMENT_SELECT($collectionName,$docID);
                $doc['Authors'] = $DB->GET_FOLDER_AUTHORS_BY_DOCUMENT_ID($collectionName,$docID);
                break;
            case 3: //fieldbook template
                $DB = new FieldBookDBHelper();
                $doc = $DB->SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT($collectionName,$docID);
                $doc['Crews'] = $DB->GET_FIELDBOOK_CREWS_BY_DOCUMENT_ID($collectionName,$docID);
                break;
            case 4: //indices template
                $DB = new IndicesDBHelper();
                $doc = $DB->SP_TEMPLATE_INDICES_DOCUMENT_SELECT($collectionName,$docID);
                break;
            default:
                $doc = null;
                break; //error
        }
        //get tdl (dspace) fields from document table
        $doc_dspace = $DB->PUBLISHING_DOCUMENT_GET_DSPACE_INFO($docID);
        $doc["TDLCollection"] = $collection["TDLname"];
        $doc += $collection;

        echo "\nPublishing item ID: " . $docID . "\n";
        fwrite($logfile,date(DATE_RFC2822) . ": " . "Publishing item ID:$docID" . "\r\n");
        if($debug) {
            echo "Document Info:\n\n";
            print_r($doc);

            echo "\n\nConverted Schema:\n";
            print_r($Schema->convertSchema($collection["templateID"],$doc));
        }
        echo "\n";

        //CASES:
        // IF status = 2: metadata preprocessing, publish data, prepare and publish bitstream front/back
        // IF status = 10: prepare bitstream and publish bitstreams front and back
        // IF status = 11: prepare bitstream and publish bitstream back
        switch($doc_dspace["dspacePublished"])
        {
            case 2:
                //publish from the start
                //METADATA PREPROCESSING
                //PUBLISH DATA
                $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,10); //set status to uploading
                $doc_dspace["dspacePublished"] = 10;
                $convertedSchema = $Schema->convertSchema($collection['templateID'],$doc); //convert from BandoCat to TDL schema using method in TDLSchema
                //save the converted schema in a temporary json file
                $jsonfilename = __DIR__ . '\\' . $docID . '.json';
                $fp = fopen($jsonfilename, 'w');
                fwrite($fp, $convertedSchema);
                fclose($fp);
                $header = $DS->TDL_POST_NEW_ITEM($TDLCollectionID,$jsonfilename,"application/json",null);
                if($debug)
                {
                    echo "Header:\n\n";
                    print_r($header);
                }
                $dspaceID = $Schema->getHeaderValueFromKey($header,"id"); //get item id
                $dspaceURI = $Schema->getHeaderValueFromKey($header,"handle"); //get handle uri
                unlink($jsonfilename); //delete json file
                $ret = $DB->PUBLISHING_DOCUMENT_TDL_UPDATE($docID,$dspaceID,$dspaceURI); //update new dspaceID and URI to the database
                if(!$ret)
                {
                    fwrite($logfile,date(DATE_RFC2822) . ": " . " Error updating to document table. Job Ended.\r\n");
                    fclose($logfile);
                    return;
                }
                else fwrite($logfile,date(DATE_RFC2822) . ": " . " Post new item success! DspaceID: $dspaceID | DspaceURI : $dspaceURI.\r\n");
                break;
            case 10:
            case 11:
            $dspaceID = $doc_dspace["dspaceID"];
            $dspaceURI = $doc_dspace["dspaceURI"];
            fwrite($logfile,date(DATE_RFC2822) . ": " . " Continue working on abandoned job. DspaceID: $dspaceID | DspaceURI : $dspaceURI.\r\n");
                break;
            default: fwrite($logfile,date(DATE_RFC2822) . ": " . " Unknown dspacePublished status code " . $doc_dspace["dspacePublished"] . ". Job Ended.\r\n");
                fclose($logfile);
                return;
        }
        if(!$dspaceID) //error checking
        {
            fwrite($logfile,date(DATE_RFC2822) . ": " . " Unable to post the item. Job End.\r\n");
            fclose($logfile);
            return;
        }
        fwrite($logfile,date(DATE_RFC2822) . ": Uploading bitstreams....\r\n");


        //Need to change publish bitstream schema
        // - Find the bitstream's names on Dspace
        // - Gather fullpath, filename, isBack, type (jpg/kmz/geotiff)
        // - compare: (if bitstreams name match then pass, if bitstreams name not match, then publish)


        //PUBLISH BITSTREAM FRONT
        if($doc_dspace["dspacePublished"] == 10) {
            $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,10); //set status to publishing front scan
            $pathtoImage = $collection["storagedir"] . $doc["FileNamePath"];

            //convert TIF to JPEG
            //POST jpeg bitstream
            //delete JPEG bitstream
            $jpgFilePath = str_replace(".tif",".jpg",$pathtoImage);
            $jpgFileName = str_replace(".tif",".jpg",$doc["FileName"]);
            exec("convert " . $pathtoImage . " " . $jpgFilePath );


            // TODO: check if the image exists or not. If not exists, return 404front in http_retval
            $http_retval = $DS->TDL_POST_BITSTREAM($dspaceID, $jpgFilePath, $jpgFileName);
            //delete JPEG
            exec("del " . str_replace('/', '\\', $jpgFilePath));
            fwrite($logfile, date(DATE_RFC2822) . ": " . $jpgFilePath . "\r\n");
            if ($http_retval == "200")
                fwrite($logfile, date(DATE_RFC2822) . ": Front scan has been uploaded....\r\n");
            else {
                fwrite($logfile, date(DATE_RFC2822) . ": Front scan failed to upload. Code $http_retval. Job End\r\n");
                fclose($logfile);
                return;
            }

            //georec front if available
//            if($doc['geoRecFrontStatus'] == 1)
//            {
//                //kmz
//                $http_retval = $DS->TDL_POST_BITSTREAM($dspaceID, $doc['georecdir'] . $doc['georecFrontDirKMZ'], $doc['georecFrontDirKMZ']);
//                //delete JPEG
//                exec("del " . $jpgFilePath . " 2>nul");
//                if ($http_retval == "200")
//                    fwrite($logfile, date(DATE_RFC2822) . ": KMZ Front scan has been uploaded....\r\n");
//                else {
//                    fwrite($logfile, date(DATE_RFC2822) . ": KMZ Front scan failed to upload. Code $http_retval. Job End\r\n");
//                    fclose($logfile);
//                    return;
//                }
//
//                //geotiff
//                $http_retval = $DS->TDL_POST_BITSTREAM($dspaceID, $doc['georecdir'] . $doc['georecFrontDirGeoTIFF'], $doc['georecFrontDirGeoTIFF']);
//                //delete JPEG
//                exec("del " . $jpgFilePath . " 2>nul");
//                if ($http_retval == "200")
//                    fwrite($logfile, date(DATE_RFC2822) . ": KMZ Front scan has been uploaded....\r\n");
//                else {
//                    fwrite($logfile, date(DATE_RFC2822) . ": KMZ Front scan failed to upload. Code $http_retval. Job End\r\n");
//                    fclose($logfile);
//                    return;
//                }
//            }

        }

        //PUBLISH BITSTREAM BACK (IF AVAILABLE)
        if(!isset($doc["FileNameBack"]) || $doc["FileNameBack"] == "" || $doc["FileNameBack"] == null)
        {
            fwrite($logfile,date(DATE_RFC2822) . ": There is no back scan....\r\n");
            //back not available
        }
        else
        {
            $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,11); //set status to publishing back scan
            $pathtoImageBack = $collection["storagedir"] . $doc["FileNameBackPath"];
            //convert TIF to JPEG
            //POST jpeg bitstream
            //delete JPEG bitstream
            $jpgFilePathBack = str_replace(".tif",".jpg",$pathtoImageBack);
            $jpgFileNameBack = str_replace(".tif",".jpg",$doc["FileNameBack"]);
            exec("convert " . $pathtoImageBack . " " . $jpgFilePathBack );

            // TODO: check if the image exists or not. If not exists, return 404back in http_retval
            $http_retval = $DS->TDL_POST_BITSTREAM($dspaceID, $jpgFilePathBack, $jpgFileNameBack);
            //delete JPEG
            exec("del " . $jpgFilePathBack . " 2>nul");
            if($http_retval == "200")
                fwrite($logfile,date(DATE_RFC2822) . ": Back scan has been uploaded....\r\n");
            else {
                fwrite($logfile,date(DATE_RFC2822) . ": Back scan failed to upload. Code $http_retval. Job End\r\n");
                fclose($logfile);
                return;
            }
        }

        if($http_retval == "200") {
            fwrite($logfile, date(DATE_RFC2822) . ": Bitstream(s) uploaded successfully....\r\n");
            fwrite($logfile, date(DATE_RFC2822) . ": Document ID: $docID has been published!\r\n");
            $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,1); //set status to Published

            //LOG
            $ret = $DB->SP_LOG_WRITE("publish",$collection["collectionID"],$docID,1,"success","System Log");
            if(!$ret)
                fwrite($logfile, date(DATE_RFC2822) . ": Unable to write log to DB!\r\n");
        }
        else $DB->PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,-1); //set status to Error

        //$isEmptyQueue = true; //break the loop, for testing
    }

    echo "\n...\n...\n...\nEnd Job...";
    fwrite($logfile,date(DATE_RFC2822) . ": " . "Job Ended.\r\n");
    fclose($logfile);
    return;
<?php
//check for session
require '../../Library/SessionManager.php';
$session = new SessionManager();
    //prevent accessing directly
    if(!isset($_POST))
        header('Location: index.php');


    require '../../Library/DBHelper.php';
    $DB = new DBHelper();
    $data = $_POST;
    $action = htmlspecialchars($data['txtAction']);
    $collection = htmlspecialchars($data['txtCollection']);
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);

    //data pre-processing
    //Date
    require '../../Library/DateHelper.php';
    $date = new DateHelper();
    $startdate = $date->mergeDate($data['ddlStartMonth'],$data['ddlStartDay'],$data['ddlStartYear']);
    $enddate = $date->mergeDate($data['ddlEndMonth'],$data['ddlEndDay'],$data['ddlEndYear']);
    //Company, Author, Customer, Medium ID
    $DB->SP_TEMPLATE_MAP_CUSTOMER_GET_ID_FROM_NAME_WITH_INSERT($collection,$data['txtCustomer'],$customerID);
    $DB->SP_TEMPLATE_MAP_COMPANY_GET_ID_FROM_NAME_WITH_INSERT($collection,$data['txtCompany'],$companyID);
    $DB->SP_TEMPLATE_MAP_AUTHOR_GET_ID_FROM_NAME_WITH_INSERT($collection,$data['txtAuthor'],$authorID);
    $DB->SP_TEMPLATE_MAP_MEDIUM_GET_ID_FROM_NAME($collection,$data['ddlMedium'],$mediumID);

    //review
    if($action == "review")
    {
        $retval = $DB->SP_TEMPLATE_MAP_DOCUMENT_UPDATE($collection,$data['txtDocID'],$data['txtLibraryIndex'],$data['txtTitle'],$data['txtSubtitle'],
            $data['rbIsMap'],$data['txtMapScale'],$data['rbHasNorthArrow'],$data['rbHasStreets'],
                $data['rbHasPOI'],$data['rbHasCoordinates'],$data['rbHasCoast'],$data['rbNeedsReview'],
                $data['txtComments'],$customerID,$startdate,$enddate,$data['txtFieldBookNumber'],$data['txtFieldBookPage'],$data['ddlReadability'],
            $data['ddlRectifiability'],$companyID,$data['txtType'],$mediumID,$authorID);

    }
    //catalog (new document)
    else if($action == "catalog")
    {

    }

    if($retval == false)
        $logstatus = "fail";
            else  $logstatus = "success";
    //write log
    //$DB->SP_LOG_WRITE($action,$config['CollectionID'], $session->getUserID(),$logstatus);

    echo json_encode(array($data['rbHasNorthArrow'],$data['rbHasStreets']));
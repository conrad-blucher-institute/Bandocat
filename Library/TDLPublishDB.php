<?php
//This trait provides general functions to select or update fields related to TDL in `document` table of every collections
/**
 * Created by PhpStorm.
 * User: snguyen1
 * Date: 2/15/2017
 * Time: 12:22 PM
 */
interface TDLPublishDB
{
}
//Functions belong to trait can be used for georectification of different templates
trait TDLPublishTrait
{
    function PUBLISHING_DOCUMENT_GET_DSPACE_INFO($docID)
    {
        $sth = $this->getConn()->prepare("SELECT `dspacePublished`,`dspaceURI`,`dspaceID` FROM `document` WHERE `documentID` = :docID LIMIT 1");
        $sth->bindParam(':docID',$docID,PDO::PARAM_INT);
        $ret = $sth->execute();
        if($ret)
            return $sth->fetch(PDO::FETCH_ASSOC);
        print_r($sth->errorInfo());
        return null;
    }

    function PUBLISHING_DOCUMENT_GET_NEXT_IN_QUEUE_ID()
    {
        $sth = $this->getConn()->prepare("SELECT `documentID` FROM `document` WHERE `dspacePublished` = 2 ORDER BY `documentID` ASC LIMIT 1");
        $ret = $sth->execute();
        if($ret)
            return $sth->fetchColumn();
        return null;
    }

    function PUBLISHING_DOCUMENT_GET_PUBLISHING_ID()
    {
            $sth = $this->getConn()->prepare("SELECT `documentID` FROM `document` WHERE `dspacePublished` = 10 OR `dspacePublished` = 11 ORDER BY `documentID` ASC LIMIT 1");
            $ret = $sth->execute();
            if($ret)
                return $sth->fetchColumn();
            return null;
    }

    //2 = In publish Queue, 1 = Published, 0 = Not published, 10 = Publishing...
    function PUBLISHING_DOCUMENT_UPDATE_STATUS($docID,$status){
        $sth = $this->getConn()->prepare("UPDATE `document` SET `dspacePublished` = :stat WHERE `documentID` = :docID");
        $sth->bindParam(':stat',$status,PDO::PARAM_INT);
        $sth->bindParam(':docID',$docID,PDO::PARAM_INT);
        $ret = $sth->execute();
//        if(!$ret)
//            print_r($sth->errorInfo());
        return $ret;
    }

    function PUBLISHING_GET_PUBLISH_QUEUE()
    {
        $sth = $this->getConn()->prepare("SELECT `documentID`,`libraryindex`,`dspacePublished` FROM `document` WHERE `dspacePublished` = 2 OR `dspacePublished` = 10 ORDER BY `dspacePublished` DESC,`documentID` ASC");
        $ret = $sth->execute();
        //if(!$ret)
        //    print_r($sth->errorInfo());
        if($ret){
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    function PUBLISHING_PUSH_TO_QUEUE($howMany = null)
    {
        $query = "UPDATE `document` SET `dspacePublished`  = 2 WHERE `dspacePublished` = 0 ORDER BY `documentID` ASC";
        if($howMany != null)
            $query = $query . " LIMIT " . $howMany;
        //$howMany = null means publishing all documents in a collections
        $sth = $this->getConn()->prepare($query);
        $ret = $sth->execute();
        //if(!$ret)
        //    print_r($sth->errorInfo());
        return $ret;
    }

    function PUBLISHING_RESET_QUEUE()
    {
        $sth = $this->getConn()->prepare("UPDATE `document` SET `dspacePublished` = 0 WHERE `dspacePublished` = 2");
        $ret = $sth->execute();
        //if(!$ret)
        //    print_r($sth->errorInfo());
        return $ret;
    }

    function PUBLISHING_DOCUMENT_TDL_UPDATE($docID,$dspaceID,$dspaceURI)
    {
        $sth = $this->getConn()->prepare("UPDATE `document` SET `dspaceID` = :dspaceID,`dspaceURI` = :dspaceURI WHERE `documentID` = :docID");
        $sth->bindParam(':dspaceID',$dspaceID,PDO::PARAM_INT);
        $sth->bindParam(':dspaceURI',$dspaceURI,PDO::PARAM_STR);
        $sth->bindParam(':docID',$docID,PDO::PARAM_INT);
        $ret = $sth->execute();
        //if(!$ret)
            //print_r($sth->errorInfo());
        return $ret;
    }

}
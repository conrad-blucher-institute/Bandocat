<?php
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/


class CreatorHelper extends DBHelper
{

    function GET_COLLECTION_AND_TEMPLATE()
    {
        $this->getConn()->exec('USE ' . $this->maindb);
        $sth = $this->getConn()->prepare("SELECT `collection`.`name`,`collection`.`displayname`,`collection`.`dbname`,`collection`.`templateID`,t.`name`,t.`description` FROM `collection` LEFT JOIN `template` AS t ON `collection`.`templateID` = t.`templateID`");
        $ret = $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    function GET_TEMPLATES()
    {
        $this->getConn()->exec('USE ' . $this->maindb);
        $sth = $this->getConn()->prepare("SELECT `template`.`templateID`,`template`.`name`,`template`.`description` FROM `template`");
        $ret = $sth->execute();
        return $sth->fetchAll(PDO::FETCH_NUM);
    }


    function COLLECTION_VALIDATE_NEW_ENTRY($iParName,$iDisplayName,$iDBName)
    {
        $this->getConn()->exec('USE ' . $this->maindb);
        $sth = $this->getConn()->prepare("SELECT COUNT(*) FROM `collection` WHERE `name` = :parname OR `displayname` = :dname OR `dbname` = :dbname");
        $sth->bindParam(':parname',$iParName,PDO::PARAM_STR);
        $sth->bindParam(':dname',$iDisplayName,PDO::PARAM_STR);
        $sth->bindParam(':dbname',$iDBName,PDO::PARAM_STR);
        $ret = $sth->execute();
        if($ret)
            $ret = $sth->fetchColumn();
        return $ret;
    }

    //need to fix
    function COLLECTION_INSERT($iParName,$iDisplayName,$iDBName,$iStorageDir,$iThumbnailDir,$iTemplateID,$iGeorecDir)
    {
        $this->getConn()->exec('USE ' . $this->maindb);
        $sth = $this->getConn()->prepare("INSERT INTO `collection`(`name`,`displayname`,`dbname`,`storagedir`,`thumbnaildir`,`templateID`,`georecdir`) VALUES(:parname,:dname,:dbname,:storagedir,:thumbdir,:tID,:georecdir)");
        $sth->bindParam(':parname',$iParName,PDO::PARAM_STR);
        $sth->bindParam(':dname',$iDisplayName,PDO::PARAM_STR);
        $sth->bindParam(':dbname',$iDBName,PDO::PARAM_STR);
        $sth->bindParam(':storagedir',$iStorageDir,PDO::PARAM_STR);
        $sth->bindParam(':thumbdir',$iThumbnailDir,PDO::PARAM_STR);
        $sth->bindParam(':tID',$iTemplateID,PDO::PARAM_INT);
        $sth->bindParam(':georecdir',$iGeorecDir,PDO::PARAM_STR);
        $ret = $sth->execute();
        if($ret)
            return $this->getConn()->lastInsertId();
        return $ret;
    }

    function COLLECTION_DELETE($iColID)
    {
        $this->getConn()->exec('USE ' . $this->maindb);
        $sth = $this->getConn()->prepare("DELETE FROM `collection` WHERE `collectionID` = :colID");
        $sth->bindParam(':colID',$iColID,PDO::PARAM_INT);
        $ret = $sth->execute();
        return $ret;
    }

    //limit1 => select only one db
    function GET_DBNAME_FROM_TEMPLATEID($iTemplateID,$limit1)
    {
        $this->getConn()->exec('USE ' . $this->maindb);
        if($limit1 == true)
            $sth = $this->getConn()->prepare("SELECT `dbname` FROM `collection` WHERE `templateID` = :tID ORDER BY `collectionID` ASC LIMIT 1");
        else $sth = $this->getConn()->prepare("SELECT `dbname` FROM `collection` WHERE `templateID` = :tID ORDER BY `collectionID` ASC");
        $sth->bindParam(':tID',$iTemplateID,PDO::PARAM_INT);
        $ret = $sth->execute();
        if($ret)
            return $sth->fetchColumn();
        return false;
    }

    //TO DO:
    //add mysql/bin into Path
    //increase maximum execution time in php.ini
    function DATABASE_CLONE_NEW($iNewDBName,$iExistingDBName)
    {
        $query = "CREATE DATABASE IF NOT EXISTS " . $iNewDBName;
        $sth = $this->getConn()->query($query);
        if($sth) {
            //export routines,triggers and schema (no data) of existing db
            $export = "mysqldump -d --routines --triggers -u " . parent::$user .  " -p" . parent::$pwd . " " . $iExistingDBName . " > temp.sql 2>&1";
            exec($export,$output);
            $import = "mysql -u " . parent::$user . " -p" . parent::$pwd . " " . $iNewDBName . " < temp.sql 2>&1";
            exec($import,$output);
            //delete temp file
            $delete = "del /f temp.sql 2>&1";
            exec($delete,$output);

            //reset Auto Increment on all tables (starts with 1)
            $this->DATABASE_RESET_TABLES_AUTOINCREMENT($iNewDBName);
            return true;
        }
        return false;
    }

    //unsafe
    function DATABASE_RESET_TABLES_AUTOINCREMENT($iDBName)
    {
        $this->getConn()->query("USE " . $iDBName);
        $sth = $this->getConn()->prepare("SHOW TABLES");
        $sth->execute();
        $table_lists = $sth->fetchAll(PDO::FETCH_NUM);
        foreach($table_lists as $table)
        {
            $sth = $this->getConn()->query("TRUNCATE TABLE " . $table[0]);
            $sth->execute();
        }
        return true;
    }

}
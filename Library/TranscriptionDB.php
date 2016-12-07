<?php

/**
 * Created by PhpStorm.
 * User: snguyen1
 * Date: 12/5/2016
 * Time: 3:06 PM
 */
interface TranscriptionDB
{
    public function TRANSCRIPTION_ENTRY_INSERT($collection,$newObject);
    public function TRANSCRIPTION_ENTRY_UPDATE($collection,$updateObject);
    public function TRANSCRIPTION_ENTRY_SELECT($collection,$docID,$x1,$y1,$x2,$y2);

}

trait TranscriptionTrait
{
    public function TRANSCRIPTION_ENTRY_DELETE($collection,$docID,$x1,$y1,$x2,$y2)

    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            $sth = $this->getConn()->prepare("DELETE FROM `transcription` WHERE  `documentID` = :docID AND `x1` = :x1 AND `y1` = :y1 AND `x2` = :x2 AND `y2` = :y2");
            $sth->bindParam(':docID',$docID,PDO::PARAM_INT,11);
            $sth->bindParam(':x1',$x1,PDO::PARAM_INT,11);
            $sth->bindParam(':x2',$x2,PDO::PARAM_INT,11);
            $sth->bindParam(':y1',$y1,PDO::PARAM_INT,11);
            $sth->bindParam(':y2',$y2,PDO::PARAM_INT,11);
            $ret = $sth->execute();
            return $ret;
        }
        return false;
    }

    public function TRANSCRIPTION_GET_COORDINATES($collection,$docID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            $sth = $this->getConn()->prepare("SELECT `x1`,`y1`,`x2`,`y2` FROM `transcription` WHERE `documentID` = :docID");
            $sth->bindParam(':docID',$docID,PDO::PARAM_INT,11);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
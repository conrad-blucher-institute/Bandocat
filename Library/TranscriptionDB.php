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
    /**********************************************
     * Function: TRANSCRIPTION_ENTRY_DELETE
     * Description: deletes a transcription entry in the db
     * Parameter(s):
     * collection (in string) - name of the collection
     * $docID (in int) - the id of the document
     * $x1 (in int) - x value of the box selecting a piece of information on the page
     * $y1 (in int) - y value of the box selecting a piece of information on the page
     * $x2 (in int) - x2 value of the box selecting a piece of information on the page
     * $y2 (in int) - y2 value of the box selecting a piece of information on the page
     * Return value(s):
     * $result True If success, or FALSE if failed
     ***********************************************/
    public function TRANSCRIPTION_ENTRY_DELETE($collection,$docID,$x1,$y1,$x2,$y2)

    {
        //get appropriate DB
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "")
        {
            $this->getConn()->exec('USE ' . $dbname);
            //prepares a delete sql statement
            $sth = $this->getConn()->prepare("DELETE FROM `transcription` WHERE  `documentID` = :docID AND `x1` = :x1 AND `y1` = :y1 AND `x2` = :x2 AND `y2` = :y2");
            //binds variables to the sql statement above
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

    /**********************************************
     * Function: TRANSCRIPTION_GET_COORDINATES
     * Description: gets a transcription entry coordinates in the db
     * Parameter(s):
     * collection (in string) - name of the collection
     * $docID (in int) - the id of the document
     * Return value(s):
     * $result True If success, or FALSE if failed
     ***********************************************/
    public function TRANSCRIPTION_GET_COORDINATES($collection,$docID)
    {
        //get appropriate DB
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "")
        {
            $this->getConn()->exec('USE ' . $dbname);
            //selects the coordinates from the transcriptions DB
            $sth = $this->getConn()->prepare("SELECT `x1`,`y1`,`x2`,`y2` FROM `transcription` WHERE `documentID` = :docID");
            //bind variables to the above sql statement
            $sth->bindParam(':docID',$docID,PDO::PARAM_INT,11);
            $sth->execute();
            //returns array of coords
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
    /**********************************************
     * Function: SP_TEMPLATE_INDICES_DOCUMENT_CHECK_EXIST_RECORD
     * Description: Checks if a template for indices documents exists
     * Parameter(s):
     * collection (in string) - name of the collection
     * $iLibraryIndex (in int) - the id of the document
     * Return value(s):
     * $result True If success, or FALSE if failed
     ***********************************************/
    function SP_TEMPLATE_INDICES_DOCUMENT_CHECK_EXIST_RECORD($collection, $iLibraryIndex)
    {
        //get appropriate DB
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "")
        {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            /* Prepares the SQL query, and returns a statement handle to be used for further operations on the statement*/
            // sql statement CALL calls the function pointed to in the db
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_INDICES_DOCUMENT_CHECK_EXIST_RECORD(?,@oReturnValue)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->getConn()->errorInfo()[0]);
            //binds variables to the supplied sql statement
            $call->bindParam(1, $iLibraryIndex, PDO::PARAM_STR, 40);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            //selects the return value if successful
            $select = $this->getConn()->query('SELECT @oReturnValue');
            //returns true if successful
            $result = $select->fetch(PDO::FETCH_NUM);
            if($result[0] == 1)
                return "EXISTED";
            else if($result[0] == 0)
                return "GOOD";
        } else return false;
    }
}
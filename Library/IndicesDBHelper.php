<?php
require_once 'TranscriptionDB.php';
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/
class IndicesDBHelper extends DBHelper implements TranscriptionDB
{
    use TranscriptionTrait;

    /**********************************************
     * Function: SP_TEMPLATE_INDICES_DOCUMENT_SELECT
     * Description: GIVEN collection name & document ID, RETURN INFORMATION ABOUT Document
     * Parameter(s):
     * collection (in string) - name of the collection
     * $iDocID (in Integer) - document ID
     * Return value(s):
     * $result (assoc array) - return a document info in an associative array, or FALSE if failed
     ***********************************************/
    function SP_TEMPLATE_INDICES_DOCUMENT_SELECT($collection, $iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_INDICES_DOCUMENT_SELECT(?,@oLibraryIndex,@oBookName,@oPageType,@oPageNumber,@oComments,@oNeedsReview,@oFileName,@oTranscribed)");
            $call->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);

            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oLibraryIndex AS LibraryIndex,@oBookName AS BookName,@oPageType AS PageType,@oPageNumber AS PageNumber,@oComments AS Comments,@oNeedsReview AS NeedsReview,@oFileName AS FileName,@oTranscribed AS Transcribed');
            $result = $select->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else return false;
    }



    function GET_INDICES_MAPKIND($collection)
    {
            $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
            $this->getConn()->exec('USE ' . $dbname);
            $sth = $this->getConn()->prepare("SELECT `mapkindname` FROM `mapkind` ORDER BY `mapkindname` ASC");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
    }


    public function TRANSCRIPTION_ENTRY_INSERT()
    {

    }
    public function TRANSCRIPTION_ENTRY_UPDATE()
    {

    }

    public function TRANSCRIPTION_ENTRY_SELECT($collection,$docID,$x1,$y1,$x2,$y2)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
        $sth = $this->getConn()->prepare("SELECT `documentID`,`x1`,`y1`,`x2`,`y2`,`surveyorsection`,`blockortract`,`lotoracres`,
			`description`, `client`, `fieldbookinfo`, `relatedpapersfileno`,
			`mapinfo`, `date`, `jobnumber`,`comments` FROM `transcription` WHERE `documentID` = :docID AND `x1` = :x1 AND `y1` = :y1 AND `x2` = :x2 AND `y2` = :y2 LIMIT 1");
            $sth->bindParam(':docID',$docID,PDO::PARAM_INT,11);
            $sth->bindParam(':x1',$x1,PDO::PARAM_INT,11);
            $sth->bindParam(':x2',$x2,PDO::PARAM_INT,11);
            $sth->bindParam(':y1',$y1,PDO::PARAM_INT,11);
            $sth->bindParam(':y2',$y2,PDO::PARAM_INT,11);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }



}
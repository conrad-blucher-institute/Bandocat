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

    function SP_TEMPLATE_INDICES_DOCUMENT_INSERT($collection, $iLibraryIndex, $iBookID, $iPageType, $iPageNumber, $iComments, $iNeedsReview, $iFilename)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection));
        $db = $dbname['DbName'];
        if ($db != null && $db != ""){
            $this->getConn()->exec('USE ' . $db);
        //Prepare Statement
            if($iPageNumber == "")
                $iPageNumber = null;
        $call = $this->getConn()->prepare("CALL SP_TEMPLATE_INDICES_DOCUMENT_INSERT(?,?,?,?,?,?,?)");
        if (!$call)
            trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
        $call->bindParam(1, ($iLibraryIndex), PDO::PARAM_STR, 200);
        $call->bindParam(2, ($iBookID), PDO::PARAM_INT, 11);
        $call->bindParam(3, ($iPageType), PDO::PARAM_STR, 18);
        $call->bindParam(4, ($iPageNumber), PDO::PARAM_INT, 11);
        $call->bindParam(5, ($iComments), PDO::PARAM_STR, 40);
        $call->bindParam(6, ($iNeedsReview), PDO::PARAM_INT, 1);
        $call->bindParam(7, ($iFilename), PDO::PARAM_STR, 200);

        //Execute Statement
        $ret = $call->execute();
        if($ret)
        {
            $select = $this->getConn()->query('SELECT LAST_INSERT_ID()');
            $ret = $select->fetch(PDO::FETCH_COLUMN);
            return $ret;
        }
        else{
            return false;
        }

    }
    else return false;

    }

    function SP_TEMPLATE_INDICES_DOCUMENT_UPDATE($collection, $iLibraryIndex, $iBookID, $iPageType, $iPageNumber, $iComments, $iNeedsReview, $iFilename)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection));
        $db = $dbname['DbName'];
        if ($db != null && $db != ""){
            $this->getConn()->exec('USE ' . $db);
            //Prepare Statement
            if($iPageNumber == "")
                $iPageNumber = null;
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_INDICES_DOCUMENT_UPDATE(?,?,?,?,?,?,?)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, ($iLibraryIndex), PDO::PARAM_STR, 200);
            $call->bindParam(2, ($iBookID), PDO::PARAM_INT, 11);
            $call->bindParam(3, ($iPageType), PDO::PARAM_STR, 18);
            $call->bindParam(4, ($iPageNumber), PDO::PARAM_INT, 11);
            $call->bindParam(5, ($iComments), PDO::PARAM_STR, 40);
            $call->bindParam(6, ($iNeedsReview), PDO::PARAM_INT, 1);
            $call->bindParam(7, ($iFilename), PDO::PARAM_STR, 200);

            //Execute Statement
            return $call->execute();
        }
        else return false;
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


    public function TRANSCRIPTION_ENTRY_INSERT($collection,$newObject)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        $sth = $this->getConn()->prepare("INSERT INTO `transcription` (`documentID`,`x1`, `y1`, `x2`, `y2`, `surveyorsection`, `blockortract`,
                                          `lotoracres`,`description`,`client`,`fieldbookinfo`,`relatedpapersfileno`,`mapinfo`,`date`,`jobnumber`,`comments`)
			VALUES (:docID,:x1,:y1,:x2,:y2,:surveyOrSection, :blockOrTract,:lotOrAcres,
			:description, :entryclient, :fieldBookInfo,:relatedPapersFileNo,:mapInfo,:entrydate,:jobNumber,:comments)");
        $sth->bindParam(':docID',$newObject->docID,PDO::PARAM_INT,11);
        $sth->bindParam(':x1',$newObject->x1,PDO::PARAM_INT,11);
        $sth->bindParam(':y1',$newObject->y1,PDO::PARAM_INT,11);
        $sth->bindParam(':x2',$newObject->x2,PDO::PARAM_INT,11);
        $sth->bindParam(':y2',$newObject->y2,PDO::PARAM_INT,11);
        $sth->bindParam(':surveyOrSection',$newObject->surveyOrSection,PDO::PARAM_STR,100);
        $sth->bindParam(':blockOrTract',$newObject->blockOrTract,PDO::PARAM_STR,100);
        $sth->bindParam(':lotOrAcres',$newObject->lotOrAcres,PDO::PARAM_STR,100);
        $sth->bindParam(':description',$newObject->description,PDO::PARAM_STR,200);
        $sth->bindParam(':entryclient',$newObject->client,PDO::PARAM_STR,100);
        $sth->bindParam(':fieldBookInfo',$newObject->fieldBookInfo,PDO::PARAM_STR,900);
        $sth->bindParam(':relatedPapersFileNo',$newObject->relatedPapersFileNo,PDO::PARAM_STR,100);
        $sth->bindParam(':mapInfo',$newObject->mapInfo,PDO::PARAM_STR,800);
        $sth->bindParam(':entrydate',$newObject->entryDate,PDO::PARAM_STR,10);
        $sth->bindParam(':jobNumber',$newObject->jobNumber,PDO::PARAM_STR,20);
        $sth->bindParam(':comments',$newObject->comments,PDO::PARAM_STR,200);
        $ret = $sth->execute();
        return $ret;
        
    }
    public function TRANSCRIPTION_ENTRY_UPDATE($collection,$updateObject)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        $sth = $this->getConn()->prepare("UPDATE `transcription` SET
			`surveyorsection` = :surveyOrSection,
			`blockortract` = :blockOrTract,
			`lotoracres` = :lotOrAcres,
			`description` = :description,
			`client` = :entryclient,
			`fieldbookinfo` = :fieldBookInfo,
			`relatedpapersfileno` = :relatedPapersFileNo,
			`mapinfo` = :mapInfo,
			`date` = :entrydate,
			`jobnumber` = :jobNumber,
			`comments` = :comments
			WHERE `documentID` = :docID AND `x1` = :x1 AND `y1` = :y1 AND `x2` = :x2 AND `y2` = :y2");
        $sth->bindParam(':docID',$updateObject->docID,PDO::PARAM_INT,11);
        $sth->bindParam(':x1',$updateObject->x1,PDO::PARAM_INT,11);
        $sth->bindParam(':y1',$updateObject->y1,PDO::PARAM_INT,11);
        $sth->bindParam(':x2',$updateObject->x2,PDO::PARAM_INT,11);
        $sth->bindParam(':y2',$updateObject->y2,PDO::PARAM_INT,11);
        $sth->bindParam(':surveyOrSection',$updateObject->surveyOrSection,PDO::PARAM_STR,100);
        $sth->bindParam(':blockOrTract',$updateObject->blockOrTract,PDO::PARAM_STR,100);
        $sth->bindParam(':lotOrAcres',$updateObject->lotOrAcres,PDO::PARAM_STR,100);
        $sth->bindParam(':description',$updateObject->description,PDO::PARAM_STR,200);
        $sth->bindParam(':entryclient',$updateObject->client,PDO::PARAM_STR,100);
        $sth->bindParam(':fieldBookInfo',$updateObject->fieldBookInfo,PDO::PARAM_STR,900);
        $sth->bindParam(':relatedPapersFileNo',$updateObject->relatedPapersFileNo,PDO::PARAM_STR,100);
        $sth->bindParam(':mapInfo',$updateObject->mapInfo,PDO::PARAM_STR,800);
        $sth->bindParam(':entrydate',$updateObject->entryDate,PDO::PARAM_STR,10);
        $sth->bindParam(':jobNumber',$updateObject->jobNumber,PDO::PARAM_STR,20);
        $sth->bindParam(':comments',$updateObject->comments,PDO::PARAM_STR,200);
        $ret = $sth->execute();
        return $ret;

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
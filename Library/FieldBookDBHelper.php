<?php
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/
class FieldBookDBHelper extends DBHelper
{

    /**********************************************
     * Function: SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT
     * Description: GIVEN collection name & document ID, RETURN INFORMATION ABOUT Document
     * Parameter(s):
     * collection (in string) - name of the collection
     * $iDocID (in Integer) - document ID
     * Return value(s):
     * $result (assoc array) - return a document info in an associative array, or FALSE if failed
     ***********************************************/

    function SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT($collection, $iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT(?,@oLibraryIndex,@oFBCollection,@oBookTitle,@oJobNumber,@oJobTitle,@oAuthor,@oStartDate,@oEndDate,@oComments,@oIndexedPage,@oIsBlankPage,@oIsSketch,@oIsLooseDoc,@oNeedsInput,@oNeedsReview,@oFileNamePath,@oThumbnail)");
            $call->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);

            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oLibraryIndex AS LibraryIndex,@oFBCollection AS Collection,@oBookTitle AS BookTitle,@oJobNumber As JobNumber,@oJobTitle AS JobTitle,@oAuthor AS Author,@oStartDate AS StartDate,@oEndDate AS EndDate,@oComments AS Comments,@oIndexedPage AS IndexedPage,@oIsBlankPage AS IsBlankPage,@oIsSketch AS IsSketch,@oIsLooseDoc AS IsLooseDoc,@oNeedsInput AS NeedsInput,@oNeedsReview AS NeedsReview,@oFileNamePath AS FileNamePath,@oThumbnail AS Thumbnail');
            $result = $select->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else return false;
    }

    function SP_TEMPLATE_FIELDBOOK_DOCUMENT_INSERT($collection,$iLibraryIndex,$iBookTitle,$iFileNamePath,$iFileName,$iThumbnail)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection));
        $db = $dbname['DbName'];
        if ($db != null && $db != ""){
            $this->getConn()->exec('USE ' . $db);
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FIELDBOOK_DOCUMENT_INSERT(:libindex,:book,:path,:file,:thumbnail)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);

            $call->bindParam(':libindex', ($iLibraryIndex), PDO::PARAM_STR);
            $call->bindParam(':book', ($iBookTitle), PDO::PARAM_STR);
            $call->bindParam(':path', ($iFileNamePath), PDO::PARAM_STR);
            $call->bindParam(':file', ($iFileName), PDO::PARAM_STR);
            $call->bindParam(':thumbnail', ($iThumbnail), PDO::PARAM_STR);

            //Execute Statement
            $ret = $call->execute();
            if($ret)
            {
                $select = $this->getConn()->query('SELECT LAST_INSERT_ID()');
                $data = $select->fetch(PDO::FETCH_COLUMN);
                return $data;
            }
            else{
                return false;
            }

        }
        else return false;

    }

    function SP_TEMPLATE_FIELDBOOK_DOCUMENT_UPDATE($collection, $iDocID, $iLibraryIndex, $iFBCollectionName, $iBookTitle, $iJobNumber,$iJobTitle,$iAuthorName,$iStartDate,$iEndDate,$iComments,$iIndexedPage,$iIsBlankPage,$iIsSketch,$iIsLooseDoc,$iNeedsInput, $iNeedsReview)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection));
        $db = $dbname['DbName'];
        if ($db != null && $db != ""){
            $this->getConn()->exec('USE ' . $db);
            //Prepare Statement
            if($iIndexedPage == "")
                $iIndexedPage = null;
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FIELDBOOK_DOCUMENT_UPDATE(:docID,:lib,:fbcol,:btitle,:jnumber,:jtitle,:author,:sdate,:edate,:comments,:indexed,:blankp,:sketch,:loose,:input,:review)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(':docID', ($iDocID), PDO::PARAM_INT);
            $call->bindParam(':lib', ($iLibraryIndex), PDO::PARAM_STR);
            $call->bindParam(':fbcol', ($iFBCollectionName), PDO::PARAM_STR);
            $call->bindParam(':btitle',($iBookTitle), PDO::PARAM_STR);
            $call->bindParam(':jnumber', ($iJobNumber), PDO::PARAM_STR);
            $call->bindParam(':jtitle', ($iJobTitle), PDO::PARAM_STR);
            $call->bindParam(':author', ($iAuthorName), PDO::PARAM_STR);
            $call->bindParam(':sdate', ($iStartDate), PDO::PARAM_STR);
            $call->bindParam(':edate', ($iEndDate), PDO::PARAM_STR);
            $call->bindParam(':comments', ($iComments), PDO::PARAM_STR);
            $call->bindParam(':indexed', ($iIndexedPage), PDO::PARAM_STR);
            $call->bindParam(':blankp', ($iIsBlankPage), PDO::PARAM_INT);
            $call->bindParam(':sketch', ($iIsSketch), PDO::PARAM_INT);
            $call->bindParam(':loose', ($iIsLooseDoc), PDO::PARAM_INT);
            $call->bindParam(':input', ($iNeedsInput), PDO::PARAM_INT);
            $call->bindParam(':review', ($iNeedsReview), PDO::PARAM_INT);
            $ret = $call->execute();
            //Execute Statement
            //var_dump($call->errorInfo());
            if($ret)
                return true;
            return false;
        }

    }

    function TEMPLATE_FIELDBOOK_DELETE_DOCUMENTCREW($collection,$iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection));
        $db = $dbname['DbName'];
        $this->getConn()->exec('USE ' . $db);
        //DELETE FROM DOCUMENTCREW BEFORE INSERTING
        $sth = $this->getConn()->prepare("DELETE FROM `documentcrew` WHERE `documentcrew`.`docID` = :docID");
        $sth->bindParam(':docID', ($iDocID), PDO::PARAM_INT);
        $ret = $sth->execute();
        if($ret)
            return true;
        return false;
    }

    /**********************************************
     * Function: SP_TEMPLATE_FIELDBOOK_DOCUMENTCREW_INSERT
     * Description: GIVEN document ID & array of CrewName, delete from documentcrew first, then call SP to select/insert from crew table, then insert into documentcrew
     * Parameter(s):
     * collection (in string) - name of the collection
     * $iDocID (in Integer) - document ID
     * $iCrewNameArray (in Array) - Array of Crew names
     * Return value(s):
     * True if good, False if fail
     ***********************************************/

    function SP_TEMPLATE_FIELDBOOK_DOCUMENTCREW_INSERT($collection,$iDocID,$iCrewNameArray)
    {
        //DELETE FROM DOCUMENTCREW BEFORE INSERTING
        $ret = $this->TEMPLATE_FIELDBOOK_DELETE_DOCUMENTCREW($collection,$iDocID);
        if($ret) {
            $sth = $this->getConn()->prepare("DELETE FROM `documentcrew` WHERE `documentcrew`.`docID` = :docID");
            $sth->bindParam(':docID', ($iDocID), PDO::PARAM_INT);
            $ret = $sth->execute();
            if (!$ret)
                return false;
            //INSERT VALUES INTO DOCUMENTCREW, IF VALUE DOES NOT EXIST IN CREW TABLE, INSERT INTO CREW FIRST
            foreach ($iCrewNameArray as $iCrew) {
                $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FIELDBOOK_DOCUMENTCREW_INSERT(:docID,:crew)");
                if (!$call) {
                    trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
                    return false;
                }
                $call->bindParam(':docID', ($iDocID), PDO::PARAM_INT);
                $call->bindParam(':crew', ($iCrew), PDO::PARAM_STR);
                $ret = $call->execute();
                if ($ret == false)
                    return false;
            }
            return true;
        }
        return false;
    }

    //FileName is unique on document table
    public function TEMPLATE_FIELDBOOK_CHECK_EXIST_RECORD_BY_FILENAME($collection, $iFileName)
    {
        $db = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($db != null && $db != "") {
            $this->getConn()->exec('USE ' . $db);
                $sth = $this->getConn()->prepare("SELECT COUNT(*) FROM `document` WHERE `filename` = :filename");
                $sth->bindParam(':filename',$iFileName,PDO::PARAM_STR);
                $sth->execute();
                $result = $sth->fetchColumn();
                return $result;
        }
        else return false;
    }

    function GET_FIELDBOOK_COLLECTION_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `fbcollectionname` FROM `fbcollection`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

    function GET_CREW_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `crewname` FROM `crew`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

    function GET_FIELDBOOK_CREWS_BY_DOCUMENT_ID($collection,$iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT c.`crewname` FROM `documentcrew` AS dc LEFT JOIN  `crew` AS c ON dc.`crewID` = c.`crewID` WHERE dc.`docID` = ? ");
            $sth->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

}
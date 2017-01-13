<?php
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/
class FolderDBHelper extends DBHelper
{

    //NEED TESTING
    function SP_TEMPLATE_FOLDER_DOCUMENT_INSERT($collection, $iLibraryIndex, $iTitle,$iFileName, $iFileNameBack,
                                                                  $iFileNamePath,$iFileNameBackPath)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            if($iFileNameBack == "")
            {
                $iFileNameBack = null;
                $iFileNameBackPath = null;
            }
            if($iFileName == "")
            {
                $iFileName = null;
                $iFileNamePath = null;
            }
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FOLDER_DOCUMENT_INSERT(:libindex,:title,:fname,
            :fnameback,:fnamepath,:fnamebackpath)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(':libindex', $iLibraryIndex, PDO::PARAM_STR, 200);
            $call->bindParam(':title', $iTitle, PDO::PARAM_STR, 350);
            $call->bindParam(':fname', $iFileName, PDO::PARAM_STR, 80);
            $call->bindParam(':fnameback', $iFileNameBack, PDO::PARAM_STR, 80);
            $call->bindParam(':fnamepath', $iFileNamePath, PDO::PARAM_STR, 200);
            $call->bindParam(':fnamebackpath', $iFileNameBackPath, PDO::PARAM_STR, 200);

            /* EXECUTE STATEMENT */
            $ret = $call->execute();
            if($ret)
            {
                $select = $this->getConn()->query('SELECT LAST_INSERT_ID()');
                $ret = $select->fetch(PDO::FETCH_COLUMN);
                return $ret;
            }
            return 0;
        } else return false;
    }


    //NEED TESTING
    function SP_TEMPLATE_FOLDER_DOCUMENT_UPDATE($collection,$iDocID,$iLibraryIndex, $iTitle, $iInSubfolder, $iSubfolderComment,
                                                                  $iClassificationName, $iClassificationComment,
                                                                  $iNeedsInput,$iNeedsReview,$iComments,
                                                                  $iStartDate, $iEndDate)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FOLDER_DOCUMENT_UPDATE(:docID,:libindex,:title,:insub,:subcomment,:classname,:classcom,
            :input,:review,:comm,:startdate,:enddate)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(':docID', $iDocID, PDO::PARAM_INT, 200);
            $call->bindParam(':libindex', $iLibraryIndex, PDO::PARAM_STR, 200);
            $call->bindParam(':title', $iTitle, PDO::PARAM_STR, 350);
            $call->bindParam(':insub',$iInSubfolder,PDO::PARAM_INT,1);
            $call->bindParam(':subcomment',$iSubfolderComment,PDO::PARAM_STR,300);
            $call->bindParam(':classname',$iClassificationName,PDO::PARAM_STR,100);
            $call->bindParam(':classcom',$iClassificationComment,PDO::PARAM_STR,400);
            $call->bindParam(':input',$iNeedsInput,PDO::PARAM_INT,1);
            $call->bindParam(':review', $iNeedsReview, PDO::PARAM_INT, 1);
            $call->bindParam(':comm', $iComments, PDO::PARAM_STR,500);
            $call->bindParam(':startdate', $iStartDate, PDO::PARAM_STR, 10);
            $call->bindParam(':enddate', $iEndDate, PDO::PARAM_STR, 10);
            /* EXECUTE STATEMENT */
            $ret = $call->execute();
            return $ret;
        } else return false;
    }



    //NEEDS TESTING
    function SP_TEMPLATE_FOLDER_DOCUMENT_SELECT($collection, $iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FOLDER_DOCUMENT_SELECT(?,@oLibraryIndex,@oTitle,@oInSubfolder,@oSubfolderComment,@oClassification,@oClassificationComment,@oFileName,@oFileNameBack,@oNeedsInput,@oNeedsReview,@oFileNamePath,@oFileNameBackPath,@oComments,@oStartDate,@oEndDate)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oLibraryIndex AS LibraryIndex,@oTitle AS Title,@oInSubfolder AS InSubfolder,@oSubfolderComment AS SubfolderComment,@oClassification AS Classification,@oClassificationComment AS ClassificationComment,@oFileName AS FileName,@oFileNameBack AS FileNameBack,@oNeedsInput AS NeedsInput,@oNeedsReview AS NeedsReview,@oFileNamePath AS FileNamePath,@oFileNameBackPath AS FileNameBackPath,@oComments AS Comments,@oStartDate AS StartDate,@oEndDate AS EndDate');
            $result = $select->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else return false;
    }



    /**********************************************
     * Function: SP_TEMPLATE_FOLDER_DOCUMENTAUTHOR_INSERT
     * Description: GIVEN document ID & array of AuthorName, delete from documentauthor first, then call SP to select/insert from author table, then insert into documentauthor
     * Parameter(s):
     * collection (in string) - name of the collection
     * $iDocID (in Integer) - document ID
     * $iAuthorNameArray (in Array) - Array of Author names
     * Return value(s):
     * True if good, False if fail
     ***********************************************/

    function SP_TEMPLATE_FOLDER_DOCUMENTAUTHOR_INSERT($collection,$iDocID,$iAuthorNameArray)
    {
        //DELETE FROM DOCUMENTAUTHOR BEFORE INSERTING
        $ret = $this->TEMPLATE_FOLDER_DELETE_DOCUMENTAUTHOR($collection,$iDocID);
        if($ret) {
            if (!$ret)
                return false;
            //INSERT VALUES INTO DOCUMENTAUTHOR, IF VALUE DOES NOT EXIST IN AUTHOR TABLE, INSERT INTO AUTHOR FIRST
            foreach ($iAuthorNameArray as $iAuthor) {
                if($iAuthor != "") {
                    $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FOLDER_DOCUMENTAUTHOR_INSERT(:docID,:author)");
                    if (!$call) {
                        trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
                        return false;
                    }
                    $call->bindParam(':docID', ($iDocID), PDO::PARAM_INT);
                    $call->bindParam(':author', ($iAuthor), PDO::PARAM_STR);
                    $ret = $call->execute();
                    if ($ret == false)
                        return false;
                }
            }
            return true;
        }
        return false;
    }

    function TEMPLATE_FOLDER_DELETE_DOCUMENTAUTHOR($collection,$iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection));
        $db = $dbname['DbName'];
        $this->getConn()->exec('USE ' . $db);
        //DELETE FROM DOCUMENTAUTHOR BEFORE INSERTING
        $sth = $this->getConn()->prepare("DELETE FROM `documentauthor` WHERE `documentauthor`.`docID` = :docID");
        $sth->bindParam(':docID', ($iDocID), PDO::PARAM_INT);
        $ret = $sth->execute();
        if($ret)
            return true;
        return false;
    }

    function GET_FOLDER_AUTHORS_BY_DOCUMENT_ID($collection,$iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT a.`authorname` FROM `documentauthor` AS da LEFT JOIN  `author` AS a ON da.`authorID` = a.`authorID` WHERE da.`docID` = ? ");
            $sth->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }


    //FileName is unique on document table
    public function TEMPLATE_FOLDER_CHECK_EXIST_RECORD_BY_FILENAME($collection, $iFileName)
    {
        $db = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($db != null && $db != "") {
            $this->getConn()->exec('USE ' . $db);
            if(strpos($iFileName,"back") !== false)
                $sth = $this->getConn()->prepare("SELECT COUNT(*) FROM `document` WHERE `filenameback` = :filename");
            else $sth = $this->getConn()->prepare("SELECT COUNT(*) FROM `document` WHERE `filename` = :filename");
            $sth->bindParam(':filename',$iFileName,PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetchColumn();
            return $result;
        }
        else return false;
    }

    function GET_FOLDER_CLASSIFICATION_LIST($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `classificationname`,`classificationdescription` FROM `classification`");
            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

}
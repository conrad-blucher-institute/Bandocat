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
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FIELDBOOK_DOCUMENT_SELECT(?,@oLibraryIndex,@oBookName,@oPageType,@oPageNumber,@oComments,@oNeedsReview,@oFileName,@oTranscribed)");
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

    function SP_TEMPLATE_FIELDBOOK_DOCUMENT_UPDATE($collection, $iDocID, $iLibraryIndex, $iBookID, $iPageType, $iPageNumber, $iComments, $iNeedsReview)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection));
        $db = $dbname['DbName'];
        if ($db != null && $db != ""){
            $this->getConn()->exec('USE ' . $db);
            //Prepare Statement
            if($iPageNumber == "")
                $iPageNumber = null;
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FIELDBOOK_DOCUMENT_UPDATE(?,?,?,?,?,?,?)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, ($iDocID), PDO::PARAM_INT, 11);
            $call->bindParam(2, ($iLibraryIndex), PDO::PARAM_STR, 200);
            $call->bindParam(3, ($iBookID), PDO::PARAM_INT, 11);
            $call->bindParam(4, ($iPageType), PDO::PARAM_STR, 18);
            $call->bindParam(5, ($iPageNumber), PDO::PARAM_INT, 11);
            $call->bindParam(6, ($iComments), PDO::PARAM_STR, 40);
            $call->bindParam(7, ($iNeedsReview), PDO::PARAM_INT, 1);
            $ret = $call->execute();
            //Execute Statement
            if($ret)
                return true;

            else
                return print_r($call->errorInfo()[2]);
        }

    }


    function GET_FIELDBOOK_MAPKIND($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        $sth = $this->getConn()->prepare("SELECT `mapkindname` FROM `mapkind` ORDER BY `mapkindname` ASC");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_NUM);
        return $result;
    }

    //Function that queries the FIELDBOOKinventory book table and fetches bookname and bookID rows
    function GET_FIELDBOOK_BOOK($collection){
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE '. $dbname);
        $sth = $this->getConn()->prepare("SELECT `bookname`, `bookID`  FROM `book`");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_NUM);
    }

    function SP_TEMPLATE_FIELDBOOK_DOCUMENT_CHECK_EXIST_RECORD($collection, $iLibraryIndex)
    {
        $db = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($db != null && $db != "") {
            $this->getConn()->exec('USE ' . $db);
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_FIELDBOOK_DOCUMENT_CHECK_EXIST_RECORD(?, @oReturnValue)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, htmlspecialchars($iLibraryIndex), PDO::PARAM_STR, 200);
            $call->execute();
            $select = $this->getConn()->query('SELECT @oReturnValue');
            $ret = $select->fetch(PDO::FETCH_NUM);
            return (int)$ret[0];
        }
    }


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
}
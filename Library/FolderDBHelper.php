<?php
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/
class FolderDBHelper extends DBHelper
{

    //******************************* JOB FOLDER FUNCTIONS ******************//

    //NEED TESTING
    function SP_TEMPLATE_JOBFOLDER_DOCUMENT_INSERT_WITHOUT_AUTHOR($collection, $iLibraryIndex, $iTitle, $iInSubfolder, $iSubfolderComment,
                                                                  $iClassificationID, $iClassificationComment, $iFileName, $iFileNameBack,
                                                                  $iNeedsInput,$iNeedsReview, $iFileNamePath,$iFileNameBackPath,$iComments,
                                                                  $iStartDate, $iEndDate)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_JOBFOLDER_DOCUMENT_INSERT_WITHOUT_AUTHOR(:libindex,:title,:insub,:subcomment,:classID,:classcom,:fname,
            :fnameback,:input,:review,:fnamepath,:fnamebackpath,:comm,:startdate,:enddate)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(':libindex', $iLibraryIndex, PDO::PARAM_STR, 200);
            $call->bindParam(':title', $iTitle, PDO::PARAM_STR, 350);
            $call->bindParam(':insub',$iInSubfolder,PDO::PARAM_INT,1);
            $call->bindParam(':subcomment',$iSubfolderComment,PDO::PARAM_STR,300);
            $call->bindParam(':fname', $iFileName, PDO::PARAM_STR, 80);
            $call->bindParam(':fnameback', $iFileNameBack, PDO::PARAM_STR, 80);
            $call->bindParam(':input',$iNeedsInput,PDO::PARAM_INT,1);
            $call->bindParam(':review', $iNeedsReview, PDO::PARAM_INT, 1);
            $call->bindParam(':fnamepath', $iFileNamePath, PDO::PARAM_STR, 200);
            $call->bindParam(':fnamepathback', $iFileNameBackPath, PDO::PARAM_STR, 200);
            $call->bindParam(':comm', $iComments, PDO::PARAM_STR,500);
            $call->bindParam(':startdate', $iStartDate, PDO::PARAM_STR, 10);
            $call->bindParam(':enddate', $iEndDate, PDO::PARAM_STR, 10);

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
    function SP_TEMPLATE_JOBFOLDER_DOCUMENT_UPDATE_WITHOUT_AUTHOR($collection,$iDocID,$iLibraryIndex, $iTitle, $iInSubfolder, $iSubfolderComment,
                                                                  $iClassificationID, $iClassificationComment,
                                                                  $iNeedsInput,$iNeedsReview,$iComments,
                                                                  $iStartDate, $iEndDate)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_JOBFOLDER_DOCUMENT_UPDATE_WITHOUT_AUTHOR(:docID,:libindex,:title,:insub,:subcomment,:classID,:classcom,
            :input,:review,:comm,:startdate,:enddate)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(':docID', $iDocID, PDO::PARAM_INT, 200);
            $call->bindParam(':libindex', $iLibraryIndex, PDO::PARAM_STR, 200);
            $call->bindParam(':title', $iTitle, PDO::PARAM_STR, 350);
            $call->bindParam(':insub',$iInSubfolder,PDO::PARAM_INT,1);
            $call->bindParam(':subcomment',$iSubfolderComment,PDO::PARAM_STR,300);
            $call->bindParam(':input',$iNeedsInput,PDO::PARAM_INT,1);
            $call->bindParam(':review', $iNeedsReview, PDO::PARAM_INT, 1);
            $call->bindParam(':comm', $iComments, PDO::PARAM_STR,500);
            $call->bindParam(':startdate', $iStartDate, PDO::PARAM_STR, 10);
            $call->bindParam(':enddate', $iEndDate, PDO::PARAM_STR, 10);

            /* EXECUTE STATEMENT */
            return $call->execute();
        } else return false;
    }




    //NEEDS TESTING
    function SP_TEMPLATE_JOBFOLDER_DOCUMENT_SELECT_WITHOUT_AUTHOR($collection, $iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            /* PREPARE STATEMENT */
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_JOBFOLDER_DOCUMENT_SELECT_WITHOUT_AUTHOR(?,@oLibraryIndex,@oTitle,@oSubtitle,@oIsMap,@oMapScale,@oHasNorthArrow,@oHasStreets,@oHasPOI,@oHasCoordinates,@oHasCoast,@oFileName,@oFileNameBack,@oNeedsReview,@oComments,@oCustomerName,@oStartDate,@oEndDate,@oFieldBookNumber,@oFieldBookPage,@oReadability,@oRectifiability,@oCompanyName,@oType,@oMedium,@oAuthorName,@oFileNamePath,@oFileNameBackPath)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, htmlspecialchars($iDocID), PDO::PARAM_INT, 11);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oLibraryIndex AS LibraryIndex,@oTitle AS Title,@oSubtitle AS Subtitle,@oIsMap AS IsMap,@oMapScale AS MapScale,@oHasNorthArrow AS HasNorthArrow,@oHasStreets AS HasStreets,@oHasPOI AS HasPOI,@oHasCoordinates AS HasCoordinates,@oHasCoast AS HasCoast,@oFileName AS FileName,@oFileNameBack AS FileNameBack,@oNeedsReview AS NeedsReview,@oComments AS Comments,@oCustomerName AS CustomerName,@oStartDate AS StartDate,@oEndDate AS EndDate,@oFieldBookNumber AS FieldBookNumber,@oFieldBookPage AS FieldBookPage,@oReadability AS Readability,@oRectifiability AS Rectifiability,@oCompanyName AS CompanyName,@oType AS Type,@oMedium AS Medium,@oAuthorName AS AuthorName,@oFileNamePath AS FileNamePath,@oFileNameBackPath AS FileNameBackPath');
            $result = $select->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else return false;
    }



    //NEEDS TESTING
    /**********************************************
     * Function: SP_TEMPLATE_JOBFOLDER_DOCUMENTAUTHOR_INSERT
     * Description: CALL SP TO INSERT INTO DOCUMENTAUTHOR TABLE, INSERT INTO AUTHOR TABLE IF AUTHOR NOT EXISTED
     *              (SEE UPDATE_DOCUMENTAUTHOR FOR USAGE)
     * Parameter(s): $collection (in String) - Name of the collection
     *               $iDocID (in Int) - Document ID
     *               $iAuthorName (in String) - Author Name
     * Return value(s): AUTHOR ID IF SUCCESS, FALSE IF FAIL
     ***********************************************/
    function SP_TEMPLATE_JOBFOLDER_DOCUMENTAUTHOR_INSERT($collection,$iDocID,$iAuthorName)
    {
        $oAuthorID = null;
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);
            $call = $this->getConn()->prepare("CALL SP_TEMPLATE_JOBFOLDER_DOCUMENTAUTHOR_INSERT(?,?,@oAuthorID)");
            if (!$call)
                trigger_error("SQL failed: " . $this->getConn()->errorCode() . " - " . $this->conn->errorInfo()[0]);
            $call->bindParam(1, $iDocID, PDO::PARAM_INT);
            $call->bindParam(2, $iAuthorName, PDO::PARAM_STR, 150);
            /* EXECUTE STATEMENT */
            $call->execute();
            /* RETURN RESULT */
            $select = $this->getConn()->query('SELECT @oAuthorID');
            $oAuthorID = $select->fetch(PDO::FETCH_NUM)[0];
            return $oAuthorID;
        }
        return false;
    }

    //NEEDS TESTING
    /**********************************************
     * Function: UPDATE_DOCUMENTAUTHOR
     * Description: DELETE ROWS WITH documentID = $iDocID, THEN INSERT NEW ROWS INTO DOCUMENTAUTHOR FROM ARRAY OF AUTHORNAMES ($iAuthorNames)
     * Parameter(s): $collection (in String) - Name of the collection
     *               $iDocID (in Int) - Document ID
     *               $iAuthorNames (in array) - Author Names array
     * Return value(s): TRUE IF SUCCESS, FALSE IF FAIL
     ***********************************************/
    function UPDATE_DOCUMENTAUTHOR($collection,$iDocID,$iAuthorNames)
    {
        $ret = $this->DELETE_JOBFOLDER_DOCUMENTAUTHOR($collection,$iDocID);
        if($ret != false)
        {
            foreach($iAuthorNames as $a)
            {
                $aID = $this->SP_TEMPLATE_JOBFOLDER_DOCUMENTAUTHOR_INSERT($collection,$iDocID,$a);
                if($aID == false)
                    trigger_error("ERROR INSERTING INTO DOCUMENTAUTHOR, FUNCTION UPDATE_DOCUMENTAUTHOR");
            }
            return true;
        }
        return false;
    }

    //NEEDS TESTING
    /**********************************************
     * Function: DELETE_JOBFOLDER_DOCUMENTAUTHOR
     * Description: DELETE ROWS WHERE documentID = $iDocID IN DOCUMENTAUTHOR
     * Parameter(s): $collection (in String) - Name of the collection
     *               $iDocID (in Int) - Document ID
     * Return value(s): TRUE IF SUCCESS, FALSE IF FAIL
     ***********************************************/
    function DELETE_JOBFOLDER_DOCUMENTAUTHOR($collection,$iDocID)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        if ($dbname != null && $dbname != "") {
            $this->getConn()->exec('USE ' . $dbname);

            $sth = $this->getConn()->prepare("DELETE * FROM `documentauthor` WHERE `documentID` = ?");
            $sth->bindParam(1, $iDocID, PDO::PARAM_INT);
            $ret = $sth->execute();
            return $ret;
        }
        return false;
    }
    //NEEDS TESTING
    /**********************************************
     * Function: DELETE_JOBFOLDER_DOCUMENT
     * Description: DELETE  FROM document table and documentauthor table WHERE documentID = $iDocID
     * Parameter(s): $collection (in String) - Name of the collection
     *               $iDocID (in Int) - Document ID
     * Return value(s): TRUE IF SUCCESS, FALSE IF FAIL
     ***********************************************/
    function DELETE_JOBFOLDER_DOCUMENT($collection,$iDocID)
    {
        $ret = $this->DELETE_DOCUMENT($collection,$iDocID);
        if($ret != false)
            $ret = $this->DELETE_JOBFOLDER_DOCUMENTAUTHOR($collection,$iDocID);
        return $ret;
    }


}
<?php
require 'InterfaceTranscription.php';
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/
class IndicesDBHelper extends DBHelper implements InterfaceTranscription
{

    function GET_INDICES_MAPKIND($collection)
    {
        $dbname = $this->SP_GET_COLLECTION_CONFIG(htmlspecialchars($collection))['DbName'];
        $this->getConn()->exec('USE ' . $dbname);
        if ($dbname != null && $dbname != "") {
            $sth = $this->getConn()->prepare("SELECT `mapkindID`,`mapkindname` FROM `mapkind` ORDER BY `mapkindname` ASC");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_NUM);
            return $result;
        } else return false;
    }

    public function TRANSCRIPTION_ENTRY_SUBMIT()
    {

    }
    public function TRANSCRIPTION_ENTRY_UPDATE()
    {

    }
    public function TRANSCRIPTION_ENTRY_DELETE()
    {

    }



}
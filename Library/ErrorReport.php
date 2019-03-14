<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 2/11/2019
 * Time: 2:06 PM
 */
include_once ("DBHelper.php");
include_once ("Mailer.php");

class ErrorReport
{
    private $resolved;
    private $unresolved;
    private $mailer;
    private $db;
    private $entry;

    /**
     * ErrorReport constructor.
     */
    public function __construct()
    {
        $this->setDb();
        $this->setMailer();
    }

    /**
     * @return mixed
     */
    public function getResolved()
    {
        return $this->resolved;
    }

    /**
     * @param mixed $resolved
     */
    public function setResolved($resolved)
    {
        $this->resolved = $resolved;
    }

    /**
     * @return mixed
     */
    public function getUnresolved()
    {
        return $this->unresolved;
    }

    /**
     * @param mixed $unresolved
     */
    public function setUnresolved($unresolved)
    {
        $this->unresolved = $unresolved;
    }

    /**
     * @return mixed
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * @param mixed $entry
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return mixed
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param mixed $mailer
     */
    public function setMailer()
    {
        $this->mailer = new Mailer();
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb()
    {
        $this->db = new DBHelper();
    }

    public function fetchErrors()
    {

    }

    public function fetchResolved()
    {

    }

    public function reportError($entry)
    {
        $db = $this->getDb();

        // Getting database
        $dbname = "bandocatdb";
        $db->getConn()->exec('USE ' . $dbname);

        $data = array($entry["url"], $entry["errorMessage"], $entry["userid"], $entry["error"]);

        // Preparing statement
        $sth = $db->getConn()->prepare("INSERT INTO `errorreport` (`url`, `message`, `userID`, `errorID`) VALUES (?, ?, ?, ?)");

        // Execute
        if($sth->execute($data) == false)
        {
            return ["Error report could not be filed successfully.", false];
        }

        else
        {
            return ["Thank you for reporting this error, we wll let you know when it is resolved!", true];
        }
    }
}
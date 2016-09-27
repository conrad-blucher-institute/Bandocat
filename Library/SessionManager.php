<?php
class SessionManager
{
    private $userName;
    private $userID;
    private $role;

    /**
     * SessionManager constructor.
     * @param $userName
     * @param $userID
     * @param $role
     */
    public function __construct()
    {
        session_start();
        $this->userName = $_SESSION['username'];
        $this->userID = $_SESSION['userID'];
        $this->role = $_SESSION['role'];
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }


    //Super admin & admin
    public function isAdmin()
    {
        if($this->getRole() == 'Admin' || $this->getRole() == 'Super Admin')
            return true;
        return false;
    }

    //can write/edit review documents
    public function hasWritePermission()
    {
        if($this->getRole() == 'Admin' || $this->getRole() == 'Super Admin' || $this->getRole() == 'Writer')
            return true;
        return false;
    }

}
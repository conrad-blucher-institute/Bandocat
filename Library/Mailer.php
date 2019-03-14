<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 2/11/2019
 * Time: 2:15 PM
 */

class Mailer
{
    private $to;
    private $subject;
    private $headers;
    private $html;

    /**
     * Mailer constructor.
     */
    public function __construct()
    {
        $this->html = "";
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers)
    {
        $this->headers .= $headers;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function sendMail()
    {
        mail($this->getTo(), $this->getSubject(), $this->getHtml(), $this->getHeaders());
    }
}
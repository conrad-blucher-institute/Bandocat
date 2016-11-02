<?php
//this class is used to write/read log file (.txt)
class ErrorLogger
{
    private static $filename = "../../ErrorLog/log.txt";

    /**
     * ErrorLogger constructor.
     */
    public function __construct()
    {
        date_default_timezone_set("America/Chicago");
    }

    public function writeErrorLog($username,$collection,$docID,$error_array)
    {
        $str = date("Y-m-d h:i:sa") . $username . " @" . $collection . " - documentID:" . $docID . ": ";
        foreach($error_array as $item)
            $str = $str . $item . ",";
        $str = $str . PHP_EOL;
        $file = fopen(ErrorLogger::$filename, "a");
        fwrite($file,$str);
        fclose($file);
    }
}
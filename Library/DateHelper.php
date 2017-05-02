<?php


class DateHelper
{

    /**
     * DateHelper constructor.
     */
    public function __construct()
    {
    }

    /**********************************************
     * Function: splitDate
     * Description: helps to manipulate supplied dates into a different form
     * Parameter(s):
     * $date (in string) - date
     * Return value(s):
     * $result array containing separated date
     ***********************************************/
    function splitDate($date)
    {
        if($date == '' || $date == null)
        {
            return $ret = array(
                "Month" => "00",
                "Day" => "00",
                "Year" => "0000",
            );
        }
        $split = explode('/',$date);
        return $ret = array(
            "Month" => $split[0],
            "Day" => $split[1],
            "Year" => $split[2],
        );
    }
    /**********************************************
     * Function: mergeDate
     * Description: helps to manipulate supplied dates into a different form
     * Parameter(s):
     * $month (in string) - month
     * $day (in string) - day
     * $year (in string) - year
     * Return value(s):
     * $result date
     ***********************************************/
    function mergeDate($month,$day,$year)
    {
        if($month == "" || $month == null || $month == "0")
            $month == "00";
        if($day == "" || $day == null || $day == "0")
            $day == "00";
        if($year == "" || $year == null || $year == "0")
            $year = "0000";
        return  $month . "/" . $day . "/" . $year;
    }
}
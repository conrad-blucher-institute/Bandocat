<?php


class DateHelper
{

    /**
     * DateHelper constructor.
     */
    public function __construct()
    {
    }


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
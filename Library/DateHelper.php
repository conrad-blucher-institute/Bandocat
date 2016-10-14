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
        $split = explode('/',$date);
        return $ret = array(
            "Month" => $split[0],
            "Day" => $split[1],
            "Year" => $split[2],
        );
    }

}
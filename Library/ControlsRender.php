<?php

/**
 * Created by PhpStorm.
 * User: snguyen1
 * Date: 10/13/2016
 * Time: 1:38 PM
 */
class ControlsRender
{

    /**
     * Functions constructor.
     */
    public function __construct()
    {
    }


    //Render HTML controls
    function getDataList($array)
    {
        foreach($array as $item)
            echo '<option value="' . $item[0]. '">' . $item[0] . '</option>';
    }

    function GET_DDL_MONTH($input)
    {
        if($input == null || $input == '00' || $input == '0' || $input == '')
            echo '<option selected="selected" value="00">Month</option>';
        else echo '<option value="00">Month</option>';
        for($num = 1; $num <= 12; $num++)
        {
            if($num < 10)
                $num = '0' . $num;
            if($input == $num)
                echo '<option selected = "selected" >'.$input.'</option>';
            else
                echo '<option value="'.$num.'">'.$num.'</option>';
        }

    }

    function GET_DDL_DAY($input)
    {
        if($input == null || $input == '00' || $input == '0' || $input == '')
            echo '<option selected="selected" value="00">Day</option>';
        else echo '<option value="00">Day</option>';
        for($num= 1; $num<=31; $num++)
        {
            if($num < 10)
                $num = '0' . $num;
            if($input == $num)
                echo '<option selected = "selected" >'.$input.'</option>';
            else
                echo '<option value="'.$num.'">'.$num.'</option>';
        }
    }

    function GET_DDL_YEAR($input)
    {
        if($input == null || $input == '0000' || $input == '0' || $input == '')
            echo '<option selected="selected" value="0000">Year</option>';
        else echo '<option value="0000">Year</option>';
        $current = date("Y");
        for($num=1750; $num<=$current; $num++)
        {
            if($input == $num)
                echo '<option selected = "selected" >'.$input.'</option>';
            else
                echo '<option value="'.$num.'">'.$num.'</option>';
        }
    }

    //fetch from DB
    function GET_DDL($array,$selected)
    {
        echo '<option value="">Select</option>';
        foreach ($array as $item) {
            if ($selected == $item[0])
                echo '<option value="' . $item[0] . '" selected>' . $item[0] . '</option>';
            else echo '<option value="' . $item[0] . '">' . $item[0] . '</option>';
        }
    }
    //FETCH FROM PHP ARRAY
    function GET_DDL2($array,$selected)
    {
        echo '<option value="">Select</option>';
        foreach ($array as $item) {
            if ($selected == $item)
                echo '<option value="' . $item . '" selected>' . $item . '</option>';
            else echo '<option value="' . $item . '">' . $item . '</option>';
        }
    }

    //Render collection dropdown
    function GET_DDL_COLLECTION($array,$selected)
    {
        echo '<option value="">Select</option>';
        foreach ($array as $item) {
            if ($selected == $item)
                echo '<option value="' . $item['name'] . '" selected>' . $item['displayname'] . '</option>';
            else echo '<option value="' . $item['name'] . '">' . $item['displayname'] . '</option>';
        }
    }
}
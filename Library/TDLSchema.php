<?php

//This class provides functions to convert document schema from internal BandoCat to TDL
class TDLSchema
{
    public $mimetype;
    public $rightsholder = "Special Collections and Archives, Mary and Jeff Bell Library, Texas A&M University-Corpus Christi. Conrad Blucher Institute, Texas A&M University-Corpus Christi.";
    public $rightsstatement = "This material is made available for use in research, teaching, and private study, pursuant to U.S. Copyright law. The user assumes full responsibility for any use of the materials, including but not limited to, infringement of copyright and publication rights of reproduced materials. Any materials used should be fully credited with the source. Permission for publication of GeoTIFF, KMZ, and CSV files must be secured with the Conrad Blucher Institute. Permission for publication of scanned images must be secured with the Head of Special Collections and Archives.";

    function __construct($mimetype = null)
    {
        if($mimetype == null)
            $this->mimetype = "image/tiff";
        else $this->mimetype = $mimetype;
    }

    function convertSchema($templateID,$doc,$isPOST = true)
    {
        switch($templateID)
        {
            case "1": //map
                $array =  $this->convertMapSchema($doc);
                break;
            case "2":
                break;
            case "3":
                break;
            case "4":
                break;
            default:
                return false;
        }

        if($isPOST) //POST
        {
            $output["metadata"] = $array;
            return json_encode($output);
        }
        else return json_encode($array); //PUT
    }


    //$document is an associative array
    function convertMapSchema($doc)
    {
        //preprocess
        if($doc["FieldBookNumber"] == 0)
            $doc["FieldBookNumber"] = "";
        if($doc["FieldBookPage"] == 0)
            $doc["FieldBookPage"] = "";


        $output = array(array("key" => "dc.contributor.author", "value" => $doc["AuthorName"]),
            array("key" => "dc.title", "value" => $doc["Title"]),
            array("key" => "dc.coverage.temporal","value" => $this->mergeStrings($this->convertDate($doc["StartDate"]),$this->convertDate($doc["EndDate"]))),
            array("key" => "dc.type","value" => $doc["Type"]),
            array("key" => "dc.contributor","value" => $doc["CompanyName"]),
            array("key" => "dc.relation.isbasedon","value" => $this->mergeStrings($doc["FieldBookNumber"],$doc["FieldBookPage"])),
            array("key" => "dc.format.medium","value" => $doc["Medium"]),
            array("key" => "dc.format.mimetype","value" => $this->mimetype),
            array("key" => "dc.rights.holder","value" => $this->rightsholder),
            array("key" => "dc.rights","value" => $this->rightsstatement),
            array("key" => "mc.subtitle","value" => $doc["Subtitle"]),
            array("key" => "mc.mapscale","value" => $doc["MapScale"]),
            array("key" => "mc.note","value" => $doc["Comments"]),
            array("key" => "mc.customer","value" => $doc["CustomerName"]),
            array("key" => "mc.collection","value" => $doc["Collection"])
        );

        //have to put info inside a "metadata" key
//        $output["metadata"] = $temp;
//        return json_encode($output);
        return $output;
    }

    //$document is an associative array
    function convertJobFolderSchema($doc)
    {
        //preprocess

        $output = array(array("key" => "dc.contributor.author", "value" => $doc["AuthorName"]),
            array("key" => "dc.title", "value" => $doc["Title"]),
            array("key" => "dc.coverage.temporal","value" => $this->mergeStrings($this->convertDate($doc["StartDate"]),$this->convertDate($doc["EndDate"]))),
            array("key" => "dc.type","value" => $doc["Type"]),
            array("key" => "dc.contributor","value" => $doc["CompanyName"]),
            array("key" => "dc.relation.isbasedon","value" => $this->mergeStrings($doc["FieldBookNumber"],$doc["FieldBookPage"])),
            array("key" => "dc.format.medium","value" => $doc["Medium"]),
            array("key" => "dc.format.mimetype","value" => $this->mimetype),
            array("key" => "dc.rights.holder","value" => $this->rightsholder),
            array("key" => "dc.rights","value" => $this->rightsstatement),
            array("key" => "mc.subtitle","value" => $doc["Subtitle"]),
            array("key" => "mc.mapscale","value" => $doc["MapScale"]),
            array("key" => "mc.note","value" => $doc["Comments"]),
            array("key" => "mc.customer","value" => $doc["CustomerName"]),
            array("key" => "mc.collection","value" => $doc["Collection"])
        );

        return $output;
        //have to put info inside a "metadata" key
//        $output["metadata"] = $temp;
//        return json_encode($output);
    }



    //convert date from MM/DD/YYYY to MM-DD || MM-YYYY || MM-DD-YYYY
    //return "" if date is 00/00/0000
    function convertDate($date)
    {
        if($date == "00/00/0000")
            return "";
        $temp = str_replace("/","-",$date);
        $temp = str_replace("00","",$temp);
        $temp = str_replace("--","-",$temp);

        if(strrpos($temp,"-") == strlen($temp) - 1 )
            $temp = substr($temp,0,-1);
        return $temp;
    }

    function mergeStrings($str1,$str2)
    {
        $temp1 = str_replace(" ","",$str1);
        $temp2 = str_replace(" ","",$str2);
        if($temp1 == "" && $temp2 != "")
            return $temp2;
        if($temp2 != "" && $temp2 == "")
            return $temp1;
        if($temp1 == "" && $temp2 == "")
            return "";
        if($temp1 != "" && $temp2 != "")
            return $temp1 . "/" . $temp2;
    }

    function getHeaderValueFromKey($header,$key)
    {
        $temp = strpos($header,$key,0);
        $temp2 = strpos($header,":",$temp) + 1;
        $temp3 = strpos($header,",",$temp2);

        return str_replace('"',"",substr($header,$temp2,$temp3 - $temp2));
    }

}
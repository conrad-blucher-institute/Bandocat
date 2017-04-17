<?php
//This class provides functions to convert document schema from internal BandoCat to TDL
class TDLSchema
{
    public $rightsholder1 = "Special Collections and Archives, Mary and Jeff Bell Library, Texas A&M University-Corpus Christi.";
    public $rightsholder2 = "Conrad Blucher Institute, Texas A&M University-Corpus Christi.";
    public $rightsstatement = "This material is made available for use in research, teaching, and private study, pursuant to U.S. Copyright law. The user assumes full responsibility for any use of the materials, including but not limited to, infringement of copyright and publication rights of reproduced materials. Any materials used should be fully credited with the source. Permission for publication of GeoTIFF, KMZ, and CSV files must be secured with the Conrad Blucher Institute. Permission for publication of scanned images must be secured with the Head of Special Collections and Archives.";

    function __construct()
    {
    }

    function convertSchema($templateID,$doc,$isPOST = true)
    {
        switch($templateID)
        {
            case "1": //map
                $array =  $this->convertMapSchema($doc);
                break;
            case "2":
                $array =  $this->convertJobFolderSchema($doc);
                break;
            case "3":
                $array = $this->convertFieldBookSchema($doc);
                break;
            case "4":
                break;
            default:
                return false;
        }

        //POST = PUBLISH
        if($isPOST) //POST
        {
            $output["metadata"] = $array;
            return json_encode($output);
        }
        else return json_encode($array); //PUT = UPDATE
    }


    //$document is an associative array
    function convertMapSchema($doc)
    {
        //preprocess
//        if($doc["FieldBookNumber"] == 0)
//            $doc["FieldBookNumber"] = "";
//        if($doc["FieldBookPage"] == 0)
//            $doc["FieldBookPage"] = "";

        //only update a field when it has data (not empty)

        $numberOfPages = "1 page";
        if($doc["FileNameBack"] != "")
            $numberOfPages = "2 pages";

        $output = array();
        $this->addField($output,"dc.identifier.other",str_replace("-_","-",$doc['LibraryIndex']));
        $this->addField($output,"dc.title",str_replace("-_","-",$doc['Title']));
        $this->addField($output,"dc.coverage.temporal",$this->mergeStrings($this->convertDate($doc["StartDate"]),$this->convertDate($doc["EndDate"])));
        $this->addField($output,"dc.type",$doc["Type"]);
            //array("key" => "dc.relation.isbasedon","value" => $this->mergeStrings($doc["FieldBookNumber"],$doc["FieldBookPage"])),
        $this->addField($output,"dc.format.extent",$numberOfPages);
        $this->addField($output,"dc.format.medium",$doc["Medium"]);
        $this->addField($output,"dc.format.mimetype","image/jpeg");
        $this->addField($output,"dc.rights.holder",$this->rightsholder1);
        $this->addField($output,"dc.rights.holder",$this->rightsholder2);
        $this->addField($output,"dc.rights",$this->rightsstatement);
        $this->addField($output,"mc.subtitle",$doc["Subtitle"]);
        $this->addField($output,"mc.mapscale",$doc["MapScale"]);
        $this->addField($output,"mc.note",$doc["Comments"]);
        $this->addField($output,"mc.customer",$doc["CustomerName"]);
        $this->addField($output,"mc.collection",$doc["TDLCollection"]);
        $this->addField($output,"dc.identifier.citation",$doc['TDLcitation']);
        $this->addField($output,"mc.collectionid",$doc['TDLnumber']);
        $this->addField($output,"mc.collection.sub",$doc['TDLsubgroup']);

        $this->addField($output,"dc.contributor",$doc["TDLAuthorName"]);
        $this->addField($output,"dc.contributor",$doc["CompanyName"]);


        //need FieldBook and Job Folder relation (dc.relation title) and PDF (dc.relation.url)

        return $output;
    }

    //$document is an associative array
    //job folder page
    function convertJobFolderSchema($doc)
    {
        //preprocess

        $numberOfPages = "1 page";
        if($doc["FileNameBack"] != "")
            $numberOfPages = "2 pages";

        $output = array(
            array("key" => "dc.identifier.other", "value" => str_replace("-_","-",$doc['LibraryIndex'])),
            array("key" => "dc.title", "value" => str_replace("-_","-",$doc['Title'])),
            array("key" => "dc.coverage.temporal","value" => $this->mergeStrings($this->convertDate($doc["StartDate"]),$this->convertDate($doc["EndDate"]))),
            //array("key" => "dc.relation.isbasedon","value" => $this->mergeStrings($doc["FieldBookNumber"],$doc["FieldBookPage"])),
            array("key" => "dc.format.extent","value" => $numberOfPages),
            array("key" => "mc.classification","value" => $doc['Classification']),
            array("key" => "mc.classification.note","value" => $doc['ClassificationComment']),
            array("key" => "dc.format.mimetype","value" => ""),
            array("key" => "dc.rights.holder","value" => $this->rightsholder),
            array("key" => "dc.rights","value" => $this->rightsstatement),
            array("key" => "mc.note","value" => $doc["Comments"]),
            array("key" => "mc.collection","value" => $doc["TDLCollection"]),
            array("key" => "dc.identifier.citation","value" => $doc['TDLcitation']),
            array("key" => "mc.collectionid","value" => $doc['TDLnumber']),
            array("key" => "mc.collection.sub","value" => $doc['TDLsubgroup'])
        );
        //adding multiple authors
        foreach($doc["Authors"] as $author)
            array_push($output,array("key" => "dc.contributor.author","value" => $author[0]));

        //related fieldbook (PDF URL) and title
        //related Map (title and URL)
        return $output;
    }

    function convertFieldBookSchema($doc)
    {
        //extend: number of page in what??? Book or number of scan???

        //preprocess
        $output = array();

        $this->addField($output,"dc.identifier.other",str_replace("-_","-",$doc['LibraryIndex']));
        $this->addField($output,"dc.relation.ispartof",$doc["BookTitle"]);
        $this->addField($output,"mc.collection",$doc["Collection"]);
        $this->addField($output,"dc.coverage.temporal",$this->mergeStrings($this->convertDate($doc["StartDate"]),$this->convertDate($doc["EndDate"])));
            //array("key" => "dc.relation.isbasedon","value" => $this->mergeStrings($doc["FieldBookNumber"],$doc["FieldBookPage"])),
        $this->addField($output,"mc.pagenumber",$doc["IndexedPage"]);
        $this->addField($output,"dc.relation.ispartof",$doc['JobNumber']);
        $this->addField($output,"dc.relation.ispartof",$doc['JobTitle']);
        $this->addField($output,"dc.format.mimetype","image/jpeg");
        $this->addField($output,"dc.rights.holder",$this->rightsholder);
        $this->addField($output,"dc.rights",$this->rightsstatement);
        $this->addField($output,"mc.note",$doc["Comments"]);
        $this->addField($output,"mc.collection",$doc["TDLCollection"]);
        $this->addField($output,"dc.identifier.citation",$doc['TDLcitation']);
        $this->addField($output,"mc.collectionid",$doc['TDLnumber']);
        $this->addField($output,"mc.collection.sub",$doc['TDLsubgroup']);

        //adding multiple crew members
        foreach($doc["Crews"] as $crew)
            array_push($output,array("key" => "dc.contributor.author","value" => $crew[0]));
        //missing: relation to Map and Job Folder (URL to the PDF and title)

        return $output;
    }

    //ignore if the field is empty
    function addField(&$arr,$key,$val)
    {
        if(trim($val) !== "")
            array_push($arr,array("key" => $key,"value" => $val));
        else //empty
        {
            switch($key)
            {
                case "dc.coverage.temporal": //start date & end date
                    array_push($arr,array("key" => $key,"value" => "undated"));
                    break;
                default:break;
            }
        }
    }


    //convert date from MM/DD/YYYY
    //NEW: Convert to YYYY
    //return "" if date is 00/00/0000
    function convertDate($date)
    {
        if($date == "00/00/0000")
            return "";
        $dateArr = explode("/",$date); // [0] = MM, [1] = DD, [2] = YYYY
        if($dateArr[2] == "0000" )
            return "";
        else
        {
            if($dateArr[0] == "00" && $dateArr[1] == "00") return $dateArr[2]; //return YYYY
            if($dateArr[1] == "00") return $dateArr[2] . "-" . $dateArr[0]; //return YYYY-MM
            return $dateArr[2] . "-" . $dateArr[0] . "-" . $dateArr[1]; //return YYYY-DD-MM
        }
        return $dateArr[2] . "-" . $dateArr[0] . "-" . $dateArr[1]; //return YYYY-DD-MM
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

    //TODO:
    //Job Folder (whole)
    //Field Book (whole)
    //Update Schema

}
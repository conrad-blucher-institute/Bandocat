<?php
function getIsoWeeksInYear($year) {
    $date = new DateTime;
    $date->setISODate($year, 53);
    return ($date->format("W") === "53" ? 53 : 52);
}


require '../../Library/DBHelper.php';
$DB = new DBHelper();
$collections = $DB->GET_COLLECTION_FOR_DROPDOWN();
$year =  $_GET['year'];
$week_upperbound = getIsoWeeksInYear($year);
if($year == date("Y"))
    $week_upperbound = date("W");

$array = array();
foreach($collections as $col) {
    $counter = 0;
    for($i = 0; $i < $week_upperbound; $i++)
    {
        $wk = $i + 1;
        $temp[$i] = array('week'=>strval($wk),'count'=>'0');
    }
    $merge_arr = array_merge($temp,$DB->GET_WEEKLYREPORT($year,$col['collectionID']));

    $unique_arr = array();
    foreach($merge_arr as $key => $val){
        if(!array_key_exists('week', $unique_arr)){
            $unique_arr[$val['week']] = $val;
        }
    }
    $subarray = array();
    foreach($unique_arr as $ua) {
        array_push($subarray,$ua['count']);
    }
    array_push($array,$subarray);
}
echo json_encode($array);

?>
<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin() == false) {
    header('Location: ../../');
}
require '../../Library/IndicesDBHelper.php';
$us =$_POST['mapkind'];
$collection='mapindices';
$IDB= new IndicesDBHelper();
$ola = $IDB->GET_INDICES_MAPKIND($collection);
$split = json_encode($ola);
$UpperArray = strtoupper($split);
$UpperUs = strtoupper($us);

if (strpos($UpperArray, $UpperUs) !== false) {
    $message = "Error! Map Kind '".$us."' exists";
    echo $message;
}
else{
    $IDB->INSERT_INDICES_MAPKIND($us, $collection);
    $message = $us." Added to MapKind";
    echo $message;
}

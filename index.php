<?php
include 'Library/SessionManager.php';
$session = new SessionManager();

//if no pagekey to redirect url
if(!isset($_GET['pagekey'])) {
    if ($session->getLoggedIn() == true)
        header('Location: Forms/Main/');
    else header('Location: Forms/Login/');
}


//routing
//when receiving 'col' and 'doc' parameters, redirect to review page of 'col' & 'doc'
if(isset($_GET['col']) && isset($_GET['doc']) && $_GET['pagekey']=='review')
{
    require 'Library/DBHelper.php';
    $DB = new DBHelper();
    $template = $DB->GET_COLLECTION_TEMPLATE($_GET['col']);
    header('Location: ./Templates/' . $template[dir] . '/review.php?doc=' . $_GET[doc] . '&col=' . $_GET[col]);
}

<?php
include '../../Library/SessionManager.php';
require '../../Library/DBHelper.php';
require '../../Library/AnnouncementDBHelper.php';
$session = new SessionManager();
$announcement = new AnnouncementDBHelper();
$announcementData = $announcement->GET_ANNOUNCEMENT_DATA();
$announcementLenght = count($announcementData);
$announcementJSON = json_encode($announcementData);
$userID = $session->getUserID();
$admin = $session->isAdmin();
?>
<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Welcome to BandoCat!</title>

    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <link rel="stylesheet" type="text/css" href="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.js"></script>
    <style>
        /* Header/logo Title */
        .header {
            padding: 10px 10px 5px;
            text-align: center;
            background: #0079C2;
            color: white;
        }

        .header > p
        {
            font-size: 18px;
        }

        .title {

        }

        #version {
            text-align: right;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
        }

        #version > div {

        }
        .main-content
        {
            display: flex;
            height: 80vh;
            justify-content: space-between;
            flex-wrap: wrap;
            align-content: stretch;
            /*background-color: DodgerBlue;*/
            overflow-y: auto;
            margin: 0;
            padding: 0;
        }

        .card
        {
            //background-color: #f1f1f1;
            width: 43vh;
            height: 75vh;
            margin: 10px;
            text-align: center;
            font-size: 30px;
            border: 5px double silver;
            position: relative;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        .card-body {
            padding: 2px;
            text-align: center;
            font-size: 15px;
        }

        .card-header
        {
            padding-left: 5px;
            padding-right: 5px;
            text-align: center;
            background-color: silver;
            color: white;
        }

        .card-footer {
            position: absolute;
            width: 100%;
            bottom: 0;
            background-color: silver;
            color: white;
            font-size: .60em;
        }

        a{
            color: black;
        }

        a:link {
            text-decoration: none;
        }

        a:visited {
            text-decoration: none;
        }

        h3
        {
            padding: 0px;
            margin: 0px;
            font-size: 30px;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        /***************************************************/
        .navbar {
            overflow: hidden;
            background-color: #0079C2;
            font-family: Arial, Helvetica, sans-serif;
        }

        .navbar a {
            float: left;
            font-size: 16px;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .dropdown {
            float: left;
            overflow: hidden;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 16px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover, .dropdown:hover .dropbtn {
            background-color: silver;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        /*************************************************************/
        .card-image
        {
            width: 25%;
            height: 25%;
        }
    </style>

</head>
<!-- HTML BODY -->
<body>
<div class="header">
    <div class="navbar">
        <div class="dropdown">
            <button class="dropbtn">Profile
                <i class="fa fa-gear"></i>
            </button>
            <div class="dropdown-content">
                <!--<a href="#">Profile</a>
                <a href="#">Settings</a>-->
                <a href="../Logout/">Logout</a>
            </div>
        </div>
        <p style="float:right;">Version 2.5</p>
    </div>
    <h1 class="title">Bandocat</h1>
    <p>Welcome <b><?php echo $session->getUserName(); ?></b>!</p>
</div>
<div class="main-content">

    <a href="../Main/">
        <div class="card">
            <div class="card-header">
                <h3>Bandocat</h3>
            </div>
            <div class="card-body">
                <p>Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
                <p>Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
            </div>
            <div class="card-footer">
                Click here to visit Bandocat!
            </div>
        </div>
    </a>

    <a href="../../../TonyAmos/Forms/TonyAmosCollection.php">
        <div class="card">
            <div class="card-header">
                <h3>Tony Amos</h3>
            </div>
            <div class="card-body">
                Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
            </div>
            <div class="card-footer">
                Click here to visit Tony Amos!
            </div>
        </div>
    </a>

    <a href="#">
        <div class="card">
            <div class="card-header">
                <h3>2D Rachel</h3>
            </div>
            <div class="card-body">
                Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
            </div>
            <div class="card-footer">
                Click here to visit 2D Rachel!
            </div>
        </div>
    </a>

    <a href="#">
        <div class="card">
            <div class="card-header">
                <h3>Collection 4</h3>
            </div>
            <div class="card-body">
                Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
            </div>
            <div class="card-footer">
                Click here to visit Collection 4!
            </div>
        </div>
    </a>

    <a href="#">
        <div class="card">
            <div class="card-header">
                <h3>Collection 5</h3>
            </div>
            <div class="card-body">
                Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
            </div>
            <div class="card-footer">
                Click here to visit Collection 5!
            </div>
        </div>
    </a>

    <a href="#">
        <div class="card">
            <div class="card-header">
                <h3>Collection 6</h3>
            </div>
            <div class="card-body">
                Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
            </div>
            <div class="card-footer">
                Click here to visit Collection 6!
            </div>
        </div>
    </a>
</div>
</body>
</html>





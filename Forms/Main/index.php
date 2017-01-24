<?php
    include '../../Library/SessionManager.php';
    $session = new SessionManager();
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

    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.js"></script>
    <script type="text/javascript" src="Greetings.js"></script>
</head>
<!-- HTML BODY -->
<body>

<!-- TABLE FOR LAYOUT OF PAGE -->
<table id="thetable">
    <tr>
        <th class="menu_left" id="thetable_left">
            <?php include '../../Master/header.php'?>
        </th>
        <th class="tg-chpy" colspan="2">
            <span id= "lblUsername" class="Username"><a href="../../Forms/AccountSettings"><?php echo $session->getUserName(); ?></a></span>,&nbsp;<span id="Time_Day"></span><span id="Greetings"></span>
        </th>
    </tr>
    <tr style="height: 630px">
        <td class="menu_left" id="thetable_left">
            <?php include '../../Master/sidemenu.php' ?>
        </td>
        <td class="tg-zhyu"><h2>BandoCat</h2></td>
        <td class="tg-0za1"><h2>Announcements</h2>
            <input class="bluebtn" type="button" onclick="crAnno()" value="CREATE ANNOUNCMENT" style="margin-left: 18%; font-size: 0.775vw;">
            <input class="bluebtn" type="button" onclick="crMeet()" value="CREATE MEETING" style="margin-left: 25%; font-size: 0.775vw;">
            <div id="post"></div>
        </td>
    </tr>
</table>

<?php include '../../Master/footer.php'; ?>
</body>
<!-- Funny Greeting -->
<script>
    /*Program that will get the time in hours from the Date function. Then, a conditional statement will determine what
    time of the day it is; morning or afternoon*/
        var d = new Date();
        var n = d.getHours();
        if(n >= 12)
        {
            document.getElementById("Time_Day").innerHTML = "Good Afternoon! ";
        }

        else{
            document.getElementById("Time_Day").innerHTML= "Good Morning! ";
        }
    /*Program that will obtain a random number from the random function. Then, it will multiply it by the length of the
    Greetings javascript array, which is saved in an external document. Finally the integer number retrieved is used to
    select an index with a greeting that will be displayed at the top of the page.*/
        var Random = Math.random();
        var Generic_Number = Random * (Greetings.length);
        var Integer = parseInt(Generic_Number);
        document.getElementById("Greetings").innerHTML = Greetings[Integer];

        /*Create announcement on button click
        create a div
        get the length of divs inside the post div*/
    identifier = [];
        function crAnno() {
            annoLenght = $("#post > div").length;
            console.log(annoLenght);
            if(annoLenght < 1) {
                $("#post").append("<div class='anno'>" +
                    "<h2>Announcement</h2>" +
                    "<textarea style='height: 100px; width: 180px'></textarea>"+
                    "</div>");
            }
        }

        function crMeet() {
            meetLenght = $("#post > div").length;
            if(meetLenght < 1) {
                $("#post").append("<div class='meet'>" +
                    "<h2>Meeting</h2>" +
                    "<p>Date: <input type='text' id='date'></p>"+
                    "</div>");
            }

            $(function () {
                $("#date").datepicker();
            });
        }

</script>
<!-- Page Style -->
<style>

    #lblUsername{
        text-decoration: underline;
    }

    .tg-0za1:hover{
        opacity: 0.95;
        box-shadow: none;
        outline: solid;
        outline-width: 1.0px;
        outline-color: #A8A8A8;
    }
    .tg-zhyu:hover{
        opacity: 0.95;
        box-shadow: none;
        outline: solid;
        outline-width: 1.0px;
        outline-color: #A8A8A8;
    }
    h2{
        font-size: 15px !important;
        font-family: sans-serif;
    }
    nav{margin: 12px 0px 40px 16px !important;}
    .tg  {border-collapse:collapse;border-spacing:0; width: 100%}
    #thetable td{font-family:Arial, sans-serif;font-size:14px; padding-top: 0px; padding-left: 0px; overflow:hidden;word-break:normal;}
    #thetable th{width: 1%}
    #thetable .tg-chpy{font-size:20px;font-family:serif !important;;text-align:right;vertical-align:top; width: 77%; background-color: #f1f1f1; border-radius: 2px;  box-shadow: 0px 0px 3px #0c0c0c;}
    #thetable .tg-0za1{font-size:13px;font-family:serif !important;;vertical-align:top; background-color: #f1f1f1; border-radius: 2px; border-width: 0px; box-shadow: 0px 0px 2px #0c0c0c;}
    #thetable .tg-yw4l{vertical-align:top; border-style: none}
    #thetable .tg-zhyu{font-size:13px;font-family:serif !important;;vertical-align:top; background-color: #f1f1f1; border-radius: 2px; border-width: 0px; box-shadow: 0px 0px 2px #0c0c0c; width: 55%}
    a:hover {color: white !important; decoration: underline}

    .anno {
         text-align: center;
         background-color: white;
         width: 80% !important;
         height: 150px !important;
         margin-top: 5%;
         margin-left: 10%;
         border-radius: 5%;
         box-shadow: 0px 0px 8px 1px;
     }

    .meet {
        text-align: center;
        background-color: white;
        width: 80% !important;
        height: 150px !important;
        margin-top: 5%;
        margin-left: 10%;
        border-radius: 5%;
        box-shadow: 0px 0px 8px 1px;
    }

</style>

</html>





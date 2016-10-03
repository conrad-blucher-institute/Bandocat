<!--This page allows users to login-->
<!--If login successfully, login form will disappear, user can see Main Menu-->
<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Welcome to BandoCat!</title>
    <link rel="shortcut icon" type="image/png" href="../../Images/favicon.ico"/>
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <link rel="stylesheet" type="text/css" href="footer-distributed.css">
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
<style>

    *{margin:0;padding:0;}
    html, body {height: 100%;}
    body{min-height:600px;}

    #login_container{
        top:80px;
        position: relative;
        padding:5px;
        height:80%;
        margin:0 auto;
        width:400px;
        line-height:40px;
    }
    table{width:100%;}
    td{padding:0;}
    tr{line-height:50px;}

    label{
        color:#000;
        font-size:21px;
        line-height:40px !important;

    }
    input[type="password"],input[type="text"]{height:30px;font-size:18px}

    input[type="submit"]{
        padding:6px 14px 6px 14px;
        font-size:18px;
        color: #ffffff;
        border-style: solid;
    }
    input[type="submit"]:hover
    {
        cursor: pointer;
        border-style:inset;
    }


    #txt_error{color:red;line-height:20px;}

     .Copyrights{position: relative;
         width: auto;
         float: right;
         text-align: right;
         font-family: serif;
         margin-bottom: 0px;
         bottom: 0%;}

    .Logo{position: relative;
        top: 10%;
        width: 75%;
        height: 75%;}
    li{
        margin-top:1%;
    }
    ul{
        padding-bottom:10px !important;
        padding-left:20px !important;
    }
    a:hover{
        color: #0c0c0c !important;
    }
    #footer{
        position: relative;
        margin-top: -210px; /* negative value of footer height */
        height: 210px;
        clear:both;
    }
    #wrap {min-height: 100%;}
    #main {overflow:auto;
        padding-bottom: 210px;}  /* must be same height as the footer */

</style>
</head>
<body onresize="onResizeWindow()">
<div id="wrap">
    <div id="main">
    <div id="login_container">
        <form id="frm_auth" name="frm_auth" method="post">
            <div style="width: 100%;text-align: center"><img width="400px" src="../../Images/Logos/bando-logo-medium.png" class="unselectable"/>
            </div>
                <table width="100%" style="text-align:center">
                <tr>
                    <td><label class="unselectable" for="username">Username</label>
                        </td>
                    <td>
                        <input type="text" id="txtUsername" name="username" required>
                    </td>
                </tr>
                <tr>
                    <td><label class="unselectable" for="password">Password</label></td>
                    <td>
                        <input type="password" id="txtPassword" name="password" required>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name = "login" id="btnSubmit" value="Login" class="bluebtn"/>
                    </td>
                </tr>
                    <tr>
                        <td colspan="2"><p id="txt_error"></p></td>
                    </tr>
            </table>
        </form>
    </div>
</div>
</div>
<footer id="footer" class="footer-distributed">

    <div class="footer-right">
        <div>
            <a href="http://spatialquerylab.com/" target="_blank" ><img class="Logo" src="../../Images/Query.PNG" title="Spatial Query Lab Website"/></a>
            <a href="http://spatialquerylab.com/" target="_blank"><img class="Logo" src="../../Images/facebook-small.png"/></a>
            <a href="http://spatialquerylab.com/" target="_blank"><img class="Logo" src="../../Images/twitter_35x35.png"/></a>
        </div>
    </div>

    <div class="footer-left">

        <p class="footer-links" style="white-space: nowrap"> Learn more about BandoCat at the
            <a href="http://spatialquerylab.com/" target="_blank"><u>Spatial {Query} Lab</u></a>
        </p>

    </div>

    <p>Contact Information:</p>
    <ul>
        <li>Website Admin: <a href="mailto:snguyen1@islander.tamucc.edu" target="_top">snguyen1@islander.tamucc.edu</a></li>
        <li>Project Manager: <a href="mailto:richard.smith@tamucc.edu" target="_top">richard.smith@tamucc.edu</a></li>
    </ul>
        <p class = "Copyrights">Copyright <span id="CBI_Year"></span> Conrad Blucher Institute for Surveying and Science  </p>
</footer>
</body>
<script>

    //this function resize element's width, height or hide/show elements when the browser resizes
    function onResizeWindow()
    {
        var theWidth = $(window).width();
        if(theWidth < 850)
            $("p.Copyrights").hide();
        else $("p.Copyrights").show();
    }

    $( document ).ready(function() {
        onResizeWindow();
        var date = new Date();
        document.getElementById("CBI_Year").innerHTML = date.getFullYear();

        /* attach a submit handler to the form */
        $('#frm_auth').submit(function (event) {
            $('#txt_error').text("");
            /* stop form from submitting normally */
            event.preventDefault();
            /* Send the data using post */
            $.ajax({
                type: 'post',
                url: 'login_processing.php',
                data: {username: $('#txtUsername').val(), password: $('#txtPassword').val()},
                success:function(data){
                    switch(data)
                    {
                        case 'Invalid':
                            $('#txt_error').html("Username & password are incorrect!");
                            break;
                        case 'Inactive':
                            $('#txt_error').html("User is inactive!<br>Please contact Administrator!");
                            break;
                        case 'Success':
                            window.location.replace("../Main/");
                            break;
                        default: break;
                    }

                }
            });
        });
    });

</script>
</html>
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
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
<style>
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
</style>
    <script>
        /* attach a submit handler to the form */
        $("#frm_auth").submit(function(event) {

            /* stop form from submitting normally */
            event.preventDefault();

            /* get the action attribute from the <form action=""> element */
            var $form = $( this ),
                url = $form.attr( 'action' );
            /* Send the data using post */
            var posting = $.post( url, { user: $('#txtUsername').val(), pwd: $('#txtPassword').val() } );
            /* Alerts the results */
            posting.done(function( data ) {
                alert('success');
            });
        });
    </script>
</head>
<body>
<div id="login_container">
    <form id="frm_auth" name="frm_auth" method="post" action="login_processing.php">
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
        </table>
    </form>
</div>
</body>
</html>
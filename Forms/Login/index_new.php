<!--This page allows users to login-->
<!--If login successfully, login form will disappear, user can see Main Menu-->
<?php
/*******************************************
 * Creates a session or resumes the current one based on
 * a session identifier passed via a GET or POST request,
 * or passed via a cookie.
 *******************************************/
session_start();
?>
<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Welcome to BandoCat!</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">

    <!-- Page Level Styling -->
    <style>

        .container {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-light">
<div class="container pt-5 bg-secondary" id="main">
    <div class="d-flex flex-column justify-content-center bg-primary">
        <div class="img-thumbnail bg-dark" style="height: 150px; width: 150px;"></div>
        <input class="form-control" type="text">
    </div>
</div><!-- Container -->
<?php include "../../Master/bandocat_copyright_footer.php" ?>

<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- JQuery UI cdn -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
    $(document).ready(function() {
        centerLogin();
    });

    function centerLogin()
    {
        var docHeight = $(window).height();
        var footerHeight = $('#footer').height();

        var totalHeight = docHeight - footerHeight;
        console.log(totalHeight);
        $("#main").css('height', totalHeight + 'px');
    }
</script>
<!-- Page Level Script -->
<script>
    /*******************************************
     * A page can't be manipulated safely until
     * the document is "ready". The code located
     * in this function will only run once the
     * page Document Object Model is ready for
     * code to execute.
     *******************************************/
    $( document ).ready(function()
    {
        /*get current date and update copyright date*/
        var date = new Date();
        document.getElementById("CBI_Year").innerHTML = date.getFullYear();

        /* attach a jquery submit handler to the login form */
        $('#frm_auth').submit(function (event)
        {
            $('#txt_error').text("");
            /* stop form from submitting normally */
            event.preventDefault();

            /* Send the data to login_processing.php using post */
            /* the return value from login_processing.php is stored in data */
            $.ajax({
                type: 'post',
                url: 'login_processing.php',
                data: {username: $('#txtUsername').val(), password: $('#txtPassword').val()},
                success:function(data){
                    switch(data)
                    {
                        case 'Invalid':
                            $('#txt_error').html("Username and/or password is incorrect.");
                            break;
                        case 'Inactive':
                            $('#txt_error').html("User is inactive.<br>Please contact Administrator.");
                            break;
                        case 'Success':
                            window.location.replace("../Landing/");
                            break;
                        default: break;
                    }

                }
            });
        });
    });
</script>
</body>
</html>
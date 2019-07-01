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
<!-- Here is a comment -->
<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Welcome to BandoCat!</title>

    <style>
        .full-width-div {
            position: absolute;
            width: 100%;
            left: 0;
        }
    </style>
</head>
<body>
<!-- Top Navigation Bar -->
<?php include "../../Master/topmenu.php"; ?>
<!-- Jumbotron -->
<div class="jumbotron text-center" style="margin:0;">
    <h1 class="">Hello, <?php echo $session->getUserName();?>!</h1>
    <p class="">Welcome to Bandocat! An all-in-one map scanning project manager!</p>
</div>
<br>
<!-- Page Contents -->
<div class="container">
    <!-- First row of the page -->
    <div class="row">
        <!-- ED RACHEL CONTENT -->
        <div class="col">
            <div class="container">
                <div class="text-center">
                    <h1>Blucher Land Record</h1>
                </div>
                <!-- Text Contents -->
                <div>
                    <p>The Blucher Land Records project is an effort to create a digital land record system containing historic South Texas land records from 11 prominent land surveyors.</p>
                    <p>The project is a collaborative effort by the Conrad Blucher Institute for Surveying and Science and the Mary and Jeff Bell Library. </p>
                    <p>The Ed Rachal Foundation has sponsored this project since 2014 and provide professional support by way of Mr. Ron Brister, RPLS.</p>
                </div>
            </div>
            <!--<div class="text-center">
                <a class="btn btn-secondary" href="../Main/" role="button">Visit Ed Rachel &raquo;</a>
            </div>-->
        </div>
        <!-- USACE CONTENT -->
        <div class="col">
            <div class="container">
                <div class="text-center">
                    <h1> Shoreline Digitization</h1>
                </div>
                <!-- Text Contents -->
                <div>
                    <p>The Shoreline Digitization project aims to provide the public with historic shoreline information from the Texas coast dating back to the 1960s. </p>
                    <p>This project is being completed by the Conrad Blucher Institute for Surveying in Science’s Spatial {Query} Lab and Coastal Dynamics Lab.
                        The imagery being processed is from the US Army Corps of Engineers – Galveston District and is also sponsored by them. </p>
                    <p>Public access to records we be maintained by CBI (digitally) and the Texas Natural Resource Information System (physically).</p>
                </div>
                <!--<div class="text-center">
                    <a class="btn btn-secondary" href="#" role="button">Visit USACE &raquo;</a>
                </div>-->
            </div>
        </div>
        <!-- TONY AMOS CONTENT -->
        <div class="col">
            <div class="container">
                <div class="text-center">
                    <h1>Tony Amos</h1>
                </div>
                <!-- Text Contents -->
                <div>
                    <p>Dr. Tony Amos was a renowned scientist at the University of Texas Marine Science Institute who collected over 40 years of data along beaches near Port Aransas, Texas. </p>
                    <p>This data includes everything from environmental, to species, to shore debris and pollution information.
                        Dr. Amos conducted this research on his own time with his own resources in order to help protect public Texas beaches through beneficial research. </p>
                    <p>After his passing, UTMSI and CBI connected to help process his data and make it available to the public.
                        Thanks to project sponsorship from the Coastal Bend Bays and Estuaries Program, Dr. Amos’ efforts are able to continue to impact decision making related to Texas beaches.</p>
                </div>
                <!--<div class="text-center">
                    <a class="btn btn-secondary text-center" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Tony Amos &raquo;</a>
                </div>-->
            </div>
        </div>
    </div>
    <div class="row">
        <!-- ED RACHEL CONTENT -->
        <div class="col">
            <div class="text-center">
                <a class="btn btn-secondary" href="../Main/" role="button">Visit Blucher Land &raquo;</a>
            </div>
        </div>
        <!-- USACE CONTENT -->
        <div class="col">
            <div class="container">
                <div class="text-center">
                    <a class="btn btn-secondary" href="#" role="button">coming soon</a>
                </div>
            </div>
        </div>
        <!-- TONY AMOS CONTENT -->
        <div class="col">
            <div class="container">
                <div class="text-center">
                    <a class="btn btn-secondary text-center" href="../Main/" role="button">Visit Tony Amos &raquo;</a>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="full-width-div">
        <!-- Footer -->
        <?php include "../../Master/new_footer.php"; ?>
    </div>
</div> <!-- /fluid container -->
<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('body').on('click','.carousel-control',function() {
            $(this).closest('.carousel').carousel( $(this).data('slide') );
        });
    });
</script>
</body>
</html>





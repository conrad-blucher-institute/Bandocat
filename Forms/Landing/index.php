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
                    <h1>Ed Rachel</h1>
                </div>
                <!-- Text Contents -->
                <div>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                </div>
                <div class="text-center">
                    <a class="btn btn-secondary" href="../Main/" role="button">Visit Ed Rachel &raquo;</a>
                </div>
            </div>
        </div>
        <!-- USACE CONTENT -->
        <div class="col">
            <div class="container">
                <div class="text-center">
                    <h1>USACE</h1>
                </div>
                <!-- Text Contents -->
                <div>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                </div>
                <div class="text-center">
                    <a class="btn btn-secondary" href="#" role="button">Visit USACE &raquo;</a>
                </div>
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
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                </div>
                <div class="text-center">
                    <a class="btn btn-secondary text-center" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Tony Amos &raquo;</a>
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





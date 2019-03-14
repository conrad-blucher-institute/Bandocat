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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Welcome to BandoCat!</title>
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
<div class="container" style="width: 100%;">
    <!-- Carousel -->
    <div class="row">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="width: 400px; height:400px; margin: 0 auto">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Edward Shell</h5>
                        <p><a class="btn btn-primary" href="../Main/" role="button">Visit &raquo;</a></p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>USACE</h5>
                        <p><a class="btn btn-primary" href="#" role="button">Visit &raquo;</a></p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Tony Amos</h5>
                        <p><a class="btn btn-primary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit &raquo;</a></p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <hr>
    <!-- Example row of columns -->
    <div class="row text-center">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h2>Ed Rachel</h2>
                </div>
                <div class="card-body">
                    <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                    <hr>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <a class="btn btn-secondary" href="../Main/" role="button">Visit Ed Rachel &raquo;</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h2>USACE</h2>
                </div>
                <div class="card-body">
                    <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                    <hr>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <a class="btn btn-secondary" href="#" role="button">Visit USACE &raquo;</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h2>Tony Amos</h2>
                </div>
                <div class="card-body">
                    <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                    <hr>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Tony Amos &raquo;</a>
                </div>
            </div>
        </div>
    </div>
    <br>
    <!-- Newest Carosel -->
    <!--<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="row text-center">
                    <div class="col">
                        <h2>Ed Rachel</h2>
                        <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Ed Rachel &raquo;</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row text-center">
                    <div class="col">
                        <h2>USACE</h2>
                        <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit USACE &raquo;</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row text-center">
                    <div class="col">
                        <h2>Tony Amos</h2>
                        <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Tony Amos &raquo;</a>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>-->
    <br>
    <!-- Newest Carosel -->
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            <h2>Ed Rachel</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Ed Rachel &raquo;</a>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <h2>Ed Rachel</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Ed Rachel &raquo;</a>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <h2>Ed Rachel</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Ed Rachel &raquo;</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row text-center">
                    <div class="col">
                        <div class="text-center">
                            <h2>USACE</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit USACE &raquo;</a>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <h2>USACE</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit USACE &raquo;</a>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <h2>USACE</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit USACE &raquo;</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row text-center">
                    <div class="col">
                        <div class="text-center">
                            <h2>Tony Amos</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Tony Amos &raquo;</a>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <h2>Tony Amos</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Tony Amos &raquo;</a>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <h2>Tony Amos</h2>
                            <img class="rounded-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="height: 150px; width: 150px;">
                        </div>
                        <hr>
                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                        <a class="btn btn-secondary" href="../../../TonyAmos/Forms/TonyAmosCollection.php" role="button">Visit Tony Amos &raquo;</a>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <br>
</div> <!-- /container -->
<!-- Footer -->
<?php include "../../Master/new_footer.php"; ?>
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





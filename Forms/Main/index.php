<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
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

    <!-- Font Awesome CDN CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <title>Welcome to Ed Rachel!</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>

<!-- Container -->
<div class="container-fluid pl-5 pr-5">
    <!-- Put Page Contents Here -->
    <h1 class="text-center" >Bandocat</h1>
    <hr>

    <div class="row">

        <div class="col">
            <!-- I-FRAME -->
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="http://spatialquerylab.com/news/" frameborder="0" style="border: 3px solid #1E90FF;" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Announcements -->
        <div class="col-3 text-center" style="border: 3px solid #1E90FF;">
            <h3>Announcements</h3>
            <hr>
            <?php
            if($session->isAdmin())
            {
                echo "<input type='button' value='ADD ANNOUNCEMENT' class='btn btn-primary' id='addAnnouncement' style='margin-bottom: 1.5em'>";
            }
            else header('Location: ../../');
            ?>

            <div class="card mx-auto text-center" style="width: 20rem;">
                <div class="card-header" style="background-color: #3CB371;">
                    <font color="white">Title</font>
                </div>

                <p> Cras justo odio</p>
                <p> Dapibus ac facilisis in</p>
                <p> Vestibulum at eros</p>
                <p> What is up my dude</p>
                <p> nice wheather tho</p>

            </div>

        </div>

    </div> <!-- row -->
</div><!-- Container -->

<!-- Modal -->
<div id="Modal">
    <div class="modal fade" id="rowModal" tabindex="-1" role="dialog" aria-labelledby="rowModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rowModalTitle">Add Announcement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateDataBase">
                    <div class="modal-body" id="rowModalBody">
                        <!-- Title -->
                        <div class="form-group row" align="center">
                            <label class="col-sm-1 col-form-label">Title:</label>
                            <div class="col-sm-8" id="title">
                                <input type = "text" class="form-control" name = "title" id = "title" value="" required />
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group row" align="center">
                            <label class="col-sm-1 col-form-label">Content:</label>
                            <div class="col-sm-8" id="content">
                                <!--<input type = "text" class="form-control" name = "content" id = "content" value="" required />-->
                                <textarea class="form-control" name="content" id="content" cols="40" rows="5" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row" align="center">
                            <label class="col-sm-1 col-form-label">Exp Date:</label>
                            <div class="col-sm-8" id="date">
                                <input data-format="yyyy-mm-dd" type = "text" class="form-control" name = "datepicker" id = "datepicker" required />
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" value="Add" class="btn btn-primary" id="submit">
                        <!--<input type="button" value="Delete" class="btn btn-danger" id="delete">-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "../../Master/bandocat_footer.php" ?>

<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- JQuery UI cdn -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- DatePicker -->
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>
<script>
    $('#addAnnouncement').click(function() {
        $('#rowModal').modal('show');
    });

    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4'
    });

    // Reloads page when response modal is exited out of or hidden
    $('#rowModal').on('hidden.bs.modal', function () {
        location.reload();
    });
</script>
</body>
</html>
<?php
//Menu
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col']))
{
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
    $userRole = $session -> getRole();
    //If the user is a reader automatically redirect them to the edit page because only one button remains, and is pointless
    //to have 1 button to go to one place.
    if($userRole == "Reader")
    {
        header('Location: ./list.php?col='.$collection.'&action=review');
    }
}
else header('Location: ../../');
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
    <title><?php echo $config["DisplayName"]; ?> Menu</title>
</head>
<body style="height: 100%;">
<?php include "../../Master/bandocat_navbar.php"; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include "../../Master/bandocat_sidenavbar.php"; ?>
        </div>
        <div class="col-9" style="height: 67vh;">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">Menu</h1>
            <hr>
            <div class="col" align="center">
                <div class="card" style="width: 50%;">
                    <div class="card-header text-center">
                        <h3><?php echo $config["DisplayName"]; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column bd-highlight mb-3">
                            <div class="p-2 bd-highlight"><a href="catalog.php?col=<?php echo $collection; ?>" class="btn btn-primary btn-block">Catalog Document</a></div>
                            <div class="p-2 bd-highlight"><a href="./list.php?col=<?php echo $collection; ?>" class="btn btn-primary btn-block">Edit/View Document</a></div>
                            <div class="p-2 bd-highlight"><a href="../../GeoRec/Map/index.php?col=<?php echo $collection; ?>" class="btn btn-primary btn-block">Rectify Document</a></div>
                        </div>
                    </div>
                </div>
            </div><!-- Card Col -->
        </div> <!-- Col-9 -->
    </div> <!-- row -->
</div><!-- Container-fluid -->
<?php include "../../Master/bandocat_footer.php"; ?>
<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

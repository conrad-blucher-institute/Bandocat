<?php
//for admin use only

//Include all the classes from the Library directory
spl_autoload_register(function ($class_name) {
    require_once "../../Library/" . $class_name . '.php';});
$session = new SessionManager();

//for admin only
if($session->isAdmin()) {
    $DB = new DBHelper();
}
else header('Location: ../../');
$Render = new ControlsRender();

//DESCRIPTION: THIS PAGE DISPLAYS QUEUE OF TDLPUBLISH LIST
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

    <!-- Bootstrap CDN Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" crossorigin="anonymous">

    <title>TDL Publishing Queue</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container" id="main">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">TDL Publishing Queue</h1>
            <hr>
            <!-- Form responsible for the select drop down menu -->
            <form id = "form" name="form" method="post">
                <div class="form-group row">
                    <label for="ddlCollection" class="col-sm-2 col-form-label">Select Collection:</label>
                    <div class="col-sm-3">
                        <select name="ddlCollection" id="ddlCollection" class="form-control form-control">
                            <!-- Renders the Dropdownlist with the collections -->
                            <?php $Render->GET_DDL_COLLECTION($DB->GET_COLLECTION_FOR_DROPDOWN_FROM_TEMPLATEID(array(4),false),"");?>
                        </select>
                    </div>
                </div>
                <button onclick="startBackgroundWorker()" id="btnStartWorker" class="btn btn-primary btn-sm mb-3">Start Background Worker (Testing only)</button>
                <div class="form-group row">
                    <div class="col-md-1">
                        <button id="btnPush" name="btnPush" onclick="pushQueue();" class="btn btn-primary btn-sm">Push</button>
                    </div>
                    <div class="col-md-1">
                        <select id="ddlHowMany" name="ddlHowMany" class="form-control form-control">
                            <!-- Renders the Dropdownlist with the collections -->
                            <option value="1">1</option>
                            <option value="20">20</option>
                            <option value="40">40</option>
                            <option value="60">60</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="">All</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button id="btnReset" name="btnReset" onclick="resetQueue();" class="btn btn-primary btn-sm">Reset Queue</button>
                    </div>
                </div>
            </form>
        </div> <!-- col -->
    </div> <!-- row -->
    <div class="row">
        <div class="col">
            <table id="tblQueue" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
            </table>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="text-center">Log</h5>
                </div>
                <div class="card-body" style="overflow-y: auto; max-height: 25em;">
                    <div id="divLog">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- Container -->
<?php include "../../Master/bandocat_footer.php" ?>


<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- JQuery UI cdn -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- Datatables CDN -->
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

<!-- Bootstrap JS files for datatables CDN -->
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
    $(document).ready(function() {

        var docHeight = $(window).height() - $('#megaMenu').height();
        console.log(docHeight);
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });

    $( window ).resize(function() {
        var docHeight = $(window).height() -  - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
        {
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
        }
    });
</script>
<!-- Page Level Plugin -->
<script>
    //testing only
    function startBackgroundWorker()
    {
        $.ajax({
            type: "POST",
            url: "queue_startworker_processing.php",
            data: {},
            success: function (data) {
            }
        });
    }

    //convert the DspacePublished status code into String
    function statusConverter(iStatus)
    {
        switch(iStatus)
        {
            case "2": return "In Queue";
            case "10": //publishing front
                return "Publishing";
            case "11": //publishing back
                return "Continue Publishing";
            default: return iStatus.toString();
        }
    }

    function loadQueue()
    {
        $.ajax({
            type: "POST",
            url: "queue_processing.php?action=load",
            data: {ddlCollection: $("#ddlCollection").val()},
            success: function (data) {
                var temp;
                data = JSON.parse(data);
                for(var i = 0; i < data.length; i++)
                    temp += "<tr><td>" + parseInt(i + 1) +"</td><td>" + data[i].documentID + "</td><td>" + data[i].libraryindex + "</td><td>" + statusConverter(data[i].dspacePublished)  +"</td></tr>";
                $("#tblQueue").html("<thead><td>#<td>ID</td><td>Library Index</td><td>Status</td></thead>" + temp);
            }
        });
    }

    function resetQueue()
    {
        $.ajax({
            type: "POST",
            url: "queue_processing.php?action=reset",
            data: {ddlCollection: $("#ddlCollection").val()},
            success: function (data) {
                loadQueue();
            }
        });
    }

    function pushQueue()
    {
        $.ajax({
            type: "POST",
            url: "queue_processing.php?action=push",
            data: {ddlCollection: $("#ddlCollection").val(), howMany: $("#ddlHowMany :selected").val()},
            success: function (data) {
                loadQueue();
            }
        });
    }
    //read log file and parse it to HTML #divLog
    function displayLog()
    {
        $.ajax({
            type: "POST",
            url: "queue_processing.php?action=displaylog",
            data: {ddlCollection: $("#ddlCollection").val()},
            success: function (data) {
                console.log(data);
                //$("#divLog").append(data);
            }
        });
    }

    /*Submit event that obtains teh information from the user form and calls the newuser_processing.php page, which links to
     a procedure in the database that insert the information into the bandocatdb database in the user table .*/
    $(document).ready(function () {
        //displayLog();
        $( "#ddlCollection" ).change(function() {
            switch ($("#ddlCollection").val())
            {
                case "": break;
                default: {
                    console.log("Loading Queue...");
                    console.log($("#ddlCollection").val());
                    loadQueue();
                    //displayLog();
                }
            }
        });

        $("#ddlCollection").change(); //run this when the page is loaded

        //Reload queue after 10sec, reload Queue every 10sec
        window.setInterval(function(){
            if($("#ddlCollection").val() == "")
            {
                console.log("Selection box is empty");
            }
            else{
                loadQueue();
            }

        }, 10000);
        //Reload queue after 18sec, reload Log every 10sec
        window.setInterval(function(){
            if($("#ddlCollection").val() == "")
            {
                console.log("Selection box is empty");
            }
            else{
                console.log("displaying log");
                displayLog();
            }
            //
        }, 18000);

    });
</script>
</body>
</html>
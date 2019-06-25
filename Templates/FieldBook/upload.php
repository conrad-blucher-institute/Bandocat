<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
//get collection name from passed variable col
if(isset($_GET['col']))
{
    //get collection name passed in from side menu
    $collection = $_GET['col'];
    require '../../Library/DBHelper.php';
    require '../../Library/FieldBookDBHelper.php';
    $DB = new FieldBookDBHelper();
    //get appropriate DB
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
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
    <title><?php echo $config['DisplayName']; ?> Document Upload</title>

    <!-- Bootstrap CDN Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" crossorigin="anonymous">

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center"><?php echo $config['DisplayName']; ?> Document Upload</h1>
            <hr>

            <!-- This is used to center the card onto the page -->
            <div class="d-flex justify-content-center">
                <!-- Starting a card for styling -->
                <div class="card" style="width: 30em;">
                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- Starting the form -->
                        <form id="frmUpload" name="frmUpload" method="post" enctype="multipart/form-data">
                            <!-- File Upload Button -->
                            <div class="form-row">
                                <div class="form-group col">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file_array[]" id="file_array" accept=".tif" value="Input Map Information" multiple>
                                        <label class="custom-file-label" for="customFile">Choose Files</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Table -->
                            <div class="form-row">
                                <div class="form-group col">
                                    <label>Selected Files</label>
                                    <table class="table table-striped table-bordered" width="100%">
                                        <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>File Size</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody id="selectedFilesTable">
                                        <tr><td>No files selected</td></tr>
                                        </tbody>
                                        <tfoot id="selectedFilesTableFooter" style="background: #007F3E; color: white;"></tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- Upload Button -->
                            <div class="form-row">
                                <div class="form-group col d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary" value="Upload" id="btnUpload">Upload</button>
                                </div>
                            </div>
                            <!-- Message -->
                            <div class="form-row">
                                <div class="form-group col d-flex justify-content-center">
                                    <p class="text-danger">Recommended number of files for uploading: 100 files</p>
                                </div>
                            </div>
                        </form>
                    </div> <!-- Card body -->
                </div> <!-- Card -->
            </div> <!-- d-flex center -->

            <!-- Importing loading modal -->
            <?php include "../../Templates/Load/load.php"; ?>

        </div> <!-- col -->
    </div> <!-- row -->
</div><!-- Container -->
<?php include "../../Master/bandocat_footer.php" ?>


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

        var docHeight = $(window).height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');

        // Adding modal attributes
        $('#loaderModalContent').append('<div class="modal-body" id="loaderModalBody"></div>');
        $('#loaderModalBody').append('<div class="d-flex justify-content-center"><img src="../../Images/loading2.gif"></div><h5 class="text-center">Uploading Files...</h5>');
    });
</script>
<!-- This page's script -->
<script>
    var totalFsize = 0;

    // listener for when the document is loaded
    document.addEventListener("DOMContentLoaded", init, false);
    /**********************************************
     * Function: init
     * Description: responsible for initializing the handlefileselect function when the content is loaded
     * Parameter(s):
     * Return value(s):
     ***********************************************/
    function init()
    {
        //add listener to the choose files button and attach handlefileselect to the listener
        document.querySelector('#file_array').addEventListener('change', handleFileSelect, false);
        selTable = document.querySelector("#selectedFilesTable");
        selTableFooter = document.querySelector("#selectedFilesTableFooter");
    }
    /**********************************************
     * Function: handleFileSelect
     * Description: handles the selcected files
     * Parameter(s):
     * e (in files) - selected files
     * Return value(s):
     ***********************************************/
    function handleFileSelect(e)
    {
        var total = 0;
        totalFsize = 0;
        if(!e.target.files) return;

        selTable.innerHTML = "";

        var files = e.target.files;
        var filenames = [];
        for(var i=0; i<files.length; i++)
        {
            var f = files[i];
            filenames.push(f.name);
            totalFsize += f.size/1000000;
            total = totalFsize.toFixed(2);
            var row = selTable.insertRow(i);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.innerHTML =  f.name;
            cell2.innerHTML =  (f.size/1000000).toFixed(2) + " MB";
            cell3.id = f.name;
            cell3.innerHTML = "Validating...";
        }

        $.ajax({
            //Checks if the filenames already exist in the DB
            url: 'upload_validating.php?col=<?php echo $collection; ?>',
            type: 'POST',
            data: {fileNames : filenames},
            success: function (data)
            {
                data = JSON.parse(data);
                for(var i = 0; i < data.length; i++)
                {
                    //if not found
                    if(data[i] == 0)
                    {
                        document.getElementById(filenames[i]).innerHTML = "Ready";
                        document.getElementById(filenames[i]).style.color = "green";
                    }
                    else
                    {
                        document.getElementById(filenames[i]).style.color = "red";
                        if (data[i] == 1)
                            document.getElementById(filenames[i]).innerHTML = "Existed";
                        else document.getElementById(filenames[i]).innerHTML = "Error";

                    }
                }
            },

        });

        //add rows and cells to table displaying file names
        var tableFooterLength = selTableFooter.rows.length;
        var row = selTableFooter.insertRow(0);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        cell1.innerHTML =  "Total number of files: " + files.length;
        cell2.innerHTML += "Total file size upload: " + total+" MB";

        if (tableFooterLength > 0){
            selTableFooter.deleteRow(1);
        }
    }
    //frmUpload is the form that holds the btn submit
    $("#frmUpload").submit(function(event)
    {
        //Change button to uploading, then disable it
        $("#btnUpload").val("Uploading...");
        $("#btnUpload").attr("disabled",true);

        // Showing loader modal
        $("#loaderModal").modal("show");

        event.preventDefault();
        var data = new FormData();
        //Javascript FormData sent to upload_processing via ajax
        jQuery.each(jQuery('#file_array')[0].files, function(i, file)
        {
            data.append('file:'+i, file);
        });
        $.ajax({
            url: 'upload_processing.php?col=<?php echo $collection; ?>',
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data)
            {
                data = JSON.parse(data);
                for(var i = 0; i < data.length; i++)
                {

                    document.getElementById(data[i][0]).innerHTML = data[i][1];
                    if(data[i][1] == "Uploaded")
                        document.getElementById(data[i][0]).style.color = "green";
                    else document.getElementById(data[i][0]).style.color = "red";
                }
                $("#btnUpload").val("Upload");
                $("#btnUpload").attr("disabled",false);
                // Showing loader modal
                $("#loaderModal").modal("hide");
            }
        });
    });
</script>
</body>
</html>

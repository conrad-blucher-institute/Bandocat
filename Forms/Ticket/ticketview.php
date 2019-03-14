<?php
//for admin to view ticket and update ticket status
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['id'])) {
    $tID = $_GET['id']; //ticket ID
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
}
else header('Location: ../../');

$ticket = $DB->SP_ADMIN_TICKET_SELECT($tID); //assoc array contains ticket info
//var_dump($ticket); //uncomment this to display the array


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
    <title>Ticket View</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container pad-bottom">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">Ticket View</h1>
            <hr>
        </div> <!-- col -->
    </div> <!-- row -->
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-center">
                <div class="card" style="width: 30em;">
                    <div class="card-header">
                        <!-- Collection Name -->
                        <h4 id="Collection_Name" class="text-center"></h4>
                    </div>
                    <div class="card-body">
                        <form id="frmTicket" name="frmTicket">
                            <!-- Subject -->
                            <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label"><strong>Subject:</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" readonly class="form-control-plaintext" id="subject" value="<?php echo $ticket["Subject"];?>">
                                </div>
                            </div>
                            <!-- Submitter -->
                            <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label"><strong>Submitter:</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" readonly class="form-control-plaintext" id="submitter" value="<?php echo $ticket["Submitter"];?>">
                                </div>
                            </div>
                            <!-- Previously Solved By -->
                            <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label"><strong>Solved by:</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" readonly class="form-control-plaintext" id="solvedby" value="<?php if(!is_null($ticket["Solver"])) {echo $ticket["Solver"];} else {echo "Not Resolved";}?>">
                                </div>
                            </div>
                            <!-- Submitted At -->
                            <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label"><strong>Date Submitted:</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" readonly class="form-control-plaintext" id="submissiondate" value="<?php echo $ticket["SubmissionDate"];?>">
                                </div>
                            </div>
                            <!-- Description -->
                            <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label"><strong>Description:</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" readonly class="form-control-plaintext" id="description" value="<?php echo $ticket["Description"];?>">
                                </div>
                            </div>
                            <!-- Status -->
                            <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label"><strong>Status:</strong></label>
                                <div class="col-sm-8">
                                    <div class="d-flex">
                                        <div class="container">
                                            <input class="form-check-input" type="radio" name="Status" value="0" id="StatusOpen" <?php if($ticket["Status"] == "0") {echo "checked";}?>>
                                            <label class="form-check-label" for="StatusOpen">Open</label>
                                        </div>
                                        <div class="container">
                                            <input class="form-check-input" type="radio" name="Status" value="1" id="StatusClosed" <?php if($ticket["Status"] == "1") {echo "checked";}?>>
                                            <label class="form-check-label" for="StatusClosed">Closed</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Notes -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="Notes"><strong>Notes:</strong></label>
                                    <textarea rows="8" cols="75" id="Notes" name="txtNotes" class="form-control"></textarea>
                                </div>
                            </div>
                            <input class="btn btn-primary" type="submit" name="btnSubmit" id="btnSubmit"/>
                        </form>
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
    });
</script>
<!-- page level plugin -->
<script>

    $( document ).ready(function() {
        //Variable that stores in a json the information of the ticket retrieved from the database.
        var data = <?php echo json_encode($ticket); ?>;
        //Series of document elements in which the data from the ticket is saved into their inner text.
        document.getElementById("Collection_Name").innerText = data.Collection;
        //JSON with the library index information
        var libIdxJSON = JSON.parse(data.LibraryIndex);
        //Switch statement that selects the collection name and file name to be used to link the ticket with its document
        switch(data.Collection) {
            case 'Blucher Maps':
                var dbCol = 'bluchermaps';
                var file = 'Map';
                break;
            case 'Green Maps':
                var dbCol = 'greenmaps';
                var file = 'Map';
                break;
            case 'Job Folder':
                var dbCol = 'jobfolder';
                var file = 'Folder';
                break;
            case 'Blucher Field Book':
                var dbCol = 'blucherfieldbook';
                var file = 'FieldBook';
                break;
            case 'Map Indices':
                var dbCol = 'mapindices';
                var file = 'Indices';
                break;
        }

        //Object that will be posted to ticketLink.php is initialized
        var ticketData = {};
        //Object property data is initialized as an array
        ticketData['data'] = [];
        //For each element from the JSON with the library index information by index and by element
        $.each(libIdxJSON, function (index, obj) {
            var libraryIndex = obj;
            /*Data pushed to the data object to be posted to ticketLink; library index collection, and
            library index name*/
            ticketData['data'].push({"subjectCol": dbCol, "subject": libraryIndex});
        });

        $.ajax({
            url: 'ticketLink.php',
            type: 'post',
            data: ticketData,
            /*If the function was executed correctly then it will return the document id and library index in a data
             object: RETURNED Object FORMAT: {"data":[[Document Id, Library Index],...[]]}*/
            success: function (libData) {
                var libInfo = JSON.parse(libData);
                $.each(libInfo, function (data, info) {
                    for(var ticket = 0; ticket < info.length; ticket++){
                        //Document ID
                        documentID = info[ticket][0];
                        //Library Index
                        documentLibIndex = info[ticket][1];

                        /*LINK*/
                        //If the document id was fetched from database
                        if(documentID !== false){
                            if(ticket > 0){
                                $('#libraryIndexList tr:last').html("<a href='../../Templates/" + file + "/review.php?doc=" + documentID + "&col=" + dbCol + "' target='_blank' >"+ documentLibIndex +"</a>");
                            }
                            else
                                $('#libraryIndexRow' + ticket).html("<a href='../../Templates/" + file + "/review.php?doc=" + documentID + "&col=" + dbCol + "' target='_blank' >"+ documentLibIndex +"</a>");
                            //Inserts a row to the last after the last row element in the table
                            var tc = ticket + 1;
                            $('#libraryIndexList tr:last').after('<tr id="libraryIndexRow"' + tc + '></tr>');
                        }

                        /*NO LINK*/
                        else{
                            if(ticket > 0){
                                $('#libraryIndexList tr:last').html(documentLibIndex);
                            }
                            else
                                $('#libraryIndexRow' + ticket).html(documentLibIndex);
                            //Inserts a row to the last after the last row element in the table
                            var tc = ticket + 1;
                            $('#libraryIndexList tr:last').after('<tr id="libraryIndexRow"' + tc + '></tr>');
                        }
                    }
                });
                console.log(data);
            }
        });

        /*Input tags compared conditionally with the status data, from the ticket, to determine if it should be
         checked or not.*/
        if (document.getElementsByTagName("input")[0].value == "0") {
            if (data.Status == 0) {
                document.getElementsByTagName("input")[0].checked = true;
            }
        }

        if (document.getElementsByTagName("input")[1].value == "1") {
            if (data.Status == 1) {
                document.getElementsByTagName("input")[1].checked = true;
            }
        }

        $("#btnSubmit").click(function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "./ticketview_processing.php?id=" + "<?php echo $tID;?>",
                data: $("#frmTicket").serializeArray(),
                success: function (data) {
                    console.log("Return: " + data);
                    //generate total chart
                    alert(data);
                }
            });
        });
    });

</script>
</body>
</html>
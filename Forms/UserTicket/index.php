<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
require('../../Library/DBHelper.php');
$DB = new DBHelper();
$userid = $session-> getUserID();
$userticketCount = $DB->GET_USER_CLOSEDTICKET_COUNT($userid);
$collection_array = $DB->GET_COLLECTION_FOR_DROPDOWN();
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

    <!-- Font Awesome CDN CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <title>Ticket</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center" id="page_title">Ticket</h1>
            <hr>
            <p>To view the full ticket, please select an item from the table.</p>
            <table id="dtable" class="table table-bordered table-hover" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th>Collection</th>
                    <th>Library Index</th>
                    <th>Error</th>
                    <th>Submission</th>
                    <th>Solved</th>
                    <th>Last Seen</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Collection</th>
                    <th>Library Index</th>
                    <th>Error</th>
                    <th>Submission</th>
                    <th>Solved</th>
                    <th>Last Seen</th>
                    <th>Status</th>
                </tr>
                </tfoot>
            </table>
        </div> <!-- col -->
    </div> <!-- row -->
</div><!-- Container -->
<?php include "../../Master/bandocat_footer.php" ?>

<!-- Modal -->
<div class="modal fade" id="rowModal" tabindex="-1" role="dialog" aria-labelledby="rowModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rowModalTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateTicket">
                <div class="modal-body" id="rowModalBody">
                    <!-- Ticket ID -->
                    <div class="form-group row">
                        <label for="ticketID" class="col-sm-3 col-form-label">Ticket ID</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control-plaintext" id="ticketID" name="ticketID" value="1234">
                        </div>
                    </div>
                    <!-- Database -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="ddlDBname">Database:</label>
                        <div class="col-sm-9">
                            <select name="ddlDBname" id="ddlDBname" class="form-control" required>
                                <option value="">Select</option>
                                <?php
                                foreach($collection_array as $col)
                                    echo "<option value='" . $col['collectionID'] .  "'>$col[displayname]</option>";
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- Library Index -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="txtSubject1">Library Index 1:</label>
                        <div class="col-sm-7">
                            <input type = "text" name = "txtSubject" id = "txtSubject1" size="32" class="form-control" required/>
                        </div>
                        <div class="col-sm-1">
                            <input type="button" onclick="add_fields()" value="+" class="btn btn-primary">
                        </div>
                        <div class="col-sm-1">
                            <input type="button" onclick="remove_fields()" value="-" class="btn btn-danger">
                        </div>
                    </div>
                    <!-- Additional Library Index's if generated by the user -->
                    <div id="divSubject">
                    </div>
                    <!-- Ticket Errors -->
                    <div class="form-group">
                        <label for="errorTicket">Problem Selected</label>
                        <select class="form-control" id="errorTicket" name="errorTicket" required>
                            <?php echo $errorTickets; ?>
                        </select>
                        <small id="errorHelp" class="form-text text-muted">Please select one error only</small>
                    </div>
                    <!-- Problem -->
                    <div class="form-group">
                        <label for="txtDesc">Your Description</label>
                        <textarea rows="5" class="form-control" id="txtDesc" placeholder="Write your description here" name="txtDesc" maxlength="250" required>This is what the user wrote</textarea>
                        <p class="form-control-plaintext" id="counter"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" value="Save Changes" class="btn btn-primary" id="submit">
                    <input type="button" value="Delete Ticket" class="btn btn-danger" id="delete">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalTitle"></h5>
                <input type="text" hidden value="" id="status">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="responseModalBody">

            </div>
        </div>
    </div>
</div>


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
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });

    $(window).resize(function() {
        var docHeight = $(window).height() - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });
</script>
<!-- Page's local script -->
<script>
    var libArray = [];
    var length;

    $(document).ready(function() {
        length = 2;
        showTable();
    });

    function showTable()
    {
        var counter = 0;
        // Setup - add a text input to each footer cell
        $('#dtable tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } );

        // Example dtable using this method: https://datatables.net/examples/ajax/objects.html
        var table = $('#dtable').DataTable({
            "processing": true,
            "serverside": true,
            "lengthMenu": [20, 40, 60, 80, 100],
            "destroy": true,
            "bAutoWidth": false,
            /*"initComplete": function () {
                console.log("Table done loading...");
            },*/
            // Getting select statement
            "ajax": "./list_processing.php",
            "columns": [
                {"data": 'displayname'},
                {
                    "data": 'subject',
                    "render": function(data, type, row, meta) {
                        //console.log(data);
                        //console.log(type);
                        //console.log(row);
                        //console.log(meta);
                        //console.log(row);
                        if(type === "display" && row["jsonlink"] !== null)
                        {
                            data = "";
                            var json = JSON.parse(row["jsonlink"]);

                            if(json.length === 1)
                            {
                                data += createLink(json[0], row["templateID"], row["name"]);
                            }

                            else
                            {
                                for(var i = 0; i < json.length; i++)
                                {
                                    // Last one
                                    if(i === json.length - 1)
                                    {
                                        data += createLink(json[i], row["templateID"], row["name"]);
                                    }

                                    // Not last
                                    else
                                    {
                                        data += createLink(json[i], row["templateID"], row["name"]) + "<br>";
                                    }
                                }
                            }
                        }
                        return data;
                    }
                },
                {"data": 'error'},
                {"data": 'submissiondate'},
                {"data": 'solveddate'},
                {"data": 'lastseen'},
                {
                    "data": 'status',
                    "render":function(data, type, row, meta)
                    {
                        // checking what the data is
                        if(data === "0")
                        {
                            data = "Open";
                        }

                        else
                        {
                            data = "Closed";
                        }
                        return data;
                    }
                }
            ],
            "initComplete": function() {
                this.api().columns().every( function () {
                    var column = this;
                    switch(column[0][0]) //column number
                    {
                        case 0:
                            var that = this;

                            $(column.footer()).empty();
                            // Create the select list and search operation
                            var select = $('<select class="form-control form-control-sm" />')
                                .appendTo(
                                    this.footer()
                                )
                                .on( 'change', function () {
                                    that
                                        .search( $(this).val() )
                                        .draw();
                                } );

                            select.append($('<option value="">Filter...</option>'));

                            // Get the search data for the first column and add to the select list
                            this
                                .cache( 'search' )
                                .sort()
                                .unique()
                                .each( function ( d ) {
                                    //console.log(d);
                                    select.append( $('<option value="'+d+'">'+d+'</option>') );
                                } );
                            break;
                            break;
                        //search text box
                        case 1:

                        case 2:
                        case 3:
                        case 4:
                        // Search text box for all columns between 1 - 5
                        case 5:
                            var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search..." value=""></input>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'keyup change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val)
                                        .draw();
                                } );
                            break;
                        case 6:
                            var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search..." value=""></input>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'keyup change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val)
                                        .draw();
                                } );
                            break;
                        //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
                        case 7: //Status column
                            var select = $('<select class="form-control form-control-sm"><option value="">Filter...</option><option value="0">Open</option><option value="1">Closed</option></select>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val)
                                        .draw();
                                } );
                            break;
                        default:

                            break;
                    }
                } );
            }
        });

        // When the user clicks on a row on the data table
        $('#dtable tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();

            // Clear counter for the text area
            $("#counter").empty();

            $('#rowModal').modal('show');
            fillModal(data);
            console.log(data);
        } );
    }

    function fillModal(data)
    {
        length = 2;

        // Setting the title
        $('#rowModalTitle').text(data["subject"]);

        // Set form values
        $('#ticketID').val(data["ticketID"]);
        $('#ddlDBname option:contains(' + data["displayname"] + ')').prop('selected', true);
        $('#errorTicket option:contains(' + data["error"] + ')').prop('selected', true);
        $('#txtDesc').val(data["description"]);

        // Empty divSubject
        $('#divSubject').empty();

        // Setting library index
        var jsonlink = JSON.parse(data["jsonlink"]);

        if(jsonlink !== null && typeof jsonlink[0] !== 'undefined')
        {
            // Looping through library indexes
            for(var x = 0; x < jsonlink.length; x++)
            {
                // Populate txtSubject1 with the first library index
                if(x === 0)
                {
                    $('#txtSubject1').val(jsonlink[x].libraryIndex);
                }

                else
                {
                    add_fields();
                    $('#txtSubject' + (length - 1)).val(jsonlink[x].libraryIndex);
                }
            }
        }

        // If the json link doesn't exist, just use the subject
        else
        {
            var subject = data["subject"];
            subject = subject.replace(/\s/g, '');

            // Splitting subject into an array
            subject = subject.split(",");

            for(var i = 0; i < subject.length; i++)
            {
                // For the first iteration, just add the subject to the first one
                if(i === 0)
                {
                   $('#txtSubject1').val(subject[i]);
                }

                else
                {
                    add_fields();
                    $('#txtSubject' + (length - 1)).val(subject[i]);
                }
            }
        }
    }

    function checkDuplicates(array, value)
    {
        // Returns -1 if the item could not be found
        return array.indexOf(value);
    }

    /**********************************************
     * Function: add_fields
     * Description: adds more fields for authors
     * Parameter(s): length (integer) Length of Author's cells
     * val (String ) - name of the author
     * Return value(s): None
     ***********************************************/

    function add_fields() {
        var html = '<div class="form-group row" id="length' + length + '">' +
            '<label class="col-sm-3 col-form-label" for="txtSubject'+ length + '">Library Index ' + length + ':</label>' +
            '<div class="col-sm-9">\n' +
            '<input type = "text" name = "txtSubject" id = "txtSubject' + length + '" size="32" class="form-control" required/>' +
            '</div>' +
            '</div>';

        // Appending html
        $('#divSubject').append(html);

        // Incrementing counter
        length++;
    }

    function remove_fields() {
        // Remove library index n and decrement length
        if(length > 2)
        {
            length--;
            $('#length' + length).remove();
        }

        // Length should not be lower than two, so we still remove library index 2 but we don't decrement
        else if(length === 2)
        {
            $('#length' + length).remove();
            length = 2;
        }
    }

    $('#txtDesc').keyup(function(event) {

        var characters = 250 - $(this).val().length;

        if(characters >= 0)
        {
            $("#counter").text("Characters left: " + characters);
        }
        console.log((250 - $(this).val().length));

    });

    function maxLength(el) {
        if (!('maxLength' in el)) {
            var max = el.attributes.maxLength.value;
            el.onkeypress = function () {
                if (this.value.length >= max) return false;
            };
        }
    }

    function createLink(json, templateID, collection)
    {
        var tag = "";
        templateID = parseInt(templateID);
        // Getting the template
        switch(templateID)
        {
            // Map
            case 1:
                tag = '<a href="../../Templates/Map/review.php?doc=' + json["documentID"] + '&col=' + collection + '">' + json["libraryIndex"] + '</a>';
                break;
            // Folder
            case 2:
                tag = '<a href="../../Templates/Folder/review.php?doc=' + json["documentID"] + '&col=' + collection +'">' + json["libraryIndex"] + '</a>';
                break;
            // Field Book
            case 3:
                tag = '<a href="../../Templates/FieldBook/review.php?doc=' + json["documentID"] + '&col=' + collection + '">' + json["libraryIndex"] + '</a>';
                break;
            // Indices
            case 4:
                //tags.push(data);
                break;
            default:
                break;
        }
        return tag;
    }

    $('#updateTicket').submit(function(event) {
        // Prevent default behavior
        event.preventDefault();
        var data = $('#updateTicket').serializeArray();

        $.ajax({
            url: "./ticket_operations.php",
            method: "POST",
            data: {update: true, data: data},
            success:function(response)
            {
                response = JSON.parse(response);
                console.log(response);
                if(response === true)
                {
                    // Adding text to title
                    $('#responseModalTitle').text("Ticket " + data[0]["value"] + " has been updated");
                    $('#status').val(true);

                    // Adding text to response modal
                    $('#responseModalBody').empty();
                    $('#responseModalBody').append("<p>Your changes have been saved!</p>");
                    $("#rowModal").modal('hide');
                    $('#responseModal').modal('show');
                }

                else if(response["message"] != null)
                {
                    // Adding text to title
                    $('#responseModalTitle').text("Ticket " + data[0]["value"] + " has failed to update");
                    $('#status').val(false);

                    // Adding text to response modal
                    $('#responseModalBody').empty();
                    $('#responseModalBody').append("<p>Your changes could not be saved! " + response["message"] + "</p>");
                    $("#rowModal").modal('hide');
                    $('#responseModal').modal('show');
                }

                else
                {
                    // Adding text to title
                    $('#responseModalTitle').text("Ticket " + data[0]["value"] + " has failed to update");
                    $('#status').val(false);

                    // Adding text to response modal
                    $('#responseModalBody').empty();
                    $('#responseModalBody').append("<p>Your changes could not be saved! The server is not responding properly, please report this bug.</p>");
                    $("#rowModal").modal('hide');
                    $('#responseModal').modal('show');
                }
            }
        });

    });

    $('#delete').click(function(event) {
        var answer = confirm("Are you sure you want to delete this ticket?");
        var data = $('#updateTicket').serializeArray();
        // Confirm returns true if the user clicks the okay button
        if(answer === true)
        {
            $.ajax({
                url: "./ticket_operations.php",
                method: "POST",
                data: {delete: true, data: data},
                success:function(response)
                {
                    console.log(response);
                    response = JSON.parse(response);

                    if(response === true)
                    {
                        // Adding text to title
                        $('#responseModalTitle').text("Ticket " + data[0]["value"] + " has been deleted");
                        $('#status').val(true);

                        // Adding text to response modal
                        $('#responseModalBody').empty();
                        $('#responseModalBody').append("<p>The ticket was deleted successfully!</p>");
                        $("#rowModal").modal('hide');
                        $('#responseModal').modal('show');
                    }

                    else
                    {
                        // Adding text to title
                        $('#responseModalTitle').text("Ticket " + data[0]["value"] + " could not be deleted");
                        $('#status').val(false);

                        // Adding text to response modal
                        $('#responseModalBody').empty();
                        $('#responseModalBody').append("<p>The ticket could not be deleted! The server is not responding properly, please report this bug.</p>");
                        $("#rowModal").modal('hide');
                        $('#responseModal').modal('show');
                    }
                }
            })
        }
    });

    // When the reponse modal is clicked closed or is forced to hide, this function executes
    $('#responseModal').on('hidden.bs.modal', function () {
        // This will reload the page if a ticket was modified or deleted
        if($('#status').val() === "true")
        {
            location.reload();
        }
    });

    function resetTable()
    {
        $('#dtable').DataTable().clear().destroy();
        showTable();
    }
</script>
</body>
</html>


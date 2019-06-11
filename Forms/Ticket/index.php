<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin()) {
    require('../../Library/DBHelper.php');
    require('../../Library/Ticket.php');
    $DB = new DBHelper();
    $ticket = new Ticket();
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
<div class="container pad-bottom">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center" id="page_title">Tickets</h1>
            <hr>
            <table id="dtable" class="table table-bordered table-hover" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th></th>
                    <th>Collection</th>
                    <th>Library Index</th>
                    <th>Date Submitted</th>
                    <th>Solved</th>
                    <th>Last Seen</th>
                    <th>Error</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Collection</th>
                    <th>Library Index</th>
                    <th>Date Submitted</th>
                    <th>Solved</th>
                    <th>Last Seen</th>
                    <th>Error</th>
                    <th>Status</th>
                </tr>
                </tfoot>
            </table>
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

<!-- Datatables CDN -->
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

<!-- Bootstrap JS files for datatables CDN -->
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>

<!-- Page's local script -->
<script>
    $(document).ready(function() {


        //hide first column (DocID)
        //table.column(0).visible(true);
        //showTable();
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
            "order": [[ 6, "desc" ], [2, "desc"]],
            /*"initComplete": function () {
                console.log("Table done loading...");
            },*/
            // Getting select statement
            "ajax": "./table_processing.php",
            "columns": [
                {"data": 'ticketID'},
                {"data": 'displayname'},
                {
                    "data": 'subject',
                    "render": function(data, type, row, meta) {
                        //console.log(data);
                        //console.log(type);
                        console.log(row);
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
                {"data": 'submissiondate'},
                {"data": 'solveddate'},
                {"data": 'lastseen'},
                {"data": 'error'},
                {
                    "data": 'status',
                    "render":function(data, type, row, meta)
                    {
                        data = parseInt(data);
                        if(data === 1)
                        {
                            return "Closed";
                        }

                        else
                        {
                            return "Open";
                        }
                    }
                }
            ],
            "initComplete": function() {
                this.api().columns().every( function () {
                    var column = this;
                    switch(column[0][0]) //column number
                    {
                        case 0:
                            break;

                        //search text box
                        case 1:
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
                                    console.log(d);
                                    select.append( $('<option value="'+d+'">'+d+'</option>') );
                                } );
                            break;
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
            window.open("./ticketview.php?id=" + data.ticketID);
        });
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
</script>
</body>
</html>

<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
require('../../Library/DBHelper.php');
$DB = new DBHelper();
$userid = $session-> getUserID();
$userticketCount = $DB->GET_USER_CLOSEDTICKET_COUNT($userid);
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
            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th></th>
                    <th>Collection</th>
                    <th>Subject / Library Index</th>
                    <th>Submitted Date</th>
                    <th>Solved Date</th>
                    <th>Last Seen</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
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
<!-- Page's local script -->
<script>
    $(document).ready(function() {
        var table = $('#dtable').DataTable( {
            "processing": true,
            "serverSide": true,
            "aaSorting": [ [4,'asc'], [3,'desc'] ],
            "lengthMenu": [20, 40 , 60, 80, 100],
            "bStateSave": false,
            "columnDefs": [
                //column Ticket Index: Replace with Hyperlink
                {
                    "render": function ( data, type, row ) {
                        return "<a href='userticketview.php?id=" + data + "' target='_blank' >View</a>" ;
                    },
                    "targets": 0
                },
                //column Collection
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 1
                },
                //column Subject
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 2
                },
                //column : Submitted Date
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 3
                },
                //column : Solved Date
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 4
                },
                //column : Last Seen Date
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 5
                },

                //column : Status
                {
                    "render": function ( data, type, row ) {
                        var solveddate = row[4]
                        var lastseen = row[5];
                        if(solveddate > lastseen){
                            var Note = "NEW"
                            if(data == 1)
                                return "<a class='notificationBadge' data-badge="+ Note +" id='userNotificationBadge' >Closed </a>" ;
                            return "<a class='notificationBadge' data-badge="+ Note +" id='userNotificationBadge' >Open </a>" ;
                        }
                        else {
                            if(data == 1)
                                return "<a>Closed </a>" ;
                            return "<a>Open </a>" ;
                        }
                    },
                    "targets": 6
                },

            ],
            "ajax": "list_processing.php",
            "initComplete": function() {
                this.api().columns().every( function () {
                    var column = this;
                    switch(column[0][0]) //column number
                    {
                        //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
                        case 6: //Status column
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
                        //search text box
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
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
                    }
                } );
            },
        } );

        //hide first column (DocID)
        table.column(0).visible(true);


        // select row on single click
        $('#dtable tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );
    });


    window.onload = check;
</script>
</body>
</html>


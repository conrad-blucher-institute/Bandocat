<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin()) {
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
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
            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th></th>
                    <th>Collection</th>
                    <th>Subject / Library Index</th>
                    <th>Submitted Date</th>
                    <th>Submitter</th>
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

<!-- Page Level Script -->
<script>
    $(document).ready(function() {
        var docID = '';

        var table = $('#dtable').DataTable( {
            "processing": true,
            "serverSide": true,
            "lengthMenu": [20, 40 , 60, 80, 100],
            "bStateSave": false,
            "aaSorting": [ [5,'asc'], [3,'desc'] ],
            "columnDefs": [
                //column Ticket Index: Replace with Hyperlink
                {
                    "render": function ( data, type, row ) {
                        return "<a href='ticketview.php?id=" + data + "' target='_blank' >View</a>" ;
                    },
                    "targets": 0
                },
                //column Collection
                {
                    "render": function ( data, type, row ) {
                        //Object that stores the collection name
                        colData = data;
                        return data;
                    },
                    "targets": 1
                },
                //column Subject
                {
                    "render": function ( data, type, row ) {
                        //Stores the collection name to the subject collection variable
                        switch(colData) {
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
                            case 'PennyFenner':
                                var dbCol = 'pennyfenner';
                                var file = 'Map';
                                break;
                            case 'Map Indices':
                                var dbCol = 'mapindices';
                                var file = 'Indices';
                                break;
                        }
                        //Object with subject collection and subject/library index
                        var subCol = {"data":[{"subjectCol": dbCol, "subject": data}]};
                        console.log(subCol);
                        $.ajax({
                            url: 'ticketLink.php',
                            type: 'post',
                            data: subCol,
                            success: function (id) {
                                id = JSON.parse(id);
                                var td = $('td:contains('+data+')')[0];
                                if(id.data[0][0] != false)
                                    $(td).html("<a href='../../Templates/" + file + "/review.php?doc=" + id.data[0][0] + "&col=" + dbCol + "' target='_blank' >"+ id.data[0][1] +"</a>");
                            }
                        });
                        return data
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
                //column : Poster
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 4
                },
                //column : Status
                {
                    "render": function ( data, type, row ) {
                        if(data == 1)
                            return 'Closed';
                        return 'Open';
                    },
                    "targets": 5
                },

            ],
            "ajax": "list_processing.php",
            "initComplete": function() {
                this.api().columns().every( function () {
                    var column = this;
                    switch(column[0][0]) //column number
                    {
                        //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
                        case 5: //Status column
                            var select = $('<select style="width:100%"><option value="">Filter...</option><option value="0">Open</option><option value="1">Closed</option></select>')
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
                            var input = $('<input type="text" style="width:100%" placeholder="Search..." value=""></input>')
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

        //sorted by submission date
//            table
//                .column( '3:visible' )
//                .order( 'desc' )
//                .draw();

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
</script>
</body>
</html>

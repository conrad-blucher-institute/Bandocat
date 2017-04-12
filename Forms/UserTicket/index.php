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
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ticket</title>

    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <link rel = "stylesheet" type="text/css" href="../../ExtLibrary/DataTables-1.10.12/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/DataTables-1.10.12/js/jquery.dataTables.min.js"></script>
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
                            case 5:
                            case 6:
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

            //resize height of the scroller
            $("#divscroller").height($(window).outerHeight() - $(footer).outerHeight() - $("#page_title").outerHeight() - 35);
        });


        window.onload = check;
    </script>
</head>
<body>
<div id="wrap">
    <div id="main">
        <div id="divleft">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </div>
        <div id="divright">
            <h2 id="page_title">Ticket</h2>
            <div id="divscroller">
                <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" width="100%" data-page-length='20'>
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
            </div>
        </div>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>
</body>
</html>

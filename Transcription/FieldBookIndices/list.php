<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col'])) {
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
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

    <!-- Bootstrap CDN Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" crossorigin="anonymous">

    <!-- Font Awesome CDN CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <title>Map Indices Transcription</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center" id="page_title">Title</h1>
            <hr>
            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th></th>
                    <th>Page Type</th>
                    <th>Library Index</th>
                    <th>Book Title</th>
                    <th>Page #</th>
                    <th>Needs Review</th>
                    <th>Completed?</th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Page Type</th>
                    <th>Library Index</th>
                    <th>Book Title</th>
                    <th>Page #</th>
                    <th>Needs Review</th>
                    <th>Completed?</th>
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

<!-- This page's script -->
<script>
    $(document).ready(function() {

        var collection_config = <?php echo json_encode($config); ?>;
        $('#page_title').text(collection_config.DisplayName + " Transcription");


        var table = $('#dtable').DataTable( {
            "processing": true,
            "serverSide": true,
            "lengthMenu": [20, 40 , 60, 80, 100],
            "bStateSave": false,
            "order": [[ 3, "asc" ],[0, "asc"]],
            "columnDefs": [
                //column ID
                {
                    "className": "dt-center",
                    "render": function ( data, type, row ) {
                        return '<a href="../../Templates/Indices/review.php?col=' + collection_config.Name + '&doc=' + data + '">Edit/Review</a>';
                    },
                    "targets": 0
                },
                //column Page Number
                {
                    "className": "dt-right",
                    "render": function ( data, type, row ) {
                        if(data == '0' || data == 0)
                            return "";
                        return data;
                    },
                    "targets": 4
                },
                //column needs review
                {
                    "className": "dt-center",
                    "render": function ( data, type, row ) {
                        if (data == '1')
                            return "Yes";
                        return "No";
                    },
                    "targets": 5
                },
                //column Completed?
                {
                    "className": "dt-center",
                    "render": function ( data, type, row ) {
                        if(data == "1")
                            return "Yes";
                        return "No";
                    },
                    "targets": 6
                },
                //column Transcription hyperlink
                {
                    "className": "dt-center",
                    "render": function ( data, type, row ) {
                        var col = collection_config.Name;
                        return "<a href='' id='aTranscribe' " +  'onclick = "Jpg_Conversion(' + row[0] + ',\'' + data + '\',\'' + col + '\');event.preventDefault();" >Transcribe</a>';
                    },
                    "targets": 7
                },
            ],
            "ajax": "list_processing.php?col=" + collection_config.Name,
            "initComplete": function() {
                this.api().columns().every( function () {
                    var column = this;
                    switch(column[0][0]) //column number
                    {
                        //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
                        case 5: //column needsreview
                        case 6: //column completed?
                            var select = $('<select class="form-control form-control-sm"><option value="">Filter...</option><option value="1">Yes</option><option value="0">No</option></select>')
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
                        //case: dropdown table of contents/general index
                        case 1: //column page type
                            var select = $('<select class="form-control form-control-sm"><option value="">Filter...</option><option value="Table of Contents">Table of Contents</option><option value="General Index">General Index</option></select>')
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
                        case 2:
                        case 3:
                        case 4:
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
</script>

<script>
    var url = "php/IndexTiff2Jpg.php";

    function Jpg_Conversion(docId,fileName,collection)
    {
        //openModal(3);
        $.ajax({
            type: "POST",
            url: url,
            //dataType: 'json',
            data: {"docID": docId, "fileName": fileName, "collection": collection},
            success:function(data) {
                window.localStorage.setItem("fileName",data);
                window.localStorage.setItem("docID",docId);
                window.open("Transcription.php?col=" + collection );
            },
            error:function(requestObject, error, errorThrown) {
                alert(error);
                alert(errorThrown);
            }
        });
    }
</script>
</body>
</html>
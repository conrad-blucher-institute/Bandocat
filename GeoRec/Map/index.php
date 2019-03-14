<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col'])) {
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    require('../../Library/MapDBHelper.php');
    $DB = new MapDBHelper();
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

    <title>GeoRectification - <?php echo $config['DisplayName']; ?></title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container pad-bottom">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 id="page_title" class="text-center">Title</h1>
            <hr>
            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th></th>
                    <th>Library Index</th>
                    <th>Document Title</th>
                    <th>End Date</th>
                    <th>Front Map</th>
                    <th>Back Map</th>
                    <th>Rectifiability</th>
                    <th>GeoRec Front Status</th>
                    <th>GeoRec Back Status</th>
                    <th>GeoRectify</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Library Index</th>
                    <th>Document Title</th>
                    <th>End Date</th>
                    <th>Front Map</th>
                    <th>Back Map</th>
                    <th>Rectifiability</th>
                    <th>GeoRec Front Status</th>
                    <th>GeoRec Back Status</th>
                </tr>
                </tfoot>
            </table>
        </div> <!-- col -->
    </div> <!-- row -->

    <!-- Error Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" id="messageModalHeader">

                </div>
                <div class="modal-body" id="messageModalBody">

                </div>
                <div class="modal-footer" id="messageModalFooter">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="width: 15em;">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <img src="../../Images/loading2.gif">
                    </div>
                    <h5 class="text-center">Loading...</h5>
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

<!-- These are the pages script -->
<script>
    $(document).ready(function() {
        var collection_config = <?php echo json_encode($config); ?>;
        $('#page_title').text(collection_config.DisplayName + " Georectification");

        var table = $('#dtable').DataTable( {
            "processing": true,
            "serverSide": true,
            "lengthMenu": [20, 40 , 60, 80, 100],
            "bStateSave": false,
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                //column Document Index: Replace with Hyperlink, allows user to edit/review the document
                {
                    "render": function ( data, type, row ) {
                        return "<a target='_blank' href='../../Templates/Map/review.php?doc=" + data + "&col=" + collection_config['Name'] + "'>Edit/View</a>" ;
                    },
                    "targets": 0
                },
                { "searchable": false, "targets": 0 }, //disable searching by documentID
                //Library Index
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 1
                },
                //{ "searchable": false, "targets": 3 }, //disable searching by subtitle
                //column : Date
                {
                    "render": function ( data, type, row ) {
                        if(data == "00/00/0000")
                            return "";
                        return data;
                    },
                    "targets": 3
                },
                // { "searchable": false, "targets": 6 },
                //column : Filename of Front Scan (hidden)
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 4
                },
                //column : Filename of Back Scan (hidden)
                {
                    "render": function ( data, type, row ) {
                        return data;
                    },
                    "targets": 5
                },
//                    { "searchable": false, "targets": 7 }, //disable search for file name
//                    { "searchable": false, "targets": 8 }, //disable search for file name back

                //column : Rectifiability
                {
                    "render": function ( data, type, row ) {
                        switch(data)
                        {
                            case "POOR":
                                return "<span class='text-muted'>" + data + "</span>";
                            case "GOOD":
                                return "<span>" + data + "</span>";
                            case "EXCELLENT":
                                return "<span style='color:#00BC65'>" + data + "</span>";
                            default: return data;
                        }
                    },
                    "targets": 6
                },
                //column georec status
                //This column translates the GeoRec Front Status from int value to string value
                {
                    "render": function ( data, type, row ) {
                        switch(data)
                        {
                            case "0": return "<span>Not Rectified</span>";
                            case "1": return "<span style='color:#00BC65'>Rectified</span>";
                            case "2": return "<span style='color:red'>Not Rectifiable</span>";
                            case "3": return "<span style='color:darkkhaki'>Needs Review</span>";
                            case "4": return "<span style='color:darkred'>Research Required</span>";
                            default: return "<span>Unknown</span>";
                        }
                    },
                    "targets": 7
                },
                //This column translates the GeoRec Back Status from int value to string value
                {
                    "render": function ( data, type, row ) {
                        if(row[5] == "")
                            return "";
                        switch(data)
                        {
                            case "0": return "<span>Not Rectified</span>";
                            case "1": return "<span style='color:#00BC65'>Rectified</span>";
                            case "2": return "<span style='color:red'>Not Rectifiable</span>";
                            case "3": return "<span style='color:darkkhaki'>Needs Review</span>";
                            case "4": return "<span style='color:darkred'>Research Required</span>";
                            default: return "<span>Unknown</span>";
                        }
                    },
                    "targets": 8
                },
                //columnn georectify
                {
                    "render": function ( data, type, row ) {
                        switch(row[5]) //based on Georec Status column (column 8)
                        {
                            default:
                                var type1 = "front";
                                var type2 = "back";
                                return "<a href='' id='aRecFront' onclick='makeTiles(" + '"' + collection_config['Name'] + '"' + "," + row[0] + "," + '"' + type1 + '"' + ");event.preventDefault();'>Front</a>" +
                                    " | "  + "<a href='' id='aRecFront' onclick='makeTiles(" + '"' + collection_config['Name'] + '"' + "," + row[0] + "," + '"' + type2 + '"' + ");event.preventDefault();'>Back</a>";
                            case "": //no back
                                var type1 = "front";
                                return "<a href='' id='aRecFront' onclick='makeTiles(" + '"' + collection_config['Name'] + '"' + "," + row[0] + "," + '"' + type1 + '"' + ");event.preventDefault();'>Front</a>";
                        }
                    },
                    "targets": 9
                }


            ],
            "ajax": "list_processing.php?col=" + collection_config.Name,
            "initComplete": function() {
                this.api().columns().every( function () {
                    var column = this;
                    switch(column[0][0]) //column number
                    {
                        //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
//                            case 9: //column POI
//                                var select = $('<select style="width:100%"><option value="">Filter...</option><option value="1">Yes</option><option value="0">No</option></select>')
//                                    .appendTo( $(column.footer()).empty() )
//                                    .on( 'change', function () {
//                                        var val = $.fn.dataTable.util.escapeRegex(
//                                            $(this).val()
//                                        );
//
//                                        column
//                                            .search(val)
//                                            .draw();
//                                    } );
//                                break;
                        //case: GeoRec Front/Back status columns
                        case 7: //column GeoRec Front Status
                        case 8: //column GeoRec Back Status
                            var select = $('<select class="form-control"><option value="">Filter...</option><option value="0">Not Rectified</option><option value="1">Rectified</option><option value="2">Not Rectifiable</option><option value="3">Needs Review</option><option value="4">Research Required</option></select>')
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
                        //case: columns have limited unique values
                        case 6:
                            var select = $('<select class="form-control"><option value="">Filter...</option><option value="POOR">POOR</option><option value="GOOD">GOOD</option><option value="EXCELLENT">EXCELLENT</option></select>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val)
                                        .draw();
                                } );

                            column.data().unique().sort().each( function ( d, j ) {
                                //select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                            break;
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                            var input = $('<input type="text" class="form-control" placeholder="Search..." value=""></input>')
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
        table.column(4).visible(false); //hide file name col
        table.column(5).visible(false); // hide file name back col
        <?php if($session->hasWritePermission()){ ?> table.column(8).visible(true); <?php }//geo rectify only visible for writer ?>
        // show or hide subtitle
//            table.column(3).visible(false);
//            $('#checkbox_subtitle').change(function (e) {
//                e.preventDefault();
//                // Get the column API object
//                var column = table.column(3);
//                // Toggle the visibility
//                column.visible( ! column.visible() );
//            } );

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
    //This function generate tiles and return information about the image for georectification
    //Parameters:
    //collection: collection's parameter name
    //docID: unique value, use to access document information on server side
    //type : specify front or back scan of the document
    function makeTiles(collection,docID,type){
        $("#loaderModal").modal('show');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "php/tileCreator.php",
            data: {"collection": collection,"docID": docID,"type": type},
            success:function(data) {
                window.localStorage.setItem("imageInfo", JSON.stringify(data));
                $('#loaderModal').modal('hide');
                window.open("georec.php?col="+collection+"&docID="+docID+"&type="+type);
                //alert(data);
            },
            error:function(requestObject, error, errorThrown) {
                // Hiding loader modal
                $('#loaderModal').modal('hide');

                // Appending content to message modal
                $('#messageModalBody').append("<p>An error has occurred!</p>");
                $('#messageModalHeader').append('<h5 class="modal-title">Error</h5>');
                $('#messageModal').modal('show');
            }
        });
    }
    //this function shows modal (usually contains a spinner gif) to notify user that a process is loading
    function openModal(num) {
        document.getElementById('modal_' + num).style.display = 'block';
        document.getElementById('fade_' + num).style.display = 'block';
    }
    //this function hides the loading modal
    function closeModal(num) {
        document.getElementById('modal_' + num).style.display = 'none';
        document.getElementById('fade_' + num).style.display = 'none';
    }
</script>
</body>
</html>

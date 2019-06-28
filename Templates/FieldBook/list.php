<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
//Get collection name and action
if(isset($_GET['col']) && isset($_GET['action']))
{
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    //get appropriate db
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
    $action = $_GET['action'];
    if($action == "catalog")
        $title = "Catalog";
    else $title = "Review";
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

    <title><?php echo $title; ?> List</title>

    <!-- Bootstrap CDN Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" crossorigin="anonymous">

    <!-- Font Awesome CDN CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center" id="page_title"></h1>
            <hr>
            <div class="m-3">
                <!-- The Datatable -->
                <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                    <thead>
                    <tr>
                        <th></th>
                        <th>Book Title</th>
                        <th>Library Index</th>
                        <th>Job Title</th>
                        <th>Needs Input</th>
                        <th>Needs Review</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Book Title</th>
                        <th>Library Index</th>
                        <th></th>
                        <th>Needs Input</th>
                        <th>Needs Review</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div> <!-- col-->
    </div> <!-- row -->
</div><!-- Container -->
<?php include "../../Master/bandocat_footer.php" ?>

<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- Datatables CDN -->
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

<!-- Bootstrap JS files for datatables CDN -->
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>

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
        var docHeight = $(window).height() - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
        {
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
        }
    });
</script>

<!-- Script for this page -->
<script>
    /**********************************************
     * Function:  DeleteDocument
     * Description: deletes the document from the database
     * Parameter(s):
     * col (in string) - name of the collection
     * id (in Int) - document id
     * Return value(s):
     * $result true if success, false if failed
     ***********************************************/
    function DeleteDocument(col,id)
    {
        $response = confirm('Are you sure you want to delete this document?');
        if($response)
        {
            //send to form_processing the information in the data: folder
            $.ajax({
                type: 'post',
                url: 'form_processing.php',
                data: {"txtAction": "delete", "txtCollection": col, "txtDocID": id},
                success:function(data){
                    var json = JSON.parse(data);
                    var msg = "";
                    for(var i = 0; i < json.length; i++)
                        msg += json[i] + "\n";
                    alert(msg);
                    $('#dtable').DataTable().ajax.reload();
                }
            });
        }
    }
    //When the document is ready, begin the rendering of the datatable
    $(document).ready(function()
    {
        var collection_config = <?php echo json_encode($config); ?>;
        var action = '<?php echo $action; ?>';
        $('#page_title').text(collection_config.DisplayName);

        var table = $('#dtable').DataTable(
            {
                "processing": true,
                "serverSide": true,
                "lengthMenu": [20, 40 , 60, 80, 100],
                "bStateSave": false,
                "order": [[ 0, "desc" ]],
                "columnDefs":
                    [
                        //column Document Index: Replace with Hyperlink
                        {
                            //If the action is catalog or if the action is review
                            "render": function ( data, type, row )
                            {
                                if(action == "catalog")
                                    var ret = "<a target='_blank' href='catalog.php?doc=" + data + "&col=" + collection_config['Name'] + "'>Catalog</a>" ;
                                else var ret = "<a target='_blank' href='review.php?doc=" + data + "&col=" + collection_config['Name'] + "'>Edit/View</a>" ;
                                return ret;
                            },
                            "targets": 0
                        },
                        { "width": 30, "targets": 0 },
                        { "searchable": false, "targets": 0 },
                        { "width": 30, "targets": 1 },
                        { "width": 200, "targets": 2 },
                        //column : NeedsInput
                        {
                            "render": function ( data, type, row ) {
                                if(data == 1)
                                    return "Yes";
                                return "No";
                            },
                            "targets": 4
                        },
                        //column : NeedsReview
                        {
                            "render": function ( data, type, row ) {
                                if(data == 1)
                                    return "Yes";
                                return "No";
                            },
                            "targets": 5
                        },
                        { "width": 20, "targets": 5 },
                        {
                            "render": function ( data, type, row )
                            {
                                return "<a href='#' onclick='DeleteDocument(" + JSON.stringify(collection_config.Name) + "," + row[0] + ")'>Delete</a>";
                            },
                            "targets": 6
                        },
                        { "width": 30, "targets": 6 },

                    ],
                "ajax": "list_processing.php?col=" + collection_config.Name + "&action=" + '<?php echo $action; ?>',
                "initComplete": function()
                {
                    this.api().columns().every( function () {
                        var column = this;
                        switch(column[0][0]) //column number
                        {
                            //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
                            case 4: //needs input
                            case 5: //column needsreview
                                var select = $('<select style="width:100%"><option value="">Filter...</option><option value="1">Yes</option><option value="0">No</option></select>')
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
                                var input = $('<input type="text" style="width:100%" placeholder="Search..." value="">')
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
                }
            } );

        //hide first column (DocID)
        table.column(0).visible(true);
        //hides the columns responsible for need's input
        table.column(6).visible(false);
        if(action == "catalog")
            table.column(5).visible(false);
        else table.column(4).visible(false);
        //only if admin is loged in, we show the 6th column
        <?php if($session->isAdmin()){ ?> table.column(6).visible(true); <?php } ?>
    });
</script>
</body>
</html>
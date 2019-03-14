<?php
/* PHP INCLUDES */
include '../../Library/SessionManager.php';
$session = new SessionManager();
require('../../Library/DBHelper.php');
$DB = new DBHelper();
require('../../Library/ControlsRender.php');
$Render = new ControlsRender();
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


    <title>Thumbnail Creator</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">Thumbnail Creator</h1>
            <hr>

            <!-- Form responsible for the select drop down menu -->
            <form id = "form" name="form" method="post">
                Select Collection:
                <!-- <select name="ddlCollection" id="ddlCollection" onchange="Calculate(this.value)"> -->
                <select name="ddlCollection" id="ddlCollection">

                    <!-- Renders the Dropdownlist with the collections -->
                    <?php $Render->GET_DDL_COLLECTION($DB->GET_COLLECTION_FOR_DROPDOWN_FROM_TEMPLATEID(array(1),true),null);?>

                </select>
            </form>
            <!-- Displays the count of maps -->
            <h4 id="txt_counter" ></h4>

            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th></th>
                    <th>Library Index</th>
                    <th>Document Title</th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
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
<!-- Page level script -->
<script>
    var selected = false;
    var table;
    /*******************************************
     * Function responsible for calling Jquery.
     * DataTables to render and load the database
     * items.
     *******************************************/
    function SSP_DataTable(collection)
    {

        console.log(collection);

        // document.getElementById("loader_2").style.visibility = "visible";
        //create new DataTable with 6 parameters and assign table to #dtable
        //options can be found at https://datatables.net/reference/option/
        table = $('#dtable').DataTable( {
            //Enables display of a processing indicator
            "processing": true,
            //Toggles serverside processing
            "serverSide": true,
            //Specifys the entries in the length dropdown select list
            "lengthMenu": [20, 40 , 60, 80, 100],
            "bStateSave": false,
            //Initialise a datatable as usual, but if there is an existing table which matches the selector
            //it will be destroyed and replaced with the new table
            "destroy": true,
            //Allows you to assign specific options to columns in the table
            "columnDefs":
                [
                    //column Document Index: Replace with Hyperlink
                    {
                        "render": function ( data, type, row ) {
                            return "<a target='_blank' href=''>Edit/View</a>" ;
                        },
                        "targets": 0
                    },
                    { "searchable": false, "targets": 0 },
                    { "visible": false, "targets": 0 },
                    //column Title
                    {
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 2
                    },
                    { "searchable": true, "targets": 2 },

                    // { "searchable": false, "targets": 7 },
                    {
                        "render": function ( data, type, row ) {
                            return "<a href='#' onclick=''>Create Thumbnail</a>";
                        },
                        "targets": 3
                    }
                ],
            //Use ajax to pass data to the table. collection contains the db info

            "ajax":
                {
                    url: "thumbnail_processing.php?col=" +collection
                    , dataType: "json"
                    ,  complete: function() {
                        //  document.getElementById("loader_2").style.visibility = "hidden";
                    }
                    , error: function (xhr, error, thrown)
                    {
                        alert("An error occurred while attempting to retrieve data via ajax.\n"+thrown );
                    }

                },
            "initComplete": function() {
                this.api().columns().every( function ()
                {
                    var column = this;
                    switch(column[0][0]) //column number
                    {
                        //cases: search textbox
                        case 0:
                        case 1:
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
                        case 2:
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
                        //case search by status

                    }
                } );
            }
        } );

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
    }

    //On page ready, pass elementIDddlCollections value into the SSP_DataTable Function
    $(document).ready(function()
    {

        $( "#ddlCollection" ).change(function() {
            switch ($("#ddlCollection").val())
            {



                case "": break;
                default: SSP_DataTable($("#ddlCollection").val());



            }
        });


    });
    function onClickCalculate(event,Query)
    {
        //This function is responsible for generating thumbnails

    }
</script>
</body>
</html>
<?php
/* PHP INCLUDES */
spl_autoload_register(function ($class_name) {
    require_once "../../Library/" . $class_name . '.php';});
$session = new SessionManager();
$DB = new DBHelper();
$Render = new ControlsRender();
$baseUrl; //store the TDL REST URL
$ini_dir = "../../BandoCat_config/tdlconfig.ini";
 $root = substr(getcwd(),0,strpos(getcwd(),"htdocs\\")); //point to xampp// directory
 $config = parse_ini_file($root . $ini_dir);
 $baseUrl = $config['baseURL'];
//This page allows to push/pop documents to the TDL publishing Queue in BandoCat, Unpublish/Update Published document in TDL Server
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

    <title>TDL Publishing</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container mb-3">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">TDL Publishing</h1>
            <hr>
            <!-- Form responsible for the select drop down menu -->
            <form id = "form" name="form" method="post">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Select Collection:</label>
                    <div class="col-sm-2">
                        <select class="form-control form-control-sm" name="ddlCollection" id="ddlCollection">
                            <!-- Renders the Dropdownlist with the collections -->
                            <?php $Render->GET_DDL_COLLECTION($DB->GET_COLLECTION_FOR_DROPDOWN_FROM_TEMPLATEID(array(4),false),"bluchermaps");?>
                        </select>
                    </div>
                </div>
            </form>
            <!-- Displays the count of maps -->
            <h4 id="txt_counter" ></h4>
            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th>ID</th>
                    <th id="DT_identifier">Library Index</th>
                    <th>Status</th>
                    <th>Dspace URI</th>
                    <th>Dspace ID</th>
                    <th>Action</th>
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

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
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
<script>
    /*******************************************
     * Function responsible for calling Jquery.
     * DataTables to render and load the database
     * items.
     *******************************************/
    function SSP_DataTable(collection)
    {
        if(collection == "blucherfieldbook")
        {
            //create new DataTable with 6 parameters and assign table to #dtable
            //options can be found at https://datatables.net/reference/option/
            var table = $('#dtable').DataTable( {
                //Enables display of a processing indicator
                "processing": true,
                //Toggles serverside processing
                "serverSide": true,
                //Specifys the entries in the length dropdown select list
                "lengthMenu": [20, 40 , 60, 80, 100],
                "stateSave": true,
                //Initialise a datatable as usual, but if there is an existing table which matches the selector
                //it will be destroyed and replaced with the new table
                "destroy": true,
                "columnDefs":
                    [
                        //column Document Index: Replace with Hyperlink // this is the ID
                        {
                            "className": "dt-center",
                            "render": function ( data, type, row )
                            {

                                return data;
                            },
                            "width": "0%",
                            "visible": false,
                            "targets": 0


                        },
                        {
                            "className": "dt-center",
                            "render": function ( data, type, row ) {
                                switch(data)
                                {
                                    case "0": return "Not Published";
                                    case "1": return "Published";
                                    case "2": return "In Queue";
                                    case "-1": return ""; // Unpublished???
                                    case "10": //publishing front
                                        return "Publishing...";
                                    case "11":  //publishing back
                                        return "Continue Publishing...";
                                    default: return "Unknown code: " + data;
                                }
                            },
                            "targets": 2
                        },
                        {
                            "className": "dt-center",
                            "render": function ( data, type, row ) {
                                return data;
                            },
                            "targets": 3
                        },
                        {
                            "className": "dt-right",
                            "render": function ( data, type, row ) {
                                if(data == "0")
                                    return "";
                                return data;
                            },
                            "targets": 4
                        },
                        {
                            "render": function ( data, type, row ) {
                                switch(row[2])
                                {

                                    case "0": return "<a href='' onclick='performAction(event," + '"push"' + "," + row[0] +")'>Push</a>";//publish
                                    case "1": return "<a href='' onclick='performAction(event," + '"view"' + "," + row[0] +")'>View</a>" + " | <a href=./index_bitstreams.php?docID=" + row[0] + "&col=" + document.getElementById('ddlCollection').value + "> Bitstreams</a> | <a href='' onclick='performAction(event," + '"update"' + "," + row[0] +")'>Update</a> | <a href='' onclick='performAction(event," + '"unpublish"' + "," + row[0] +")'>Unpublish</a>"
                                    case "2": return "<a href='' onclick='performAction(event," + '"pop"' + "," + row[0] +")'>Pop</a>"; //in publish queue
                                    case "-1": return "<a href=''>Pop</a>"; //in unpublish queue????
                                    case "10": //publishing front map
                                    case "11": //publishing back map
                                        return "Publishing... | <a href='' onclick='performAction(event," + '"unpublish"' + "," + row[0] +")'>Unpublish</a>"; //publishing
                                    default: return "";
                                }
                            },
                            "targets": -1
                        }
                    ],
                //Use ajax to pass data to the table. collection contains the db info
                "ajax": "list_processing.php?col=" + collection,
                "initComplete": function() {
                    this.api().columns().every( function () {
                        var column = this;
                        switch(column[0][0]) //column number
                        {
                            //cases: search textbox
                            case 0:
                            case 1:
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
                            //case search by status
                            case 2:
                                var select = $('<select style="width:100%"><option value="">Filter...</option><option value="0">Not Published</option><option value="1">Published</option><option value="2">In Queue</option><option value="10|11">Publishing</option></select>')
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
                                break;
                        }
                    } );
                }
            } );
        }
        else
        {
            //create new DataTable with 6 parameters and assign table to #dtable
            //options can be found at https://datatables.net/reference/option/
            var table = $('#dtable').DataTable( {
                //Enables display of a processing indicator
                "processing": true,
                //Toggles serverside processing
                "serverSide": true,
                //Specifys the entries in the length dropdown select list
                "lengthMenu": [20, 40 , 60, 80, 100],
                "stateSave": true,
                //Initialise a datatable as usual, but if there is an existing table which matches the selector
                //it will be destroyed and replaced with the new table
                "destroy": true,
                "columnDefs":
                    [
                        //column Document Index: Replace with Hyperlink // this is the ID
                        {
                            "className": "dt-center",
                            "render": function ( data, type, row ) {
                                return data;
                            },
                            "targets": 0

                        },
                        {
                            "className": "dt-center",
                            "render": function ( data, type, row ) {
                                switch(data)
                                {
                                    case "0": return "Not Published";
                                    case "1": return "Published";
                                    case "2": return "In Queue";
                                    case "-1": return ""; // Unpublished???
                                    case "10": //publishing front
                                        return "Publishing...";
                                    case "11":  //publishing back
                                        return "Continue Publishing...";
                                    default: return "Unknown code: " + data;
                                }
                            },
                            "targets": 2
                        },
                        {
                            "className": "dt-center",
                            "render": function ( data, type, row ) {
                                return data;
                            },
                            //"width": "10%",
                            "targets": 3
                        },
                        {
                            "className": "dt-right",
                            "render": function ( data, type, row ) {
                                if(data == "0")
                                    return "";
                                return data;
                            },
                            //"width": "10%",
                            "targets": 4
                        },
                        {
                            "render": function ( data, type, row ) {
                                switch(row[2])
                                {

                                    case "0": return "<a href='' onclick='performAction(event," + '"push"' + "," + row[0] +")'>Push</a>";//publish
                                    case "1": return "<a href='' onclick='performAction(event," + '"view"' + "," + row[0] +")'>View</a>" + " | <a href=./index_bitstreams.php?docID=" + row[0] + "&col=" + document.getElementById('ddlCollection').value + "> Bitstreams</a> | <a href='' onclick='performAction(event," + '"update"' + "," + row[0] +")'>Update</a> | <a href='' onclick='performAction(event," + '"unpublish"' + "," + row[0] +")'>Unpublish</a>"
                                    case "2": return "<a href='' onclick='performAction(event," + '"pop"' + "," + row[0] +")'>Pop</a>"; //in publish queue
                                    case "-1": return "<a href=''>Pop</a>"; //in unpublish queue????
                                    case "10": //publishing front map
                                    case "11": //publishing back map
                                        return "Publishing... | <a href='' onclick='performAction(event," + '"unpublish"' + "," + row[0] +")'>Unpublish</a>"; //publishing
                                    default: return "";
                                }
                            },
                            "targets": -1
                        }
                    ],
                //Use ajax to pass data to the table. collection contains the db info
                "ajax": "list_processing.php?col=" + collection,
                "initComplete": function() {
                    this.api().columns().every( function () {
                        var column = this;
                        switch(column[0][0]) //column number
                        {
                            //cases: search textbox
                            case 0:
                            case 1:
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
                            //case search by status
                            case 2:
                                var select = $('<select style="width:100%"><option value="">Filter...</option><option value="0">Not Published</option><option value="1">Published</option><option value="2">In Queue</option><option value="10|11">Publishing</option></select>')
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
                                break;
                        }
                    } );
                }
            } );

        }


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
    $(document).ready(function() {

        $( "#ddlCollection" ).change(function()
        {

            if($("#ddlCollection").val() == "blucherfieldbook")
            {

                document.getElementById("DT_identifier").innerHTML = "Book Title";
            }
            else
            {
                document.getElementById("DT_identifier").innerHTML = "Library Index";
            }
            switch ($("#ddlCollection").val())
            {
                case "": break;
                default: SSP_DataTable($("#ddlCollection").val());
            }
        });

        $("#ddlCollection").change();
    });

    //Description: pass action and data to index_actionprocessing.php
    //Parameter: event: to prevent Default event of the action
    //           action: type of action
    //           docID: target document ID
    function performAction(event,action,docID)
    {
        var filename = event.path[2].children[3].innerText;
        console.log(filename);
        if(action == "view")
        {
            window.open("https://tamucc-ir.tdl.org/tamucc-ir/handle/" + filename)
        }
        else if (action == "unpublish")
        {
            var r = confirm("Are you sure you wish to unpublish this item?");
            if (r == true)
            {

                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "index_actionprocessing.php",
                    data: {ddlCollection: $("#ddlCollection").val(),docID: docID, action: action},
                    success: function (data) {
                        $('#dtable').DataTable().draw();
                    }
                });
            }
            else
            {

            }
        }
        else
        {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "index_actionprocessing.php",
                data: {ddlCollection: $("#ddlCollection").val(),docID: docID, action: action},
                success: function (data) {
                    $('#dtable').DataTable().draw();
                }
            });
        }
    }
</script>
</body>
</html>
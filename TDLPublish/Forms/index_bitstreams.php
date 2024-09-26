<?php
/* PHP INCLUDES */
spl_autoload_register(function ($class_name) {
    require_once "../../Library/" . $class_name . '.php';});
$session = new SessionManager();
$DB = new DBHelper();

$Render = new ControlsRender();

if(isset($_GET['docID']) && isset($_GET['col']))
{
    $ini_dir = "../../BandoCat_config/tdlconfig.ini";
    $docID = $_GET['docID'];
	$col = $_GET['col'];
	//switch to the currently working Database
	$DB->SWITCH_DB($col);
    $TDL = new TDLPublishJob();					
	$dspaceDocInfo = $DB->PUBLISHING_DOCUMENT_GET_DSPACE_INFO($docID);
	$dspaceID = $dspaceDocInfo['dspaceID'];	
	$bitstreams = $TDL->TDL_GET_ITEM_BITSTREAMS($dspaceID); 
	
	//var_dump(json_encode($bitstreams));
	$json_bitstreams = json_encode($bitstreams);
	$root = substr(getcwd(),0,strpos(getcwd(),"htdocs\\")); //point to xampp// directory
	$config = parse_ini_file($root . $ini_dir);
    $baseUrl = $config['baseURL'];
	

}
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

    <title>TDL Publishing</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">TDL Publishing</h1>
            <hr>
            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr>
                    <th>uuid</th>
                    <th>name</th>
                    <th>type</th>
                    <th>bundleName</th>
                    <th>format</th>
                    <th>sizeBytes</th>
                    <th>Action</th>

                </tr>
                </thead>
                <tfoot>
                <tr>

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

    $( window ).resize(function() {
        var docHeight = $(window).height();
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
    function SSP_DataTable()
    {

        var testdata = <?php echo $json_bitstreams; ?>;
        console.log(testdata);
        testdata = JSON.parse(testdata);
        //console.log ( typeof testdata);
        //console.log ( testdata);
        //create new DataTable with 6 parameters and assign table to #dtable
        //options can be found at https://datatables.net/reference/option/
        var table = $('#dtable').DataTable( {
            "data": testdata,
            "columns": [
                { "data": "uuid" },
                { "data": "name" },
                { "data": "type" },
                { "data": "bundleName" },
                { "data": "format" },
                { "data": "sizeBytes" }
            ],
            "columnDefs":
                [
                    {
                        "className": "dt-right",
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 0
                    },
                    {
                        "className": "dt-left",
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 1
                    },
                    {
                        "className": "dt-left",
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 2
                    },
                    {
                        "className": "dt-left",
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 3
                    },
                    {
                        "className": "dt-left",
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 4
                    },
                    {
                        "className": "dt-left",
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 5
                    },
                    {
                        "className": "dt-left",
                        "render": function ( data, type, row )
                        {

                            //console.log(data);
                            return "<a href='' onclick='performAction(event," + '"view"'+")'>View</a>" + " | <a href='' onclick='performAction(event," + '"delete"'+")'>Delete</a>" ;//publish



                        },
                        "targets": 6
                    }
                ]
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

        SSP_DataTable();
    });

    //Description: pass action and data to index_actionprocessing.php
    //Parameter: event: to prevent Default event of the action
    //           action: type of action
    //           docID: target document ID
    function performAction(event,action)
    {
        event.preventDefault();
        var bitId = event.path[2].firstChild.textContent;
        if(action == "view")
        {
            var url = "<?php echo $baseUrl; ?>";

            window.open(url + "bitstreams/" + bitId)
        }
        else
        {
            console.log(event);
            console.log(action);
            console.log(bitId);
            var col = "<?php echo $col; ?>";
            $.ajax({
                type: "POST",
                url: "index_bitprocessing.php",
                data: {ddlCollection: col,bitID:bitId, action: action},
                success: function (data) {
                    $('#dtable').DataTable().draw();
                }
            });
        }
    }


</script>
</body>
</html>
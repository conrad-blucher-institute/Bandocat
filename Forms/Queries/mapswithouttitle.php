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
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Welcome to BandoCat!</title>

    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <link rel = "stylesheet" type="text/css" href="../../ExtLibrary/DataTables-1.10.12/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/DataTables-1.10.12/js/jquery.dataTables.min.js"></script>
</head>
<!-- END HEADER -->
<!-- HTML BODY -->
<body>
<div id="wrap">
    <div id="main">
        <!-- HTML HEADER and SIDE MENU -->
       <div id="divleft">
                    <?php include '../../Master/header.php';
                    include '../../Master/sidemenu.php' ?>
       </div>
        <div id="divright">
                    <h2 id="page_title">Maps Without Titles</h2>
                    <table width="100%" id="table-header_right">
                        <tr>
                            <td style="margin-left: 45% ;font-size:14px" colspan="20%"
                            <td style="float:left;font-size:14px" colspan="20%">
                                <!-- Form responsible for the select drop down menu -->
                                <form id = "form" name="form" method="post">
                                    Select Collection:
                                    <select name="ddlCollection" id="ddlCollection" onchange="Calculate(this.value)">
                                        <!-- Renders the Dropdownlist with the collections -->
                                        <?php $Render->GET_DDL_COLLECTION($DB->GET_COLLECTION_FOR_DROPDOWN_FROM_TEMPLATEID(array(1,2),true),null);?>
                                    </select>
                                </form>
                                <!-- Displays the count of maps -->
                                <h4 id="txt_counter" ></h4>
                        </tr>
                    </table>
                    <!-- Table responsible for displaying returned db items in a table format -->
                    <div id="divscroller">
                        <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" width="100%" data-page-length='20'>
                            <thead>
                            <tr>
                                <th width="70px"></th>
                                <th width="120px">Library Index</th>
                                <th>Document Title</th>
                                <th width="40px">Needs Review</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
        </div>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>
</body>
<!-- END BODY -->
<script>
    /**************************************
     * Calculate is responsible for counting
     * the number of maps in the selected item
     * that have the requirements needed. I.E
     * searching blutchermaps would return a
     * number 782 have coasts out of 6911. Once
     * The calculation is complete, output into
     * txt_counter
     * ************************************/
    function Calculate(Query) {
        if (Query.length == 0)
        {
            document.getElementById("txt_counter").innerHTML = "";
            return;
        }
        else
            {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txt_counter").innerHTML = this.responseText;
                }
            };
            var Action = "Title";
            xmlhttp.open("GET", "StatisticsHelper.php?col=" + Query + "&action=" + Action , true);
            xmlhttp.send();
             }
    }
</script>
<script>
    /*******************************************
     * Function responsible for calling Jquery.
     * DataTables to render and load the database
     * items.
     *******************************************/
    function SSP_DataTable(collection)
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
            "bStateSave": false,
            //Initialise a datatable as usual, but if there is an existing table which matches the selector
            //it will be destroyed and replaced with the new table
            "destroy": true,
            "columnDefs": [
                //column Document Index: Replace with Hyperlink
                {
                    "render": function ( data, type, row ) {
                        return "<a target='_blank'  href='../../index.php?doc=" + data + "&col=" + $('#ddlCollection').val() + "&pagekey=review'>Edit/View</a>" ;
                    },
                    "targets": 0
                },
                //column needs review
                {
                    "render": function ( data, type, row ) {
                        if(data == 1)
                            return "Yes";
                        return "No";
                    },
                    "targets": 3
                },
                {
                    "render": function ( data, type, row ) {
                        if(data == 1)
                            return "Yes";
                        return "No";
                    },
                    "targets": 4
                },{ "searchable": false, "targets": 4, "visible": false }],
            //Use ajax to pass data to the table. collection contains the db info
            "ajax": "mapswithouttitle_processing.php?col=" + collection
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
        //resize height of the scroller
        $("#divscroller").height($(window).outerHeight() - $(footer).outerHeight() - $("#table-header_right").outerHeight() - $("#page_title").outerHeight() - 55);
    }

    //On page ready, pass elementIDddlCollections value into the SSP_DataTable Function
    $(document).ready(function() {

        $( "#ddlCollection" ).change(function() {
            switch ($("#ddlCollection").val())
            {
                case "": break;
                default: SSP_DataTable($("#ddlCollection").val());

            }
        });
    });


</script>
</html>
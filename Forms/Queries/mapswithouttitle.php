<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
require('../../Library/DBHelper.php');
$DB = new DBHelper();
require('../../Library/ControlsRender.php');
$Render = new ControlsRender();
?>
<!doctype html>
<html lang="en">
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
<body>
<div id="wrap">
    <div id="main">
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

                                <form id = "form" name="form" method="post">
                                    Select Collection:
                                    <select name="ddlCollection" id="ddlCollection" onchange="Calculate(this.value)"><?php $Render->GET_DDL_COLLECTION($DB->GET_COLLECTION_FOR_DROPDOWN(),null);
                                        ?></select>
                                </form>
                                <h4 id="txt_counter" ></h4>
                        </tr>
                    </table>
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
<script>
    function Calculate(Query) {
        if (Query.length == 0) {
            document.getElementById("txt_counter").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txt_counter").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "StatisticsHelper.php?q=" + Query + "_Title", true);
            xmlhttp.send();
        }
    }
</script>
<script>
    function SSP_DataTable(collection)
    {
        var table = $('#dtable').DataTable( {
            "processing": true,
            "serverSide": true,
            "lengthMenu": [20, 40 , 60, 80, 100],
            "bStateSave": false,
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
    //HMM
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
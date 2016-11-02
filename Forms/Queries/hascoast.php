<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
$array_collection = ["Green Maps","Blucher Maps"];
$array_db_name = ["greenmapsinventory","bluchermapsinventory"];
$array_table_name = ["greenmapsinventory.documentinformation","bluchermapsinventory.document"];
$array_dir = ["GreenCollection","MapsDB"];
if(isset($_GET['col'])) {
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);

    if($collection=="greenmaps"){
        $countcoll= $array_table_name[0];
    }
    if($collection=="bluchermaps"){
        $countcoll= $array_table_name[1];
    }
}

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
        <table id="thetable">
            <tr>
                <td class="menu_left" id="thetable_left">
                    <?php include '../../Master/header.php';
                    include '../../Master/sidemenu.php' ?>
                </td>
                <td class="container" id="thetable_right">
                    <h2 id="page_title">Maps With Coast</h2>
                    <table width="100%">
                        <tr>
                            <td style="margin-left: 45% ;font-size:13px" colspan="20%"
                            <td style="float:left;font-size:13px" colspan="20%">

                                <form id = "form1" name="form1" method="post">
                                    Select Collection:
                                    <select id="ddl_collection" name="ddl_collection">
                                        <?php
                                        for ($i = 0; $i < count($array_collection); $i++)
                                        {
                                            if(isset($_POST["ddl_collection"]) && ($_POST["ddl_collection"] == $array_table_name[$i]))
                                            {
                                                echo '<option value="' . $array_table_name[$i] . '" selected>' . $array_collection[$i] . '</option>';
                                            }
                                            else echo '<option value="' . $array_table_name[$i] . '">' . $array_collection[$i] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <input type="submit" name="btn_Submit" id="btn_Submit" value="Select" onclick="selectedValue()"/>
                                    <?php
                                    if(isset($_POST['btn_Submit']))
                                    {
//                                        mysql_connect("localhost","root","notroot");
//                                        mysql_select_db("maps") or die('Could not connect to the database');


                                        $jobfolder_flag = false;

                                        if ($_POST['ddl_collection'] == $array_table_name[0])
                                        {
                                            echo "<script type='text/javascript'>                                        
                                        var coltable;
                                        var newurl;
                                        var standingurl;

                                            //Check to see if a query exists in the URL
                                            standingurl = window.location.href;
                                            //If Query Exists, remove it before adding a new query
                                            if(standingurl.includes('?')){
                                                standingurl = standingurl.substring(0, standingurl.indexOf('?'));
                                            }
                                            //If no Query Exists, add the query
                                            else{
                                                standingurl = window.location.href
                                            }
                                                coltable=\"greenmaps\";

                                            //document.getElementById(\"demo\").innerHTML =
                                            newurl = standingurl + \"?col=\" + coltable;

                                            window.location.assign(newurl)
                                        
                                        </script>";
                                        }

                                        if ($_POST['ddl_collection'] == $array_table_name[1])
                                        {
                                            echo "<script type='text/javascript'>                                        
                                        var coltable;
                                        var newurl;
                                        var standingurl;

                                            //Check to see if a query exists in the URL
                                            standingurl = window.location.href;
                                            //If Query Exists, remove it before adding a new query
                                            if(standingurl.includes('?')){
                                                standingurl = standingurl.substring(0, standingurl.indexOf('?'));
                                            }
                                            //If no Query Exists, add the query
                                            else{
                                                standingurl = window.location.href
                                            }
                                                coltable=\"bluchermaps\";

                                            //document.getElementById(\"demo\").innerHTML =
                                            newurl = standingurl + \"?col=\" + coltable;

                                            window.location.assign(newurl)
                                        
                                        </script>";
                                        }
                                    }
                                    ?>
                                </form>

                                <h4 id="txt_counter" ></h4>
                                <?php

                               // $result = mysql_query("SELECT * FROM ".$countcoll, $link);

                                $filter ="hascoast = '1'";
                                //$query = mysql_query("SELECT * FROM ".$countcoll." WHERE ".$filter, $link);
                                //$hascoast_rows = mysql_num_rows($query);
                                //$totnum_rows = mysql_num_rows($result);

                                //  echo "<script type='text/javascript'>
                                // alert('A total of '  + $hascoast_rows + ' Have coasts out of ' + $totnum_rows);
                                // </script>";

                                ?>

                        </tr>
                    </table>
                    <script>
                        $(document).ready(function() {
                            var collection_config = <?php echo json_encode($config); ?>;
                            $('#page_title').text(collection_config.DisplayName);

                            var table = $('#dtable').dataTable( {
                                "processing": true,
                                "serverSide": true,
                                "lengthMenu": [20, 40 , 60, 80, 100],
                                "bStateSave": false,
                                "columnDefs": [
                                    //column Document Index: Replace with Hyperlink
                                    {
                                        "render": function ( data, type, row ) {
                                            return "<a href='editform.php?col=" + data + "'>Edit/View</a>" ;
                                        },
                                        "targets": 0
                                    },
                                    //column Title
                                    {
                                        "render": function ( data, type, row ) {
                                            return data;
                                        },
                                        "targets": 2
                                    },
                                    //column Subtitle
                                    {
                                        "render": function ( data, type, row ) {
                                            if(data.length > 38)
                                                return data.substr(0,38) + "...";
                                            return data;
                                        },
                                        "targets": 3
                                    },
                                    //column : Date
                                    {
                                        "render": function ( data, type, row ) {
                                            if(data == "00/00/0000")
                                                return "";
                                            return data;
                                        },
                                        "targets": 5,
                                        "visible": false
                                    },
                                    //column : HasCoast
                                    {
                                        "render": function ( data, type, row ) {
                                            if(data == 1)
                                                return "Yes";
                                            return "No";
                                        },
                                        "targets": 6

                                    },
                                    //column : NeedsReview
                                    {
                                        "render": function ( data, type, row ) {
                                            if(data == 1)
                                                return "Yes";
                                            return "No";
                                        },
                                        "targets": 7,
                                        "visible": false
                                    },

                                ],
                                "ajax": "hascoast_processing.php?col=" + collection_config.Name,
                                "initComplete": function(settings,json)
                                {
                                    var recordsTotal = $('#dtable').DataTable().page.info().recordsTotal;
                                    console.log(recordsTotal);

                                }

                            } );

                            table.fnFilter("1", 6);

                            //var rowCount = $('#dtable tr').length;

//                            document.getElementById("demo").innerHTML =
//                                "There are: " + rowCount + " Maps with Coasts, out of a total ";
                            //hide first column (DocID)
//                            table.column(0).visible(true);

                            // show or hide subtitle
                           // table.column(3).visible(false);
                            $('#checkbox_subtitle').change(function (e) {
                                e.preventDefault();
                                // Get the column API object
                                var column = table.column(3);
                                // Toggle the visibility
                                column.visible( ! column.visible() );
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

                            $('a.toggle-vis').on( 'click', function (e) {
                                e.preventDefault();

                                // Get the column API object
                                var column = table.column( $(this).attr('data-column') );

                                // Toggle the visibility
                                column.visible( ! column.visible() );
                            } );
                        });

                    </script>
                    <div id="DBTableDiv"  style="overflow-y: scroll;overflow-x:hidden;min-height:700px;max-height:800px;">
                        <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" width="100%" data-page-length='20'>
                            <thead>
                            <tr>
                                <th></th>
                                <th width="100px">Library Index</th>
                                <th>Document Title</th>
                                <th width="280px" >Document Subtitle</th>
                                <th width="200px">Customer</th>
                                <th width="70px">End Date</th>
                                <th width="40px">Has Coast</th>
                                <th width="30px">Needs Review</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
<?php include '../../Master/footer.php'; ?>
</body>
<script>
    //document.getElementById("txt_counter").innerHTML = "Result: " + counter + " documents out of " + total + " documents (" + percentage.toFixed(2) + "%)" + " Have Coasts";
</script>

</html>
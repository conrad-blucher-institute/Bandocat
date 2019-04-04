<?php
/**
 * Created by PhpStorm.
 * User: rortiz18
 * Date: 3/28/2019
 * Time: 3:24 PM
 */

include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin()) {
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
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


    <title>Blank Table Page</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">Database Manager</h1>
            <hr>


            <div class="form-group row">
                <!-- DDL for Database Selection -->
                <label class="col-sm-1 col-form-label" for="ddlDatabases">DataBase:</label>
                <div class="col-sm-4">
                    <div class="d-flex">
                        <select class="form-control" name="ddlDatabases" id="ddlDatabases">
                            <!--<option value=""></option>-->
                            <!-- POPULATES THE DDL WITH BANDOCAT DATABASES -->
                            <?php $DB->SHOW_DATABASES(); ?>
                        </select>
                    </div>
                </div>

                <!-- DDL for Table Selection -->
                <label class="col-sm-1 col-form-label" for="ddlTables">Table:</label>
                <div class="col-sm-4">
                    <div class="d-flex">
                        <select class="form-control" name="ddlTables" id="ddlTables">
                            <!-- TABLES APPENDED HERE -->
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Data-Table -->
            <table id="dtable" class="table table-striped table-bordered" width="100%" cellspacing="0" data-page-length='20'>
                <thead>
                <tr id="tableHead">

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

<script>

    //var table;

    $(document).ready(function() {

        /*var dbname = $('#ddlDatabases').val();
        var tblname = $('#ddlTables').val();

        //console.log(dbname, tblname);
        $.ajax({
            url: "./table_processing.php",
            method: "POST",
            data: {dbname: dbname, tblname: tblname},
            success:function(response)
            {
                //response = JSON.parse(response);
                //var columns = response["columns"];
                //console.log(response);

                // Display datatable
                //showTable(response);
            }
        });
        */
        getTableList();
        //getUserInput();
    });

    $('#ddlDatabases').change(function() {
        getTableList();
    });

    $('#ddlTables').change(function() {
        $('#dtable').empty();
        getUserInput();
    });

    /***************************************************************
     Function: getTableList
     Description: Appends html of selected data-table to DDL
     ***************************************************************/
    function getTableList()
    {
        // Get selected value
        var dbname = $('#ddlDatabases').val();

        // Check if it exists first

        $.ajax({
            url: "./show_tables.php",
            method: "POST",
            data: {dbname: dbname},
            success:function(response)
            {
                //console.log(response);
                $('#ddlTables').empty();
                $('#ddlTables').append(response);
                getUserInput();
            }
        });
    }

    /***************************************************************
     Function: getUserInput
     Description: Passes User Input to change datatable when new
                  fields are selected from the DDLs
     ***************************************************************/
    function getUserInput()
    {
        // Get selected value
        var dbname = $('#ddlDatabases').val();
        var tblname = $('#ddlTables').val();

        // Check if it exists first

        $.ajax({
            url: "./table_processing.php",
            method: "POST",
            data: {dbname: dbname, tblname: tblname},
            success:function(response)
            {
                //console.log(response);
                showTable(JSON.parse(response));
            }
        });
    }

    var table
    /*******************************************************************
     Function: showTable
     Description: Function displays a dynamic data-table of the chosen
                  database and table.
     *******************************************************************/
    function showTable(response)
    {
        var data = response["data"];
        var columns = response["columns"];
        var lastColumn = columns.length - 1;

        console.log(columns, lastColumn);

        $('#tableHead').empty();

       for(var i = 0; i < columns.length; i++)
        {
            // Append to header
            $('#tableHead').append("<th>" + columns[i]["data"] + "</th>")
            console.log(i ,"\n");
        }

        // Example dtable using this method: https://datatables.net/examples/ajax/objects.html
        table = $('#dtable').DataTable({
            "processing": true,
            "serverside": true,
            "lengthMenu": [20, 40, 60, 80, 100],
            "destroy": true,
            "order": [],

            // Dynamically making table
            "data":data,
            "columns": columns,
            "columnDefs":
                [
                    {
                        "render": function ()
                        {
                            return "<a href='#'>Delete</a>";
                        },
                        "targets": lastColumn,
                    }
                ],
        });

    }
</script>

</body>
</html>
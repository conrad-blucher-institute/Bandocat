<?php
//scripts map
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
    <script>
        $(document).ready(function() {
            var table = $('#dtable').DataTable( {
                "processing": true,
                "serverSide": true,
                "lengthMenu": [20, 40 , 60, 80, 100],
                "bStateSave": true,
                "columnDefs": [
                    //column Title
                    {
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 1
                    },
                    //column Subtitle
                    {
                        "render": function ( data, type, row ) {
                            if(data.length > 38)
                                return data.substr(0,38) + "...";
                            return data;
                        },
                        "targets": 2
                    },
                    //column : Date
                    {
                        "render": function ( data, type, row ) {
                            if(data == "00/00/0000")
                                return "";
                            return data;
                        },
                        "targets": 3
                    },
                ],
                "ajax": "scripts/list_processing.php"
            } );

            table.column(2).visible(false);
            $('#checkbox_subtitle').change(function (e) {
                e.preventDefault();
                // Get the column API object
                var column = table.column(2);
                // Toggle the visibility
                column.visible( ! column.visible() );
            } );
        });
    </script>

</head>
<body>
<table id="thetable">
<div >
    <tr >
        <td class="menu_left" id="thetable_left">
        <?php include '../../Master/header.php'; ?>
        <ul>
            <li><a href="">Main Menu</a></li>
            <li><a href="">View Map</a></li>
            <li class="submenu_left"><a href="">Collections</a>
                <ul>
                    <li><a href="">Blucher Maps</a></li>
                    <li><a href="">Green Maps</a></li>
                    <li><a href="">Job Folder</a></li>
                </ul>
            </li>
            <li><a href="">Logout</a></li>
        </ul>
    </div>
    </td>
    <td class="container" id="thetable_right">
    <div>
        <h2>Maps Template - Listing</h2>
        <table>
            <tr>
                <td><input name="checkbox_subtitle" type="checkbox" id="checkbox_subtitle" />Show/Hide Subtitle</td>
            </tr>
        </table>
        <div>
        <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" width="100%" data-page-length='20'>
            <thead>
                <tr>
                    <th width="150px">Library Index</th>
                    <th>Document Title</th>
                    <th width="280px">Document Subtitle</th>
                    <th width="80px">End Date</th>
                </tr>
            </thead>
        </table>
        </div>
    </div>
    </td>
    </tr>
</table>
</body>
</html>

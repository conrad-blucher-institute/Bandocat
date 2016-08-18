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
            $('#dtable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "scripts/list_processing.php"
            } );
        } );
    </script>

</head>
<body>
<div class="menu_left">
    <?php include '../../Master/header.php'; ?>
    <ul>
        <li><a href="">Home</a></li>
        <li><a href="">View Map</a></li>
        <li class="submenu_left"><a href="">Collections</a>
            <ul>
                <li><a href="">Blucher Maps</a></li>
                <li><a href="">Green Maps</a></li>
                <li><a href="">Job Folder</a></li>
            </ul>
        </li>
        <li><a href="">Main Menu</a></li>
        <li><a href="">Logout</a></li>
    </ul>
</div>
<div class="container">
    <h2>Maps Template - Listing</h2>
    <div>
    <table id="dtable" class="display compact cell-border" cellspacing="0" width="100%" data-page-length='20'>
        <thead>
            <tr>
                <th>Library Index</th>
                <th>Document Title</th>
            </tr>
        </thead>
    </table>
    </div>
</div>

</body>
</html>

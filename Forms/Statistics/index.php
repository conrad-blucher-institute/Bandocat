<?php
//for admin use only
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

    <title>Statistics</title>
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/DataTables-1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/Chart.min.js"></script>

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
                    <h2 id="page_title">Statistics</h2>
                    <div style="overflow-y: scroll;overflow-x:hidden;min-height:500px;max-height:665px">
                        <button id="btnCollectionCount" onclick="getCollectionCount()" class="bluebtn">
                            Collection Count
                        </button>
                        <div id="divDocumentCount" style="width:450px;height:500px;display: none"; >
                            <h3 id="titleDocumentCount">Total Maps/Documents per Collection </h3>
                                <canvas id="chartDocumentCount" height="430" width="400"></canvas>
                            <label>Total: <b><span id="txtDocumentCount"></span></b></label>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>
</body>
<script>
    function getCollectionCount()
    {
        $('#divDocumentCount').show();
        $.ajax({
           type: "POST",
            url: "./collectioncount_processing.php",
            success: function(data){

            },
        });
    }
</script>
</html>

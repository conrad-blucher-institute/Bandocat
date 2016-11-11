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
    <script type="text/javascript" src="../../ExtLibrary/Chart.js"></script>
    <style>
        .graph-legend li span{
            display: inline-block;
            width: 1em;
            height: 1.2em;
            font-size: 1.2em;
            margin-right: 5px;
        }

        .graph-legend li{list-style-type: none;}

    </style>
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
                    <div style="overflow-y: scroll;overflow-x:hidden;min-height:1000px">
                        <button id="btnCollectionCount" onclick="getCollectionCount()" class="bluebtn">
                            Collection Count
                        </button>
                        <div id="divDocumentCount" style="width:450px;height:500px;display: none"; >
                            <h3 id="titleDocumentCount">Total Maps/Documents per Collection </h3>
                                <canvas id="chartDocumentCount" height="350" width="300"></canvas>
                                <div class="graph-legend" id="legendDocumentCount"></div>
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

    var collection_color = ['blue','green','orange','yellow','grey','black'];
    var collection_highlight = ['#FF5A5E','#7FFF55','#007FFF','#244400','#ffffff'];

    function getCollectionCount()
    {
        $('#divDocumentCount').show();
        $.ajax({
           type: "POST",
            url: "./collectioncount_processing.php",
            success: function(data){
                var JSONdata = JSON.parse(data);
                //generate total chart
                var pieData = [];
                for(var i = 0; i < JSONdata.length;i++)
                {
                    var array = {value: JSONdata[i].count,
                                 color: collection_color[i],
                                 highlight: collection_highlight[i],
                                 label: JSONdata[i].collection
                    };

                    pieData.push(array);
                }
                var ctx1 = document.getElementById("chartDocumentCount").getContext("2d");
                window.MyPieChart= new Chart(ctx1).Pie(pieData);
                document.getElementById("legendDocumentCount").innerHTML = MyPieChart.generateLegend();
            }
        });
    }
</script>
</html>

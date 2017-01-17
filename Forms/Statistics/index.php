<?php
//for admin use only
include '../../Library/SessionManager.php';
$session = new SessionManager();
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
require('../../Library/ControlsRender.php');
$Render = new ControlsRender();

$collections = $DB->GET_COLLECTION_TABLE();

//Disk space management
function foldersize($path) {
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }
    }

    return $total_size;
}

$units = explode(' ', 'B KB MB GB TB PB');
function format_size($size) {
    global $units;

    $mod = 1024;

    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    $endIndex = strpos($size, ".")+3;

    return substr( $size, 0, $endIndex).' '.$units[$i];
}



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
</head>
<body>
<div id="wrap">
    <div id="main">
        <div id="divleft">
                    <?php include '../../Master/header.php';
                    include '../../Master/sidemenu.php' ?>
            </div>
        <div id="divright">
                    <h2 id="page_title">Statistics</h2>
                        <div id="table-header_right">Select Year: <select id="ddlYear" name="ddlYear"">
                            <?php
                            echo "<option value=''>Select</option>";
                            for($i = date("Y");$i >= 2015;$i--) {
                                if($i == date("Y")) echo "<option selected value='$i'>$i</option>";
                                else
                                    echo "<option value='$i'>$i</option>";
                            }
                            ?>
                            </select>
                        </div>
                    <div id="divscroller">
                        <table><tr>
                                <td style="vertical-align: top">
                                    <h3>Monthly Performance <span class="spanYear"></span></h3>
                                    <canvas id="chartMonthlyReport" height="470" width="600"/>
                                </td>
                                <td style="padding-left:60px;vertical-align: top">
                                        <h3 id="titleDocumentCount">Total Maps/Documents per Collection </h3>
                                            <canvas id="chartDocumentCount" height="350" width="350"></canvas>
                                        <div style="text-align: center;font-weight: bold;margin-top:15px">Total: <span id="spanTotalCount"></span> documents  </div>
                                    <div id="storage_capacity">
                                        <h3>Storage Capacity</h3>
                                        <?php
                                        $total_storage = 0;
                                        foreach($collections as $col)
                                        {
                                            $temp = foldersize($col['storagedir']);
                                            $total_storage += $temp;
                                            echo "<div class='storagecap'>$col[displayname]: " . format_size($temp) . "</div>";
                                        }
                                        echo "<div class='storagecap'><b>All Collections:" . format_size($total_storage) . "</b></div>";
                                        echo "<div class='storagecap'>Disk Free Space: " . format_size(disk_free_space($collections[0]['storagedir'])) . "</div>";
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3>Weekly Performance <span class="spanYear"></span></h3>
                                    <canvas id="chartWeeklyReport" height="500" width="1000"></canvas>
                                </td>
                            </tr>
                        </table>
                    </div>
            </div>


    </div>
</div>
<?php include '../../Master/footer.php'; ?>
</body>
<script>
    var collections = <?php echo json_encode($collections); ?>;
    var collection_color = ['#0067C5','#00BC65','#FFAA2A','#26A6D0','#787878','#00000'];
    var collection_highlight = ['#FF5A5E','#7FFF55','#3e3e3e','#26A6D0','#244400','#D4D4FF'];
    function getCollectionCount() {
        $('#divDocumentCount').show();
        $.ajax({
            type: "POST",
            url: "./collectioncount_processing.php",
            success: function (data) {
                var JSONdata = JSON.parse(data);
                //generate total chart
                var pieData = [];
                var pieLabels = [];
                for (var i = 0; i < JSONdata.length; i++) {
                    pieData.push(JSONdata[i].count);
                    pieLabels.push(JSONdata[i].collection);
                }
                var canvasPie = document.getElementById("chartDocumentCount");
                var ctx1 = canvasPie.getContext("2d");
                var PieChart = new Chart(ctx1,{ type: 'pie',
                    data: {
                        datasets: [{
                            data: pieData,
                            backgroundColor: collection_color,
                            hoverBackgroundColor: collection_highlight,
                            label: "Total Maps/Documents per Collection"
                        }],
                        labels: pieLabels
                    },
                    options: { responsive:true}
                });
                //calculate total counts of all collections = SUM of pieData array
                $("#spanTotalCount").html(pieData.reduce(function(prev,curr){return parseInt(prev)+ parseInt(curr);}));
            }
        });
    }
    //weekly
        $("#ddlYear").change(function()
            {
                var year = $("#ddlYear").val();
                if(year != "") {
                    $(".spanYear").text(year);
                    $.ajax({
                        type: "POST",
                        url: "./weeklyreports_processing.php?year=" + year,
                        success: function (data) {
                            var JSONdata = JSON.parse(data);
                            //generate total chart
                            var weeklyData = [];
                            for (var i = 0; i < collections.length; i++) {
                                var array = {
                                    label: collections[i].displayname,
                                    backgroundColor: "transparent",
                                    borderColor: collection_color[i],
                                    pointColor: collection_color[i],
                                    strokeColor: collection_color[i],
                                    borderWidth: 2,
                                    hoverBackgroundColor : collection_highlight[i],
                                    hoverBorderColor: "rgba(151,187,205,1)",
                                    data: JSONdata.datasets[i]
                                };
                                weeklyData.push(array);
                                array = null;
                            }
                            //generate weekly input chart
                            var dat = {
                                labels: JSONdata.labels,
                                datasets: weeklyData,
                            };
                            //var ctx = new Chart(document.getElementById("chartWeeklyReport").getContext("2d")).Line(dat);
                            var canvas = document.getElementById("chartWeeklyReport");
                            var ctx = canvas.getContext("2d");
                            var LineChart = new Chart(ctx,{ type: "line",
                                                            data: dat,
                                                            options: {scales: {
                                                                yAxes: [{
                                                                    stacked: true
                                                                }]
                                                            },responsive:true}
                            });
                        }
                    });
                }

            });
        //monthly
            $("#ddlYear").change(function()
            {
                var year = $("#ddlYear").val();
                if(year != "") {
                    $.ajax({
                        type: "POST",
                        url: "./monthlyreports_processing.php?year=" + year,
                        success: function (data) {
                            var JSONdata = JSON.parse(data);
                            //generate total chart
                            var monthlyData = [];
                            for (var i = 0; i < collections.length; i++) {
                                var array = {
                                    label: collections[i].displayname,
                                    backgroundColor: "transparent",
                                    borderColor: collection_color[i],
                                    pointColor: collection_color[i],
                                    strokeColor: collection_color[i],
                                    borderWidth: 2,
                                    hoverBackgroundColor : collection_highlight[i],
                                    hoverBorderColor: "rgba(151,187,205,1)",
                                    data: JSONdata[i][0]
                                };
                                monthlyData.push(array);
                                array = null;
                            }
                            //generate monthly input chart
                            var dat2 = {
                                labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                                datasets: monthlyData,
                            };

                            var canvas2 = document.getElementById("chartMonthlyReport");
                            var ctx2 = canvas2.getContext("2d");
                            var LineChart2 = new Chart(ctx2,{ type: "line",
                                data: dat2,
                                options: {scales: {
                                    yAxes: [{
                                        stacked: true,
                                    }]
                                },responsive:true}
                            });
                        }
                    });
                }
                //resize height of the scroller
                $("#divscroller").height($(window).outerHeight() - $(footer).outerHeight() - $("#table-header_right").outerHeight() - $("#page_title").outerHeight() - 55);
            });

    $(document).ready(function() {
        //$("#ddlYear").change();
        getCollectionCount();
        $("#ddlYear").change();

    });



</script>
</html>

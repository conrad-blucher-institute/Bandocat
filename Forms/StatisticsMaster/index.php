<?php
include '../../Library/SessionManager.php';
require '../../Library/DBHelper.php';
require '../../Library/AnnouncementDBHelper.php';
$session = new SessionManager();
$announcement = new AnnouncementDBHelper();
$announcementData = $announcement->GET_ANNOUNCEMENT_DATA();
$announcementLenght = count($announcementData);
$announcementJSON = json_encode($announcementData);
$userID = $session->getUserID();
$admin = $session->isAdmin();
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
    <link rel="stylesheet" type="text/css" href="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.css">

    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    <style>
        h1
        {
            text-align: center;
        }

        .flex-container {
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }

        .flex-container > .flex-item {
            min-height: 400px;
            min-width: 400px;
            margin: auto;
        }

        .card
        {
            width: 100%;
            height: 100%;

            border: 5px double silver;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        .card-body {
            padding: 10px;
            text-align: center;
        }

        .card-header
        {
            padding-left: 5px;
            padding-right: 5px;
            text-align: center;
            background-color: silver;
            color: white;
        }

        a:link
        {
            text-decoration: none;
        }

        h3
        {
            padding: 0px;
            margin: 0px;
            font-size: 30px;
        }
    </style>
</head>
<!-- HTML BODY -->
<body>
<h2>Landing</h2>

<!-- BANDOCAT CARD -->
<div id="main" class="flex-container">
    <div class="flex-item">
        <a href="../Main/..">
            <div class="card">
                <div class="card-header">
                    <h3>Bandocat</h3>
                </div>
                <div class="card-body">
                    <div class="canvas-container">
                        <canvas id="myPieChart"></canvas>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- TONY AMOS CARD -->
    <div class="flex-item">
        <a href="../../../TonyAmos/Forms/TonyAmosCollection.php">
            <div class="card">
                <div class="card-header">
                    <h3>Tony Amos</h3>
                </div>
                <div class="card-body">
                    <div class="canvas-container">
                        <canvas id="myBarChart" style="height: 300px; width: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


    <?php include '../../Master/footer.php'; ?>
</body>
<script>
    $(document).ready(function(){
        $.ajax({
            type: "POST",
            url: "load_tables.php",
            data: {bandocat: true},
            success: function(data)
            {
                var objArray = JSON.parse(data);
                console.log(objArray);

                // Pie Chart for storage statistics
                var ctx = document.getElementById("myPieChart");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    // Getting select statement
                    data: {
                        labels: [objArray[0].collection, objArray[1].collection, objArray[2].collection, objArray[3].collection
                        , objArray[4].collection, objArray[5].collection, objArray[6].collection],
                        datasets: [{
                            data: [parseFloat(objArray[0].size), parseFloat(objArray[1].size), parseFloat(objArray[2].size), parseFloat(objArray[3].size)
                                , parseFloat(objArray[4].size), parseFloat(objArray[5].size), parseFloat(objArray[6].size)],
                            // #8DC641 #21357E
                            backgroundColor: ['#03A1DA', '#86BF28', '#CEDA07', '#F3B129', '#F12522', '#a2a9b5', '#02eff7']
                        }]
                    },

                    options: {
                        title: {
                            display: true,
                            text: "Bandocat Storage Statistics"
                        }
                    }
                });
            }
        });

        $.ajax({
            type: "POST",
            url: "load_tables.php",
            data: {tonyamos: true},
            success: function(data)
            {
                console.log(JSON.parse(data));
                data = JSON.parse(data);

                // Pie Chart Example
                var ctx = document.getElementById("myBarChart");
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    // Getting select statement
                    data: {
                        labels: ["SAND", "RBG", "LGUL", "HGUL", "Total Birds"],
                        datasets: [{
                            data: [parseInt(data[0]), parseInt(data[1]), parseInt(data[2]), parseInt(data[3]), parseInt(data[4])],
                            // #8DC641 #21357E
                            backgroundColor: ['#03A1DA', '#86BF28', '#CEDA07', '#F3B129', '#F12522']
                        }]
                    },

                    options: {
                        title: {
                            display: true,
                            text: "Tony Amos Example Statistics Birds Observed"
                        }
                    }
                });
            }
        });
    });
</script>
</html>






<?php
/**
 * Created by PhpStorm.
 * User: hreeves
 * Date: 10/19/2018
 * Time: 9:14 AM
 */
require '../../Library/DBHelper.php';
require '../../Library/MapDBHelper.php';
require '../../Library/DateHelper.php';
require '../../Library/ControlsRender.php';
include '../../Library/SessionManager.php';

//get collection name from passed variable col
if(isset($_GET['col']))
{
    $collection = $_GET['col'];
}

$session = new SessionManager();
$Render = new ControlsRender();
$DB = new MapDBHelper();
$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
?>
<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Search Form</title>
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <!--<link rel="stylesheet" type="text/css" href="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.css">
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.js"></script>
    <script type="text/javascript" src="../../Master/master.js"></script>-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <style>
        .flex-container
        {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .flex-container > .item
        {
            height: 125px;
            width: 275px;
            text-align: center;
        }

        label
        {
            font-size: 18px;
        }

        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        $(document).ready(function () {

        });

        function createTable(data)
        {
            console.log(data);
            var table = $('#dtable').DataTable
            ({
                "processing": true,
                "lengthMenu": [20, 40, 60, 80, 100],
                "destroy": true,

                // Displaying loading gif
                "language": {

                },

                "initComplete": function()
                {

                },

                data:data.data,

                columns: [
                    {data: 'documentID'},
                    {data: 'libraryindex'},
                    {data: 'title'},
                    {data: 'companyname'},
                    {data: 'customername'},
                    {data: 'authorname'}
                    /*{"data": "id"},
                    {"data": "Library Index"},
                    {"data": "Title"},
                    {"data": "Customer"},
                    {"data": "Author"}*/
                ]
            });
        }

        function isEmpty(myObject)
        {
            for(var key in myObject)
            {
                if (myObject.hasOwnProperty(key))
                {
                    return false;
                }
            }

            return true;
        }

        function getSearchResults()
        {
            // Preventing the page from resubmitting
            event.preventDefault();

            // Getting all search parameters
            var docid = $('#docid').val();
            var libindex = $('#libindex').val();
            var title = $('#title').val();
            var subtitle = $('#subtitle').val();
            var mapscale = $('#mapscale').val();
            var comments = $('#comments').val();
            var customer = $('#customer').val();
            var author = $('#author').val();
            var hasnortharrow = $("#hasnortharrow").val();
            var has_streets = $("#has_streets").val();
            var poi = $("#poi").val();
            var coordinates = $("#coordinates").val();
            var coast = $("#coast").val();
            var review = $("#review").val();
            var medium = $("#medium").val();
            var geoFront = $("#geoFront").val();
            var geoBack = $("#geoBack").val();
            var read = $("#read").val();
            var rect = $("#rect").val();
            var company = $("#company").val();
            var ismap = $("#isMap").val();

            // Calling search_process.php
            $.ajax({
                url:"../../Forms/Queries/search_processing.php?col=<?php echo $collection; ?>",
                method:"POST",
                data:{docid:docid, libindex:libindex, title:title, subtitle:subtitle, mapscale:mapscale, comments:comments, customer:customer, author:author,
                hasnortharrow:hasnortharrow, has_streets:has_streets, poi:poi, coordinates:coordinates, coast:coast, review:review, medium:medium,
                geoFront:geoFront, geoBack:geoBack, read:read, rect:rect, company:company, ismap:ismap},
                success:function(data)
                {
                    data = JSON.parse(data);
                    console.log(data.data);
                    createTable(data);
                }
            });

        }
    </script>
</head>
<!-- END HTML HEADER -->
<!--  HTML BODY -->
<body>
<div id="wrap">
    <div id="main">
        <div id="divleft">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </div>
        <div id="divright">
            <!-- Page Contents Here -->
            <h2>Search <?php echo $config["DisplayName"];?></h2>
            <!-- Ordering the flow of the page -->
            <div id="divscroller" style="height:90vh;">
                <!-- ADVANCED SEARCH FORM -->
                <div id="divForm">
                    <h3 style="text-align: center;">Advanced Search</h3>
                    <form class="flex-container">
                        <div class="item">
                            <label for="docid">Document ID</label>
                            <input type="text" id="docid" name="docid" placeholder="Document ID">
                        </div>
                        <div class="item">
                            <label for="libindex">Library Index</label>
                            <input type="text" id="libindex" name="libindex" placeholder="Library Index">
                        </div>
                        <div class="item">
                            <label for="title">Document Title</label>
                            <input type="text" id="title" name="title" placeholder="Library Index">
                        </div>
                        <div class="item">
                            <label for="subtitle">Subtitle</label>
                            <input type="text" id="subtitle" name="subtitle" placeholder="Subtitle">
                        </div>
                        <div class="item">
                            <label for="mapscale">Map Scale</label>
                            <input type="text" name="mapscale" id="mapscale" placeholder="Map Scale">
                        </div>
                        <div class="item">
                            <label for="comments">Comments</label>
                            <input type="text" name="comments" id="comments" placeholder="Comments">
                        </div>
                        <div class="item">
                            <label for="customer">Customer</label>
                            <?php $Render->GET_DDL_CUSTOMER_NAME($DB->GET_DDL_CUSTOMERS($collection), true); ?>
                        </div>
                        <div class="item">
                            <label for="author">Author of Document</label>
                            <?php $Render->GET_DDL_AUTHOR_NAME($DB->GET_DDL_AUTHOR_NAME($collection), true); ?>
                        </div>
                        <div class="item">
                            <label for="medium">Medium</label>
                            <?php $Render->GET_DDL_MEDIUM_ID($DB->GET_DDL_MEDIUM($collection), true); ?>
                        </div>
                        <div class="item">
                            <label for="company">Company</label>
                            <?php $Render->GET_DDL_COMPANY_ID($DB->GET_DDL_COMPANIES($collection), true); ?>
                        </div>
                        <div class="item">
                            <label for="isMap">isMap</label>
                            <select name="isMap" id="isMap">
                                <option value="">Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="hasnortharrow">North Arrow</label>
                            <select name="hasnortharrow" id="hasnortharrow">
                                <option value="">Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="has_streets">Streets</label>
                            <select name="has_streets" id="has_streets">
                                <option value="">Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="poi">POI</label>
                            <select name="poi" id="poi">
                                <option value="">Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="coordinates">Coordinates</label>
                            <select name="coordinates" id="coordinates">
                                <option value="">Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="coast">Coast</label>
                            <select name="coast" id="coast">
                                <option value="">Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="review">Needs Review</label>
                            <select name="review" id="review">
                                <option value="">Select</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="geoFront">Geo Rectifiable Front Status</label>
                            <select name="geoFront" id="geoFront">
                                <option value="">Select</option>
                                <option value="0">Not Rectified</option>
                                <option value="1">Rectified</option>
                                <option value="2">Not Rectifiable</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="geoBack">Geo Rectifiable Back Status</label>
                            <select name="geoBack" id="geoBack">
                                <option value="">Select</option>
                                <option value="0">Not Rectified</option>
                                <option value="1">Rectified</option>
                                <option value="2">Not Rectifiable</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="read">Readability</label>
                            <select name="read" id="read">
                                <option value="">Select</option>
                                <option value="POOR">POOR</option>
                                <option value="GOOD">GOOD</option>
                                <option value="EXCELLENT">EXCELLENT</option>
                            </select>
                        </div>
                        <div class="item">
                            <label for="rect">Rectifiability</label>
                            <select name="rect" id="rect">
                                <option value="">Select</option>
                                <option value="POOR">POOR</option>
                                <option value="GOOD">GOOD</option>
                                <option value="EXCELLENT">EXCELLENT</option>
                            </select>
                        </div>
                        <div class="item">

                        </div>
                        <div class="item">
                            <button type="submit" onclick="getSearchResults()">Search</button>
                        </div>
                    </form>
                </div>
                <br>
                <hr>
                <br>
                <!-- DATA TABLE -->
                <div id="divTable">
                    <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" data-page-length='20' style="width: 100%;">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Library Index</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Customer</th>
                            <th>Author</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>id</th>
                            <th>Library Index</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Customer</th>
                            <th>Author</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>

</body>
</html>



<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col'])) {
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
}
else header('Location: ../../');

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

            var collection_config = <?php echo json_encode($config); ?>;
            $('#page_title').text(collection_config.DisplayName + " Transcription");


            var table = $('#dtable').DataTable( {
                "processing": true,
                "serverSide": true,
                "lengthMenu": [20, 40 , 60, 80, 100],
                "bStateSave": false,
                "order": [[ 3, "asc" ],[0, "asc"]],
                "columnDefs": [
                    //column ID
                    {
                        "className": "dt-center",
                        "render": function ( data, type, row ) {
                            return '<a href="../../Templates/Indices/review.php?col=' + collection_config.Name + '&doc=' + data + '">Edit/Review</a>';
                        },
                        "targets": 0
                    },
                    //column Page Number
                    {
                        "className": "dt-right",
                        "render": function ( data, type, row ) {
                            if(data == '0' || data == 0)
                                return "";
                            return data;
                        },
                        "targets": 4
                    },
                    //column needs review
                    {
                        "className": "dt-center",
                        "render": function ( data, type, row ) {
                            if (data == '1')
                                return "Yes";
                            return "No";
                        },
                        "targets": 5
                    },
                    //column Completed?
                    {
                        "className": "dt-center",
                        "render": function ( data, type, row ) {
                            if(data == "1")
                                return "Yes";
                            return "No";
                        },
                        "targets": 6
                    },
                    //column Transcription hyperlink
                    {
                        "className": "dt-center",
                        "render": function ( data, type, row ) {
                            var col = collection_config.Name;
                            return "<a href='' id='aTranscribe' " +  'onclick = "Jpg_Conversion(' + row[0] + ',\'' + data + '\',\'' + col + '\');event.preventDefault();" >Transcribe</a>';
                        },
                        "targets": 7
                    },
                ],
                "ajax": "list_processing.php?col=" + collection_config.Name,
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



        });
    </script>
</head>
<body>
<div id="wrap">
    <div id="main">
        <div id="divleft">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </div>
        <div id="divright">
            <h2 id="page_title">Title</h2>
            <div id="divscroller">
                <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" width="100%" data-page-length='20'>
                <thead>
                <tr>
                    <th width="80px"></th>
                    <th width="110px">Page Type</th>
                    <th width="200px">Library Index</th>
                    <th width="100px">Book Title</th>
                    <th width="30px">Page #</th>
                    <th width="50px">Needs Review</th>
                    <th width="50px">Completed?</th>
                    <th width="40px"></th>
                </tr>
                </thead>
            </table>
            </div>
        </div>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>
</body>
</body>
<script>
    var url = "php/IndexTiff2Jpg.php";

    function Jpg_Conversion(docId,fileName,collection)
    {
        //openModal(3);
        $.ajax({
            type: "POST",
            url: url,
            //dataType: 'json',
            data: {"docID": docId, "fileName": fileName, "collection": collection},
            success:function(data) {
                window.localStorage.setItem("fileName",data);
                window.localStorage.setItem("docID",docId);
                window.open("Transcription.php?col=" + collection );
            },
            error:function(requestObject, error, errorThrown) {
                alert(error);
                alert(errorThrown);
            }
        });
    }
</script>
</html>

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
            $('#page_title').text(collection_config.DisplayName);


            var table = $('#dtable').DataTable( {
                "processing": true,
                "serverSide": true,
                "lengthMenu": [20, 40 , 60, 80, 100],
                "bStateSave": false,
                "columnDefs": [
                    //column Document Index: Replace with Hyperlink
                    {
                        "render": function ( data, type, row ) {
                            return "<a href='review.php?doc=" + data + "&col=" + collection_config['Name'] + "'>Edit/View</a>" ;
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
                        "targets": 5
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
                        "targets": 7
                    },

                ],
                "ajax": "list_processing.php?col=" + collection_config.Name
            } );

            //hide first column (DocID)
            table.column(0).visible(true);

            // show or hide subtitle
            table.column(3).visible(false);
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


        });
    </script>

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
        <h2 id="page_title">Title</h2>
        <table width="100%">
            <tr>
                <td style="float:right;font-size:13px" colspan="100%"><input name="checkbox_subtitle" type="checkbox" id="checkbox_subtitle" />Show/Hide Subtitle</td>
            </tr>
        </table>
        <div style="overflow-y: scroll;overflow-x:hidden;min-height:500px;max-height:674px">
        <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" width="100%" data-page-length='20'>
            <thead>
                <tr>
                    <th></th>
                    <th width="100px">Library Index</th>
                    <th>Document Title</th>
                    <th width="280px">Document Subtitle</th>
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
</html>

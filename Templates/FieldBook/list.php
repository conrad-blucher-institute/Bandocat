<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
//Get collection name and action
if(isset($_GET['col']) && isset($_GET['action']))
{
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    //get appropriate db
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
    $action = $_GET['action'];
    if($action == "catalog")
        $title = "Catalog";
    else $title = "Review";
}
else header('Location: ../../');


?>
<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo $title;?> List</title>

    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <link rel = "stylesheet" type="text/css" href="../../ExtLibrary/DataTables-1.10.12/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/DataTables-1.10.12/js/jquery.dataTables.min.js"></script>
    <script>
        /**********************************************
         * Function:  DeleteDocument
         * Description: deletes the document from the database
         * Parameter(s):
         * col (in string) - name of the collection
         * id (in Int) - document id
         * Return value(s):
         * $result true if success, false if failed
         ***********************************************/
        function DeleteDocument(col,id)
        {
            $response = confirm('Are you sure you want to delete this document?');
            if($response)
            {
                //send to form_processing the information in the data: folder
                $.ajax({
                    type: 'post',
                    url: 'form_processing.php',
                    data: {"txtAction": "delete", "txtCollection": col, "txtDocID": id},
                    success:function(data){
                        var json = JSON.parse(data);
                        var msg = "";
                        for(var i = 0; i < json.length; i++)
                            msg += json[i] + "\n";
                        alert(msg);
                        $('#dtable').DataTable().ajax.reload();
                    }
                });
            }
        }
        //When the document is ready, begin the rendering of the datatable
        $(document).ready(function()
        {
            var collection_config = <?php echo json_encode($config); ?>;
            var action = '<?php echo $action; ?>';
            $('#page_title').text(collection_config.DisplayName);

            var table = $('#dtable').DataTable(
                {
                "processing": true,
                "serverSide": true,
                "lengthMenu": [20, 40 , 60, 80, 100],
                "bStateSave": false,
                "order": [[ 0, "desc" ]],
                "columnDefs":
                    [
                    //column Document Index: Replace with Hyperlink
                    {
                        //If the action is catalog or if the action is review
                        "render": function ( data, type, row )
                        {
                            if(action == "catalog")
                                var ret = "<a target='_blank' href='catalog.php?doc=" + data + "&col=" + collection_config['Name'] + "'>Catalog</a>" ;
                            else var ret = "<a target='_blank' href='review.php?doc=" + data + "&col=" + collection_config['Name'] + "'>Edit/View</a>" ;
                            return ret;
                        },
                        "targets": 0
                    },
                    { "searchable": false, "targets": 0 },

                    //column : NeedsInput
                    {
                        "render": function ( data, type, row ) {
                            if(data == 1)
                                return "Yes";
                            return "No";
                        },
                        "targets": 4
                    },
                    //column : NeedsReview
                    {
                        "render": function ( data, type, row ) {
                            if(data == 1)
                                return "Yes";
                            return "No";
                        },
                        "targets": 5
                    },
                    {
                        "render": function ( data, type, row )
                        {
                            return "<a href='#' onclick='DeleteDocument(" + JSON.stringify(collection_config.Name) + "," + row[0] + ")'>Delete</a>";
                        },
                        "targets": 6
                    },

                ],
                "ajax": "list_processing.php?col=" + collection_config.Name + "&action=" + '<?php echo $action; ?>',
                "initComplete": function()
                {
                        this.api().columns().every( function () {
                            var column = this;
                            switch(column[0][0]) //column number
                            {
                                //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
                                case 5: //column needsreview
                                    var select = $('<select style="width:100%"><option value="">Filter...</option><option value="1">Yes</option><option value="0">No</option></select>')
                                        .appendTo( $(column.footer()).empty() )
                                        .on( 'change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );

                                            column
                                                .search(val)
                                                .draw();
                                        } );
                                    break;
                                //case: column only have boolean value (Yes/No or 1/0)
                                case 1: //column page type
                                case 2: //column book title
                                    var select = $('<select style="width:100%"><option value="">Filter...</option></select>')
                                        .appendTo( $(column.footer()).empty() )
                                        .on( 'change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );

                                            column
                                                .search(val)
                                                .draw();
                                        } );

                                    column.data().unique().sort().each( function ( d, j ) {
                                        select.append( '<option value="'+d+'">'+d+'</option>' )
                                    } );
                                    break;

                                case 3:
                                    var input = $('<input type="text" style="width:100%" placeholder="Search..." value=""></input>')
                                        .appendTo( $(column.footer()).empty() )
                                        .on( 'keyup change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );

                                            column
                                                .search(val)
                                                .draw();
                                        } );
                                    break;
                            }
                        } );
                },
            } );

            //hide first column (DocID)
            table.column(0).visible(true);
            //hides the columns responsible for need's input
            table.column(6).visible(false);
            if(action == "catalog")
                table.column(5).visible(false);
            else table.column(4).visible(false);
            //only if admin is loged in, we show the 6th column
            <?php if($session->isAdmin()){ ?> table.column(6).visible(true); <?php } ?>


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
            //resize height of the scroller
            $("#divscroller").height($(window).outerHeight() - $(footer).outerHeight() - $("#page_title").outerHeight() - 50);
        });
    </script>

</head>
<!-- HTML BODY -->
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
                        <th width="50px"></th>
                        <th width="200px">Book Title</th>
                        <th width="150px">Library Index</th>
                        <th>Job Title</th>
                        <th width="50px">Needs Input</th>
                        <th width="50px">Needs Review</th>
                        <th width="40px"></th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th width="50px"></th>
                        <th width="200px">Book Title</th>
                        <th width="150px">Library Index</th>
                        <th></th>
                        <th width="50px" class="thBoolean">Needs Input</th>
                        <th width="50px" class="thBoolean">Needs Review</th>
                        <th width="40px"></th>
                    </tr>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>
</body>
</html>

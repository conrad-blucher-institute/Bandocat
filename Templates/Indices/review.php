<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col']) && isset($_GET['doc']))
{
    $collection = $_GET['col'];
    $docID = $_GET['doc'];
}

else header('Location: ../../');

require '../../Library/DBHelper.php';
require '../../Library/IndicesDBHelper.php';
require '../../Library/DateHelper.php';
require '../../Library/ControlsRender.php';

$Render = new ControlsRender();
$DB = new IndicesDBHelper();
//get appropriate db
$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
//select template indices document
$document = $DB->SP_TEMPLATE_INDICES_DOCUMENT_SELECT($collection, $docID);
//get the indices book
$book = $DB->GET_INDICES_BOOK($collection);
//get indices info
$info = $DB->GET_INDICES_INFO($collection, $docID);
$date = new DateHelper();
?>

<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <title>Review Form</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <!-- Put Page Contents Here -->
    <h1 class="text-center"><?php echo $config['DisplayName'];?> Review Form</h1>
    <hr>
    <div class="row pad-bottom">
        <?php
        if($session->isAdmin()) //if user is Admin, render the Document History (Log Info)
        {
            $arrayLogInfo = $DB->GET_LOG_INFO($collection, $docID);
            echo "<div class=\"col\"><h3 class='text-center'>Document History</h3>";
            echo "<table class=\"table table-sm table-striped table-bordered\"  cellspacing=\"0\" data-page-length='20'><thead><tr><th>Action</th><th>Username</th> <th>Timestamp</th></tr></thead><tbody>";

            $user = [];
            $length = count($arrayLogInfo);
            for ($x = 0; $x < $length; $x++) {
                $action[$x] = $arrayLogInfo[$x][0];
                $user[$x] = $arrayLogInfo[$x][1];
                $time[$x] = $arrayLogInfo[$x][2];
                echo "<tr><td>$action[$x]</td><td>$user[$x]</td><td id='timeStamp'>$time[$x]</td></tr>";
            }
            echo "</tbody></table></div>";
        }
        ?>
        <div class="col">
            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">Document Meta Data</h3>
                </div>
                <div class="card-body">
                    <form id="theform" name="theform" method="post" enctype="multipart/form-data" >
                        <div class="row">
                            <!-- These are used the most often -->
                            <div class="col">
                                <!-- Library Index -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="txtLibraryIndex">Library Index:</label>
                                    <div class="col-sm-8">
                                        <input type = "text" class="form-control" name = "txtLibraryIndex" id = "txtLibraryIndex" value='<?php echo htmlspecialchars($document['LibraryIndex'],ENT_QUOTES); ?>' required />
                                    </div>
                                </div>
                                <!-- Book Title -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="ddlBookTitle">Book Title:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="ddlBookTitle" name="ddlBookTitle" class="selectBookTitle">
                                            <?php
                                            $Render->GET_DDL($book, $document['BookName']);
                                            ?>
                                        </select>
                                        <input type="hidden" name="ddlBookID" id="ddlBookID" value=""/>
                                    </div>
                                </div>
                                <!-- Page Number -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="txtPageNumber">Page Number:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="txtPageNumber" id="txtPageNumber" value="<?php echo htmlspecialchars($document['PageNumber'],ENT_QUOTES);?>"/>
                                    </div>
                                </div>
                                <!-- Radio Buttons -->
                                <!-- Page Type -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Page Type:</label>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type = "radio" name = "rbPageType" id = "rbPageType_tableContent" size="26" value="General Index" checked/>
                                            <label class="form-check-label" for="rbPageType_tableContent">General Index</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type = "radio" name = "rbPageType" id = "rbIsMap_generalIndex" size="26" value="Table of Contents"/>
                                            <label class="form-check-label" for="rbIsMap_generalIndex">Table of Contents</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Needs Review -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Needs Review:</label>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input type = "radio" class="form-check-input" name = "rbNeedsReview" id = "rbNeedsReview_yes" value="1" <?php if($document['NeedsReview'] == 1) echo "checked"; ?>/>
                                            <label class="form-check-label" for="rbNeedsReview_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type = "radio" class="form-check-input" name = "rbNeedsReview" id = "rbNeedsReview_no" value="0" <?php if($document['NeedsReview'] == 0) echo "checked"; ?> />
                                            <label class="form-check-label" for="rbNeedsReview_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Comments -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="txtComments">Comments:</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" cols="35" rows="5" maxlength="1024" name="txtComments" id="txtComments" ><?php echo $document['Comments']?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="form row">
                            <div class="col">
                                <div class="d-flex justify-content-between">
                                    <input type="reset" id="btnReset" name="btnReset" value="Reset" class="btn btn-secondary"/>
                                    <input type = "hidden" id="txtDocID" name = "txtDocID" value = "<?php echo $docID; ?>" />
                                    <input type = "hidden" id="txtAction" name="txtAction" value="review" />  <!-- catalog or review -->
                                    <input type = "hidden" id="txtCollection" name="txtCollection" value="<?php echo $collection; ?>" />
                                    <?php if($session->hasWritePermission())
                                    {echo "<input type='submit' id='btnSubmit' name='btnSubmit' value='Upload' class='btn btn-primary'/>";}
                                    ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- Card -->
        </div>
    </div> <!-- Row -->
</div><!-- Container -->
<!-- Doesn't matter where these go, this is for overlay effect and loader -->
<div id="overlay"></div>
<?php include "../../Master/bandocat_footer.php" ?>

<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- JQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
    $(document).ready(function() {

        var docHeight = $(window).height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });
</script>
<script>
    /**********************************************
     * Function: setBookID
     * Description: Populates the DDL with books
     * Parameter(s):
     * Return value(s):
     * $result (assoc array) -
     ***********************************************/
    function setBookID()
    {
        var books = <?php echo json_encode($book); ?>;
        var bookValue = $("#ddlBookTitle :selected").val();
        for(var i = 0; i < books.length; i++) {
            if (books[i][0] == bookValue)
            {
                $('#ddlBookID').val(books[i][1]);
                return;
            }
        }
    }

    $( document ).ready(function()
    {
        //LIBRARY INDEX
        /*$document: array object that contains the selection from the bandocat_indicesinventory database, document table*/
        //Library index information retrieved from document array object
        $("#txtLibraryIndex").val("<?php echo $document['LibraryIndex']; ?>");
        setBookID();

        //Eventlistener that on change the new value of the drop down is send to the #ddlBookID hidden input for update
        $('#ddlBookTitle').on('change', function(e){
            setBookID();
        });



        //PAGE TYPE
        //Retrieve PageType from PHP fetched data to check the Page Type input radio button element
        var pageType = '<?php echo $document['PageType'];?>';
        var pageElement = document.getElementsByName('rbPageType');
        if(pageType == 'General Index' )
            pageElement[0].checked = true;
        if(pageType == 'Table of Contents' )
            pageElement[1].checked = true;

        //NEEDS REVIEW
        //Retrieved NeedsReview from PHP fetched data to check dynamically the Needs Review input radio element
        var needsReview = <?php echo $document['NeedsReview']?>;
        var reviewElement = document.getElementsByName('rbNeedsReview');
        if (needsReview == 1)
            reviewElement[0].checked = true;
        if (needsReview == 0)
            reviewElement[1].checked = true;


        //SUBMIT
        /* attach a submit handler to the form */
        $('#theform').submit(function (event) {
            /* stop form from submitting normally */
            var formData = new FormData($(this)[0]);
            /*jquery that displays the three points loader*/
            if(validateFormUnderscore("txtLibraryIndex") == true)
            {
                $('#overlay').show();
                event.disabled;

                event.preventDefault();
                /* Send the data using post */
                $.ajax({
                    type: 'post',
                    url: 'form_processing.php',
                    data:  formData,
                    processData: false,
                    contentType: false,
                    success:function(data){
                        var json = JSON.parse(data);
                        var msg = "";
                        var result = 0;
                        for(var i = 0; i < json.length; i++)
                        {
                            msg += json[i] + "\n";
                        }
                        for (var i = 0; i < json.length; i++){
                            if (json[i].includes("Success")) {
                                result = 1;
                            }
                            else if(json[i].includes("Fail") || json[i].includes("EXISTED"))
                            {
                                $('#loader').css("display", "none");
                            }
                        }
                        alert(msg);
                        if (result == 1){
                            self.close();
                        }

                    }
                });
            }
            else
            {
                //No _ was found in the string
                alert("Library Index does not contain an underscore character.                            " +
                    "Please check Library Index.");
            }

        });
        $("#divscroller").height($(window).outerHeight() - $(footer).outerHeight() - $("#page_title").outerHeight() - 55);
        $("#divleft").height($("#divscroller").height());
    });
</script>
</body>
</html>
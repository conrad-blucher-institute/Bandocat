<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();

//get collection name from passed variable col
if(isset($_GET['col']))
{
    $collection = $_GET['col'];
}
else header('Location: ../../');
require '../../Library/DBHelper.php';
require '../../Library/MapDBHelper.php';
require '../../Library/DateHelper.php';
require '../../Library/ControlsRender.php';
$Render = new ControlsRender();
$DB = new MapDBHelper();
$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
$date = new DateHelper();
$readrec = array("POOR","GOOD","EXCELLENT");

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

    <!-- Font Awesome CDN CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <title>Input Form</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container pad-bottom">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center"><?php echo $config["DisplayName"]; ?> Catalog Form</h1>
            <hr>

            <div class="d-flex justify-content-center">
                <!-- Card -->
                <div class="card" style="width: 75em;">
                    <div class="card-body">
                        <form id="theform" name="theform" method="post" enctype="multipart/form-data" >
                            <div class="row">
                                <!-- These are used the most often -->
                                <div class="col-6">
                                    <!-- Library Index -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtLibraryIndex">Library Index:</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" name = "txtLibraryIndex" id = "txtLibraryIndex" value="" required readonly />
                                        </div>
                                    </div>
                                    <!-- Document Title -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtTitle">Document Title:</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" name = "txtTitle" id = "txtTitle" value="" required />
                                        </div>
                                    </div>
                                    <!-- Subtitle -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtSubtitle">Document Subtitle:</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" name = "txtSubtitle" id = "txtSubtitle" value="" />
                                        </div>
                                    </div>
                                    <!-- Document start -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtSubtitle">Document Start Date:</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex">
                                                <select class="form-control" name="ddlStartMonth" id="ddlStartMonth">
                                                    <!-- POPULATES THE DDL WITH START MONTHS -->
                                                    <?php $Render->GET_DDL_MONTH(null); ?>
                                                </select>
                                                <select class="form-control" name="ddlStartDay" id="ddlStartDay">
                                                    <!-- POPULATES THE DDL WITH START DAYS -->
                                                    <?php $Render->GET_DDL_DAY(null); ?>
                                                </select>

                                                <select class="form-control" id="ddlStartYear" name="ddlStartYear">
                                                    <!-- POPULATES THE DDL WITH START YEARS -->
                                                    <?php $Render->GET_DDL_YEAR(null); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Document end -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtSubtitle">Document End Date:</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex">
                                                <select class="form-control" name="ddlEndMonth" id="ddlEndMonth">
                                                    <!-- POPULATES THE DDL WITH END MONTHS -->
                                                    <?php $Render->GET_DDL_MONTH(null); ?>
                                                </select>
                                                <select class="form-control" name="ddlEndDay" id="ddlEndDay">
                                                    <!-- POPULATES THE DDL WITH END DAYS -->
                                                    <?php $Render->GET_DDL_DAY(null); ?>
                                                </select>
                                                <select class="form-control" name="ddlEndYear" id="ddlEndYear">
                                                    <!-- POPULATES THE DDL WITH END YEARS -->
                                                    <?php $Render->GET_DDL_YEAR(null); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Map Scale -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtMapScale">Map Scale:</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" name = "txtMapScale" id = "txtMapScale" value=""  />
                                        </div>
                                    </div>
                                    <!-- Document Author -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtAuthor">Document Author:</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" list="lstAuthor" name = "txtAuthor" id = "txtAuthor" value="" />
                                            <datalist id="lstAuthor">
                                                <!-- POPULATE AUTHOR LIST-->
                                                <?php $Render->getDataList($DB->GET_AUTHOR_LIST($collection)); ?>
                                            </datalist>
                                        </div>
                                    </div>
                                    <!-- Radio Buttons -->
                                    <!-- is Map -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Is Map:</label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbIsMap" id = "rbIsMap_yes" value="1" checked/>
                                                <label class="form-check-label" for="rbIsMap_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbIsMap" id = "rbIsMap_no" value="0"  />
                                                <label class="form-check-label" for="rbIsMap_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Needs Review -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Needs Review:</label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbNeedsReview" id = "rbNeedsReview_yes" value="1" checked/>
                                                <label class="form-check-label" for="rbNeedsReview_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbNeedsReview" id = "rbNeedsReview_no" value="0"  />
                                                <label class="form-check-label" for="rbNeedsReview_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Has North Arrow -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Has North Arrow:</label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasNorthArrow" id = "rbHasNorthArrow_yes" value="1" checked/>
                                                <label class="form-check-label" for="rbHasNorthArrow_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasNorthArrow" id = "rbHasNorthArrow_no" value="0"  />
                                                <label class="form-check-label" for="rbHasNorthArrow_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Has Street -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Has Street:</label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasStreets" id = "rbHasStreets_yes" value="1" />
                                                <label class="form-check-label" for="rbHasStreets_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasStreets" id = "rbHasStreets_no" value="0" checked />
                                                <label class="form-check-label" for="rbHasStreets_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- POI -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Has POI:</label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasPOI" id = "rbHasPOI_yes" value="1" />
                                                <label class="form-check-label" for="rbHasPOI_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasPOI" id = "rbHasPOI_no" value="0"  checked/>
                                                <label class="form-check-label" for="rbHasPOI_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Has Coordinates -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Has Coordinates:</label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasCoordinates" id = "rbHasCoordinates_yes" value="1" />
                                                <label class="form-check-label" for="rbHasCoordinates_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasCoordinates" id = "rbHasCoordinates_no" value="0"  checked/>
                                                <label class="form-check-label" for="rbHasCoordinates_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Has Coast -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Has Coast:</label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasCoast" id = "rbHasCoast_yes" value="1" />
                                                <label class="form-check-label" for="rbHasCoast_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type = "radio" class="form-check-input" name = "rbHasCoast" id = "rbHasCoast_no" value="0" checked />
                                                <label class="form-check-label" for="rbHasCoast_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- the right side -->
                                <div class="col-6">
                                    <!-- Customer Name -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtCustomer">Customer Name:</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" list="lstCustomer" name = "txtCustomer" id = "txtCustomer" value="" />
                                            <datalist id="lstCustomer">
                                                <!-- POPULATES THE DDL WITH CUSTOMER NAMES -->
                                                <?php $Render->getDataList($DB->GET_CUSTOMER_LIST($collection)); ?>
                                            </datalist>
                                        </div>
                                    </div>
                                    <!-- Company Name -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtCompany">Company Name:</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" list="lstCompany" name = "txtCompany" id = "txtCompany" size="26" value="" />
                                            <datalist id="lstCompany">
                                                <!-- POPULATES DDL FOR COMPANY -->
                                                <?php $Render->getDataList($DB->GET_COMPANY_LIST($collection)); ?>
                                            </datalist>
                                        </div>
                                    </div>
                                    <!-- Document type or Job number -->
                                    <div class="form-group row">
                                        <?php if($collection != "pennyfenner") : ?>
                                            <label class="col-sm-4 col-form-label" for="txtType">Document Type</label>
                                            <div class="col-sm-8">
                                                <input type = "text" class="form-control" name = "txtType" id = "txtType" value="" />
                                            </div>
                                        <?php endif; ?>
                                        <?php if($collection == "pennyfenner") : ?>
                                            <label class="col-sm-4 col-form-label" for="txtJobNumber">Job Number</label>
                                            <div class="col-sm-8">
                                                <input type = "text" class="form-control" name = "txtJobNumber" id = "txtJobNumber" value=""/>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <!-- Field Book Number -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtFieldBookNumber">Field Book Number</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" name = "txtFieldBookNumber" id = "txtFieldBookNumber" value=""/>
                                        </div>
                                    </div>
                                    <!-- Field Book Page -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="txtFieldBookPage">Field Book Page</label>
                                        <div class="col-sm-8">
                                            <input type = "text" class="form-control" name = "txtFieldBookPage" id = "txtFieldBookPage" value="" />
                                        </div>
                                    </div>
                                    <!-- Document Medium -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="ddlMedium">Document Medium</label>
                                        <div class="col-sm-8">
                                            <select id="ddlMedium" name="ddlMedium" class="form-control" required>
                                                <!-- GET MAP MEDIUM FOR DDL-->
                                                <?php
                                                $Render->GET_DDL($DB->GET_TEMPLATE_MAP_MEDIUM_FOR_DROPDOWN($collection),null);
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Readability -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="ddlReadability">Readability</label>
                                        <div class="col-sm-8">
                                            <select id="ddlReadability" name="ddlReadability" class="form-control">
                                                <?php
                                                $Render->GET_DDL2($readrec,null);
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Rectifiability -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="ddlRectifiability">Rectifiability</label>
                                        <div class="col-sm-8">
                                            <select id="ddlRectifiability" name="ddlRectifiability" class="form-control">
                                                <?php
                                                $Render->GET_DDL2($readrec,null);
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Scan front -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Front Scan:</label>
                                        <div class="col-sm-8">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="fileUpload" id="fileUpload" accept=".tif" required />
                                                <label class="custom-file-label" for="fileUpload">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Scan Back -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Back Scan:</label>
                                        <div class="col-sm-8">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="fileUploadBack" id="fileUploadBack" accept=".tif" />
                                                <label class="custom-file-label" for="fileUploadBack">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Comments -->
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="txtComments" class="col-form-label">Comments:</label>
                                            <textarea class="form-control" cols="35" rows="5" name="txtComments" id="txtComments" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="form row">
                                <div class="col">
                                    <div class="d-flex justify-content-between">
                                        <input type="reset" id="btnReset" name="btnReset" value="Reset" class="btn btn-secondary"/>
                                        <input type = "hidden" id="txtDocID" name = "txtDocID" value = "" />
                                        <input type = "hidden" id="txtAction" name="txtAction" value="catalog" />  <!-- catalog or review -->
                                        <input type = "hidden" id="txtCollection" name="txtCollection" value="<?php echo $collection; ?>" />
                                        <span>
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
        </div> <!-- col -->
    </div> <!-- row -->
</div><!-- Container -->
<!-- Doesn't matter where these go, this is for overlay effect and loader -->
<div id="loader"></div>
<div id="overlay"></div>
<?php include "../../Master/bandocat_footer.php" ?>


<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>
<script type="text/javascript" src="../../Master/errorHandling.js"></script>

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
    $(document).ready(function() {
        var docHeight = $(window).height() - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });

    $(window).resize(function() {
        var docHeight = $(window).height() - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });
</script>

<script>
    $( document ).ready(function() {
        /* attach a submit handler to the form */
        $('#theform').submit(function (event) {
            /* stop form from submitting normally */
            var formData = new FormData($(this)[0]);
            /*jquery that displays the three points loader*/

            var error = errorHandling($('#txtLibraryIndex'), '<?php echo $collection ?>');
            if(error.answer){
                for(i = 0; i < error.desc.length; i++) {
                    alert(error.desc[i].message)
                }
                return false
            }
            var eScale = errorHandling($('#txtMapScale'), '<?php echo $collection ?>');
            if(eScale.answer){
                for(i = 0; i < eScale.desc.length; i++) {
                    alert(eScale.desc[i].message)
                }
                return false
            }

            //TODO:: removed libraryindex underscore validation
//            if(validateFormUnderscore("txtLibraryIndex") == true)
//            {
            $('#btnSubmit').css("display", "none");
            //$('#loader').css("display", "inherit");
            $("#overlay").show();
            $("#loader").show();
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
                            $('#btnSubmit').css("display", "inherit");
                            //$('#loader').css("display", "none");
                            $('#overlay').removeAttr("style").hide();
                            $('#loader').removeAttr("style").hide();
                        }
                    }
                    alert(msg);
                    if (result == 1){
                        window.location.href = "./catalog.php?col=<?php echo $_GET['col']; ?>";
                    }

                }
            });
        });
    });

    $("[type=file]").on("change", function(){
        // Name of file and placeholder
        var file = this.files[0].name;
        var dflt = $(this).attr("placeholder");
        if($(this).val()!=""){
            $(this).next().text(file);
        } else {
            $(this).next().text(dflt);
        }
    });

    $('#fileUpload').change(function() {
        var filename = $('#fileUpload').val().replace(/C:\\fakepath\\/i, '');
        filename = filename.replace(/\.tif/, '');
        $('#txtLibraryIndex').val(filename);
    });


</script>
</body>
</html>

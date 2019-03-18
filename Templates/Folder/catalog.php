<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
//var_dump($session);
$userRole = $session->getRole();
//get collection name from passed variables col and doc
if(isset($_GET['col']) && isset($_GET['doc']))
{
    $collection = $_GET['col'];
    $docID = $_GET['doc'];
}
else header('Location: ../../');

require '../../Library/DBHelper.php';
require '../../Library/FolderDBHelper.php';
require '../../Library/DateHelper.php';
require '../../Library/ControlsRender.php';
$Render = new ControlsRender();
$DB = new FolderDBHelper();
//get appropriate DB
$config = $DB->SP_GET_COLLECTION_CONFIG($collection);
//select fieldbook document
$document = $DB->SP_TEMPLATE_FOLDER_DOCUMENT_SELECT($collection, $docID);
$date = new DateHelper();
//select authors by document
$authors = $DB->GET_FOLDER_AUTHORS_BY_DOCUMENT_ID($collection,$docID);
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
    <title>Job Folder Catalog Form</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body onload="adminValidation()">
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <!-- <h1 class="text-center">Blank Page</h1> -->
            <hr>
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
                                                    <input type = "text" class="form-control" name="txtLibraryIndex" id="txtLibraryIndex" value="" disabled required />
                                                </div>
                                            </div>
                                            <!-- Document Title -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="txtTitle">Document Title:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="txtTitle" id="txtTitle" value="" required />
                                                </div>
                                            </div>
                                            <!-- Document Author -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="txtAuthor">Document Author:</label>
                                                <div class="col-sm-8">
                                                    <input type = "text" class="form-control" list="lstAuthor" name="txtAuthor" id="txtAuthor" value="" />
                                                    <datalist id="lstAuthor">
                                                        <!-- POPULATE AUTHOR LIST-->
                                                        <?php $Render->getDataList($DB->GET_AUTHOR_LIST($collection)); ?>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <!-- Radio Buttons Start -->
                                            <!-- Needs Review -->
                                            <div class="form-group row" id="needsReview">
                                                <label class="col-sm-4 col-form-label">Needs Review:</label>
                                                <div class="col-sm-8">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" name="folderNeedsReview" id="folderNeedsReview_yes" value="1" checked />
                                                        <label class="form-check-label" for="folderNeedsReview_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" name="folderNeedsReview" id="folderNeedsReview_no" value="0"/>
                                                        <label class="form-check-label" for="folderNeedsReview_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- In a Subfolder -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">In a Subfolder:</label>
                                                <div class="col-sm-8">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" name="inSubfolder" id="inSubfolder_yes" value="1" />
                                                        <label class="form-check-label" for="inSubfolder_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" name="inSubfolder" id="inSubfolder_no" value="0" checked />
                                                        <label class="form-check-label" for="inSubfolder_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Radio Buttons End -->
                                            <!-- Classification -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="ddlMedium">Classification:</label>
                                                <div class="col-sm-8">
                                                    <select id="ddlMedium" name="ddlMedium" class="form-control" required>
                                                        <!-- GET FOLDER CLASSIFICATION LIST -->
                                                        <?php
                                                        $Render->GET_DDL($DB->GET_FOLDER_CLASSIFICATION_LIST($collection),$document['Classification']);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Classification Comments -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="txtClassificationComments">Classification Comments:</label>
                                                <div class="col-sm-8" >
                                                    <textarea type="text" class="form-control" name="txtClassificationComments" id="txtClassificationComments" value=""></textarea>
                                                </div>
                                            </div>
                                            <!-- Subfolder Comments -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="txtSubfolderComments">Subfolder Comments:</label>
                                                <div class="col-sm-8" >
                                                    <textarea type="text" class="form-control" name="txtSubfolderComments" id="txtSubfolderComments" value=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- The Right Side -->
                                        <div class="col-6">
                                            <!-- Document Start Date -->
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
                                            <!-- Document End Date -->
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
                                            <!-- Front Scan -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Front Scan:</label>
                                                <div class="col-sm-8">
                                                    <div class="custom-file spinner-border text-dark" role="status">
                                                        <input type="file" class="custom-file-input" name="fileUpload" id="fileUpload" accept=".tif" onchange="frontUpload()" required/>
                                                        <label class="custom-file-label" for="fileUpload"></label>
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Scan Back -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label spinner-border text-dark" role="status">Back Scan:</label>
                                                <div class="col-sm-8">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="fileUploadBack" id="fileUploadBack" accept=".tif" onchange="backUpload()" />
                                                        <label class="custom-file-label" for="fileUploadBack"></label>
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- General Comments -->
                                            <div class="form-row">
                                                <div class="form-group col">
                                                    <label for="txtComments" class="col-form-label">Comments:</label>
                                                    <textarea class="form-control" cols="35" rows="5" name="txtComments" id="txtComments" placeholder="Example: Tract located in Corpus Christi, Nueces Co., Texas."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Buttons -->
                                    <div class="form row">
                                        <div class="col">
                                            <div class="d-flex justify-content-between">
                                                <input type="reset" id="btnReset" name="btnReset" value="Reset" onclick="resetPage()" class="btn btn-secondary"/>
                                                <input type = "hidden" id="txtDocID" name = "txtDocID" value = "" />
                                                <input type = "hidden" id="txtAction" name="txtAction" value="catalog" />  <!-- catalog or review -->
                                                <input type = "hidden" id="txtCollection" name="txtCollection" value="<?php echo $collection; ?>" />
                                                <span>
                                    <?php if($session->hasWritePermission())
                                    {echo "<input type='submit' id='btnSubmit' name='btnSubmit' value='Submit' class='btn btn-primary'/>";}
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

            <!-- MY PART ABOVE -->

        </div> <!-- col -->
    </div> <!-- row -->
</div><!-- Container -->
<?php include "../../Master/bandocat_footer.php" ?>

<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- JQuery UI cdn -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
    $(document).ready(function() {

        var docHeight = $(window).height() - $('#megaMenu').height();
        console.log(docHeight);
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });

    $( window ).resize(function() {
        var docHeight = $(window).height() -  - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
        {
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
        }
    });

    // AUTO POPULATING LIBRARY INDEX FIELD WITH NAME OF UPLOADED FILE. ALSO PERFORMS UPLOADED FILES VALIDATIONS.
    // UPLOADS THAT FAIL THE VALIDATION TEST ARE DISCARDED

    // Front scan check
    document.getElementById("fileUpload").onchange = frontUpload;
    function frontUpload() {
        var fileName = this.value;
        window.fileName = fileName;

        if ((fileName.includes("back") || fileName.includes("Back")) === true) {
            alert('Make sure to upload a front scan instead of a back scan.');
            document.getElementById('fileUpload').value = null;
            document.getElementById('txtLibraryIndex').value = null;
        }
        else{
            console.log('Valid File');
            document.getElementById('txtLibraryIndex').value = fileName.substring(12, fileName.indexOf('.tif'));
        }
    }

    // Back scan check
    document.getElementById("fileUploadBack").onchange = backUpload;
    function backUpload() {
        var backFileName = this.value;
        window.backFileName = backFileName;

        if ((backFileName.includes("back") || backFileName.includes("Back")) === false) {
            alert('Make sure to upload a back scan instead of a front scan.');
            document.getElementById('fileUploadBack').value = null;
        }
        else{
            console.log('Valid File');
        }
    }

    // Page reload
    function resetPage(){
        window.location.reload();
    }

    // HIDES "NEEDS REVIEW" DIV IF CURRENT USER IS NOT AN ADMIN
    function adminValidation(){
        var userRole = "<?php echo $userRole ?>";
        if ((userRole === "Admin") || (userRole === "admin")){
            //document.getElementById('needsReview').style.display = 'yes';
            console.log('Display. User is admin');
        }
        else{
            document.getElementById('needsReview').style.display = 'none';
            console.log("Hide. User is not admin");
        }
    }

</script>
</body>
</html>

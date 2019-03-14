<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
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
    <title>Catalog Form</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center"><?php echo $config['DisplayName'];?> Catalog Form</h1>
            <hr>
        </div> <!-- col -->
    </div> <!-- row -->
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-center">
                <div class="card" style="width: 40em;">
                    <div class="card-body">
                        <form id="theform" name="theform" enctype="multipart/form-data" >
                            <!-- Populates the control with data -->
                            <datalist id="lstAuthor">
                                <?php $Render->getDataList($DB->GET_AUTHOR_LIST($collection)); ?>
                            </datalist>
                            <!-- Library Index and Document title -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="txtLibraryIndex">Library Index</label>
                                        <input type = "text" name = "txtLibraryIndex" class="form-control" id = "txtLibraryIndex" size="26" value='<?php echo htmlspecialchars($document['LibraryIndex'],ENT_QUOTES);?>' required />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="txtTitle">Document Title</label>
                                        <input type = "text" name = "txtTitle" class="form-control" id = "txtTitle" size="26" required="true" value='<?php echo htmlspecialchars($document['Title'],ENT_QUOTES);?>' />
                                    </div>
                                </div>
                            </div>
                            <!-- Radio buttons, Needs review and in a subfolder -->
                            <div class="row">
                                <div class="col">
                                    <label for="rbNeedsReview">Needs Review</label>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name = "rbNeedsReview" id = "rbNeedsReview_yes" size="26" value="1" <?php if($document['NeedsReview'] == 1) echo "checked"; ?> >
                                            <label class="form-check-label" for="rbNeedsReview_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name = "rbNeedsReview" id = "rbNeedsReview_no" size="26" value="0" <?php if($document['NeedsReview'] == 0) echo "checked"; ?> >
                                            <label class="form-check-label" for="rbNeedsReview_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="rbNeedsReview">In a Subfolder</label>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name = "rbInASubfolder" id = "rbInASubfolder_yes" size="26" value="1" <?php if($document['InSubfolder'] == 1) echo "checked"; ?> >
                                            <label class="form-check-label" for="rbInASubfolder_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name = "rbInASubfolder" id = "rbInASubfolder_no" size="26" value="0" <?php if($document['InSubfolder'] == 0) echo "checked"; ?> >
                                            <label class="form-check-label" for="rbInASubfolder_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Start date, end date, and classification -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="ddlStart">Document Start Date</label>
                                        <div class="d-flex">
                                            <!-- GET START DDL MONTH -->
                                            <select class="form-control" name="ddlStartMonth" id="ddlStartMonth">
                                                <?php $Render->GET_DDL_MONTH($date->splitDate($document['StartDate'])['Month']); ?>
                                            </select>
                                            <!-- GET START DDL DAY -->
                                            <select class="form-control" name="ddlStartDay" id="ddlStartDay">
                                                <?php $Render->GET_DDL_DAY($date->splitDate($document['StartDate'])['Day']); ?>
                                            </select>
                                            <!-- GET START DDL YEAR -->
                                            <select class="form-control" id="ddlStartYear" name="ddlStartYear">
                                                <?php $Render->GET_DDL_YEAR($date->splitDate($document['StartDate'])['Year']); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="ddlEnd">Document End Date</label>
                                        <div class="d-flex">
                                            <!-- GET END DDL MONTH -->
                                            <select class="form-control" name="ddlEndMonth" id="ddlEndMonth">
                                                <?php $Render->GET_DDL_MONTH($date->splitDate($document['EndDate'])['Month']); ?>
                                            </select>
                                            <!-- GET END DDL DAY -->
                                            <select class="form-control" name="ddlEndDay" id="ddlEndDay">
                                                <?php $Render->GET_DDL_DAY($date->splitDate($document['EndDate'])['Day']); ?>
                                            </select>
                                            <!-- GET END DDL YEAR -->
                                            <select class="form-control" name="ddlEndYear" id="ddlEndYear">
                                                <?php $Render->GET_DDL_YEAR($date->splitDate($document['EndDate'])['Year']); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Subfolder Comments and classification -->
                            <div class="row">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</script>
</body>
</html>
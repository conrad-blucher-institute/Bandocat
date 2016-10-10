<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Input Form</title>
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>

</head>
<body>
<style>
    html, body {
        overflow: visible;

    }
    footer {
        position: fixed;
        min-width: 100%;
    }

    /*Account Stylesheet Adaptation from Collection Name */
    .Account{
        border-radius: 2%;
        box-shadow: 0px 0px 4px;
    }

    .Account_Table{
        background-color: white;
        padding: 3%;
        border-radius: 6%;
        box-shadow: 0px 0px 2px;
        margin: auto;
        font-family: verdana;
        text-align: left;
        margin-top: 2%;
        margin-bottom: 9%;

    }

    .Account_Table .Account_Title{
        margin-top: 2px;
        margin-bottom: 12px;
        color: #008852;
    }

    .Account_Table .Collection_data{
        width: 50%;
    }
    }
    #col1{float:left;width:460px;height:100%;padding-left:80px;}
    #col2{float:left;width:500px;height:100%;padding-left:5px;}
    #row{float:bottom;width:2000px;height:52px;background-color: #ccf5ff;}

    .cell
    {
        min-height: 52px;
    }

    .label
    {
        float:left;
        width:150px;
        min-width: 195px;
        padding-top:2px;
    }
    .labelradio
    {
        float:left;
        width:150px;
        min-width: 195px;
    }
    mark {
        background-color: #ccf5ff;
    }
    span.labelradio:hover p{
        z-index: 10;
        display: inline;
        position: absolute;
        border: 1px solid #000000;
        background: #bfe9ff;
        font-size: 14px;
        font-style: normal;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px; -o-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 4px 4px 4px #36c476;
        -moz-box-shadow: 4px 4px 4px #36c476;
        box-shadow: 4px 4px 4px #36c476;
        width: 200px;
        padding: 10px 10px;
    }
    p
    {
        font-size: 14px;
        margin-left: 40%;
    }
</style>
<table id = "thetable">

    <script type="text/javascript" src="PasswordMatch.js"></script>
    <tr>
        <td class="menu_left" id="thetable_left">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </td>
        <td class="Account" id="thetable_right">
            <h2>Input Form</h2>
            <table class="Account_Table">
                <p style = "color:red;">(*) required field</p>
                <p style = "color:red;">(Hover mouse on 'Has POI' for more information)</p>
                <form id="frm_auth" name="frm_auth" method="post" action="Account_Processing.php">
                    <tr>
                        <td id="col1">
                            <div class="cell">
                                <span class="label"><span style = "color:red;"> * </span>Library Index:</span>
                                <input type = "text" name = "libraryindex" id = "libraryindex" size="26" value="" required="true" /><span class = "errorInput" id = "libraryindexErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label"><span style = "color:red;"> * </span>Document Title:</span>
                                <input type = "text" name = "documenttitle" id = "documenttitle" size="26" value="" required="true" /><span class = "errorInput" id = "documenttitleErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label">Document Subtitle:</span>
                                <input type = "text" name = "documentsubtitle" id = "documentsubtitle" size="26" value="" required="false" /><span class = "errorInput" id = "documentsubtitleErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label">Map Scale:</span>
                                <input type = "text" name = "mapscale" id = "mapscale" size="26" value="" required="false" /><span class = "errorInput" id = "mapscaleErr"></span>
                            </div>
                            <div class="cell">
                                <span class="labelradio"><mark>Is Map:</mark><p hidden><b></b>This is to signal if it is a map</p></span>
                                <input type = "radio" name = "ismap" id = "ismap_yes" size="26" value="Yes" checked="true"/>Yes
                                <input type = "radio" name = "ismap" id = "ismap_no" size="26" value="No"  />No
                            </div>
                            <div class="cell" >
                                <span class="labelradio" ><mark>Needs Review:</mark><p hidden><b></b>This is to signal if a review is needed</p></span>
                                <input type = "radio" name = "needsreview" id = "needsreview_yes" size="26" value="Yes" checked="true"/>Yes
                                <input type = "radio" name = "needsreview" id = "needsreview_no" size="26" value="No" />No
                            </div>
                            <div class="cell">
                                <span class="labelradio"><mark>Has North Arrow:</mark><p hidden><b></b>This is to signal if it has a North Arrow</p></span>
                                <input type = "radio" name = "needsreview" id = "needsreview_yes" size="26" value="Yes" checked="true"/>Yes
                                <input type = "radio" name = "needsreview" id = "needsreview_no" size="26" value="No"  />No
                            </div>
                            <div class="cell">
                                <span class="labelradio"><mark>Has Street:</mark><p hidden><b></b>This is to signal if a Street(s) are present</p></span>
                                <input type = "radio" name = "hasStreet" id = "hasStreet_yes" size="26" value="Yes" checked="true"/>Yes
                                <input type = "radio" name = "hasStreet" id = "hasStreet_no" size="26" value="No"  />No
                            </div>
                            <div class="cell">
                                <span class="labelradio"><mark>Has POI:</mark><p hidden><b></b>This is to signal if a Point of Interest is present</p></span>
                                <input type = "radio" name = "hasPOI" id = "hasPOI_yes" size="26" value="Yes" checked="true"/>Yes
                                <input type = "radio" name = "hasPOI" id = "hasPOI_no" size="26" value="No"  />No
                            </div>
                            <div class="cell">
                                <span class="labelradio"><mark>Has Coordinates:</mark><p hidden><b></b>This is to signal if Coordinates are visible</p></span>
                                <input type = "radio" name = "hascoordinates" id = "hascoordinates_yes" size="26" value="Yes" checked="true" />Yes
                                <input type = "radio" name = "hascoordinates" id = "hascoordinates_no" size="26" value="No"  />No
                            </div>
                            <div class="cell">
                                <span class="labelradio"><mark>Has Coast:</mark><p hidden><b></b>This is to signal if a Coast line is present</p></span>
                                <input type = "radio" name = "hascoast" id = "hascoast_yes" size="26" value="Yes" />Yes
                                <input type = "radio" name = "hascoast" id = "hascoast_no" size="26" value="No" checked="true" />No
                            </div>
                            <div class="cell">
                                <span class="label"><span style = "color:red;"> * </span>Scan Of Front:</span>
                                <input type="file" name="fileupload" id="fileupload" accept="image/*" required="true" /><span class = "errorInput" id = "fileuploadErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label"><span style = "color:red;"> &nbsp; </span>Comments:</span>
                                <textarea name = "comments" rows = "5" cols = "35" id="comments"/></textarea>
                            </div>
                        </td>
                        <td id="col2">
                            <div class="cell">
                                <span class="label">Customer Name:</span>
                                <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label">Document Start Date:</span>
                                <select name="day" id="day">
                                    <option value="">Day</option>
                                    <script type="text/javascript" src="DateJS.js"></script>
                                </select>
                                <select name="month" id="month">
                                    <option value="">Month</option>
                                    <script type="text/javascript" src="DateJS.js"></script>
                                </select>

                                <select id="year" name="year">
                                    <option value="">Year</option>
                                    <script type="text/javascript" src="DateJS.js"></script>
                                </select>

                            </div>
                            <div class="cell">
                                <span class="label">Document End Date:</span>
                                <select name="day" id="day2">
                                    <option value="">Day</option>
                                    <script type="text/javascript" src="DateJS.js"></script>
                                </select>

                                <select name="month" id="month2">
                                    <option value="">Month</option>

                                </select>
                                <select name="year" id="year2">
                                    <option value="">Year</option>
                                    <script type="text/javascript" src="DateJS.js"></script>
                                </select>
                            </div>
                            <div class="cell">
                                <span class="label">Fieldbook Number:</span>
                                <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label">Field Book Page:</span>
                                <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label"><span style = "color:red;"> * </span>Readability:</span>
                                <select id="bookid" name="bookid" style="width:210px" required="true">
                                    <?php
                                    //this part will collect all book title fields and populate them to the dropdown booktitle
                                    $query = mysql_query("SELECT id,book_title FROM indicesinventory.books");
                                    while($row = mysql_fetch_array($query))
                                        echo "<option value='" . $row[0] . "'>$row[1]</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="cell" >
                                <span class="label"><span style = "color:red;"> * </span>Rectifiability:</span>
                                <select id="bookid" name="bookid" style="width:210px" required="true">
                                    <?php
                                    //this part will collect all book title fields and populate them to the dropdown booktitle
                                    $query = mysql_query("SELECT id,book_title FROM indicesinventory.books");
                                    while($row = mysql_fetch_array($query))
                                        echo "<option value='" . $row[0] . "'>$row[1]</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="cell">
                                <span class="label">Company Name:</span>
                                <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label">Document type:</span>
                                <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label"><span style = "color:red;"> * </span>Document Medium:</span>
                                <select id="bookid" name="bookid" style="width:210px">
                                    <?php
                                    //this part will collect all book title fields and populate them to the dropdown booktitle
                                    $query = mysql_query("SELECT id,book_title FROM indicesinventory.books");
                                    while($row = mysql_fetch_array($query))
                                        echo "<option value='" . $row[0] . "'>$row[1]</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="cell">
                                <span class="label">Document Author:</span>
                                <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                            </div>
                            <div class="cell">
                                <span class="label"><span style = "color:red;"> * </span>Scan Of Back:</span>
                                <input type="file" name="fileupload" id="fileupload" accept="image/*" required="true" /><span class = "errorInput" id = "fileuploadErr"></span>
                            </div>
                            <div class="cell">
                                <input type = "hidden" name = "userIDInput" value = "<?php echo $userid; ?>" />
                                <input type = "hidden" name = "docID" value = "" />
                                <input type = "hidden" name="action" value="input" />  <!-- input or edit -->
                                <span><input type="submit" id="btnSubmit" name="btnSubmit" value="Reset" class="button button-blue"/></span>
                                <span><input type="submit" id="btnSubmit" name="btnSubmit" value="Upload" class="button button-blue"/></span>
                            </div>
                        </td>
                    </tr>
                </form>

            </table>

        </td>
    </tr>

</table>

<?php include '../../Master/footer.php'; ?>

</body>
</html>

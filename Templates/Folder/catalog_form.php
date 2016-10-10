<?php
//Menu
?>
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
<body style="height: 100%">
<style>
    /*Account Stylesheet Adaptation from Collection Name */
    .Account{
        border-radius: 2%;
        box-shadow: 0px 0px 4px;
    }

    .Account_Table{
        background-color: white;
        padding: 3%;
        padding-top: 1%;
        padding-bottom: 1%;
        border-radius: 5%;
        box-shadow: 0px 0px 2px;
        font-family: verdana;
        text-align: left;
        margin-left: 5%;
        border-spacing: 1.5px;
        width: 90%;
        padding-right: 5%;
    }

    .Account_Table .Account_Title{
        margin-top: 2px;
        margin-bottom: 12px;
        color: #008852;
    }

    .Account_Table .Collection_data{
        width: 50%;
    }

    td #col1{float:left;width:85%;padding-left:60px;}
    td #col2{float:left;width:85%;padding-left:5px;}
    #title{padding-top:30px;}

    .cell
    {
        padding-top: 2%;
        padding-left: 2%;
        background-color: #e6e6e6;
        height: 2em;
        border-radius: 1%;
    }
    .label
    {
        float: left;
        min-width: 40%;
        font-size: 90%;
    }
    .labelradio
    {
        float:left;
        width:150px;
        min-width: 195px;
        background-color: #ccf5ff;
    }
    .Account_Table2{
        width: 110%;
        margin-left: 5%;
    }
    span.labelradio:hover a{
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
        -webkit-box-shadow: 4px 4px 4px #bdfcd1;
        -moz-box-shadow: 4px 4px 4px #bdfcd1;
        box-shadow: 4px 4px 4px #bdfcd1;
        width: 200px;
        padding: 10px 10px;
    }

    .Input_table{
        width: 110%;
        margin-left: -10%;
    }

    input{
        width: 50%;
        height: 50%;
    }
    .Radio_option{
        width: 8%;
    }

    select{width: 16%}
    #thetable_right{
        padding-bottom: 2%;
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
            <table class="Account_Table" style="overflow-scrolling: auto">
                <form id="frm_auth" name="frm_auth" method="post" action="Account_Processing.php">
                    <tr>
                        <td>
                            <table class="Input_table">
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="label"><span style = "color:red;"> * </span>Library Index:</span>
                                            <input type = "text" name = "libraryindex" id = "libraryindex" size="26" value="" required="true" /><span class = "errorInput" id = "libraryindexErr"></span>
                                        </div>
                                    </td>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="label"><span style = "color:red;"> * </span>Document Title:</span>
                                            <input type = "text" name = "documenttitle" id = "documenttitle" size="26" value="" required="true" /><span class = "errorInput" id = "documenttitleErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="label">Document Subtitle:</span>
                                            <input type = "text" name = "documentsubtitle" id = "documentsubtitle" size="26" value="" required="false" /><span class = "errorInput" id = "documentsubtitleErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="label">Map Scale:</span>
                                            <input type = "text" name = "mapscale" id = "mapscale" size="26" value="" required="false" /><span class = "errorInput" id = "mapscaleErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="labelradio">Is Map:<a hidden><b></b>This is to signal if it is a map</a></span>
                                            <input type = "radio" class="Radio_option"name = "ismap" id = "ismap_yes" size="26" value="Yes" checked="true"/>Yes
                                            <input type = "radio" class="Radio_option" name = "ismap" id = "ismap_no" size="26" value="No"  />No
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell" >
                                            <span class="labelradio" >Needs Review:<a hidden><b></b>This is to signal if a review is needed</a></span>
                                            <input type = "radio" class="Radio_option" name = "needsreview" id = "needsreview_yes" size="26" value="Yes" checked="true"/>Yes
                                            <input type = "radio" class="Radio_option" name = "needsreview" id = "needsreview_no" size="26" value="No" />No
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="labelradio">Has North Arrow:<a hidden><b></b>This is to signal if it has a North Arrow</a></span>
                                            <input type = "radio" class="Radio_option" name = "needsreview" id = "needsreview_yes" size="26" value="Yes" checked="true"/>Yes
                                            <input type = "radio" class="Radio_option" name = "needsreview" id = "needsreview_no" size="26" value="No"  />No
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="labelradio">Has Street:<a hidden><b></b>This is to signal if a Street(s) are present</a></span>
                                            <input type = "radio" class="Radio_option" name = "hasStreet" id = "hasStreet_yes" size="26" value="Yes" checked="true"/>Yes
                                            <input type = "radio" class="Radio_option" name = "hasStreet" id = "hasStreet_no" size="26" value="No"  />No
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="labelradio">Has POI:<a hidden><b></b>This is to signal if a Point of Interest is present</a></span>
                                            <input type = "radio" class="Radio_option" name = "hasPOI" id = "hasPOI_yes" size="26" value="Yes" checked="true"/>Yes
                                            <input type = "radio" class="Radio_option" name = "hasPOI" id = "hasPOI_no" size="26" value="No"  />No
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="labelradio">Has Coordinates:<a hidden><b></b>This is to signal if Coordinates are visible</a></span>
                                            <input type = "radio" class="Radio_option" name = "hascoordinates" id = "hascoordinates_yes" size="26" value="Yes" checked="true" />Yes
                                            <input type = "radio" class="Radio_option" name = "hascoordinates" id = "hascoordinates_no" size="26" value="No"  />No
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell">
                                            <span class="labelradio">Has Coast:<a hidden><b></b>This is to signal if a Coast line is present</a></span>
                                            <input type = "radio" class="Radio_option" name = "hascoast" id = "hascoast_yes" size="26" value="Yes" />Yes
                                            <input type = "radio" class="Radio_option" name = "hascoast" id = "hascoast_no" size="26" value="No" checked="true" />No
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell" style="height: 2.5em">
                                            <span class="label"><span style = "color:red;"> * </span>Scan Of Front:</span>
                                            <input type="file" name="fileupload" id="fileupload" accept="image/*" required="true" /><span class = "errorInput" id = "fileuploadErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col1">
                                        <div class="cell" style="height: 5.58em;">
                                            <span class="label"><span style = "color:red;"> &nbsp; </span>Comments:</span>
                                            <textarea name = "comments" rows = "5" cols = "35" id="comments" style="margin-left: 30%; margin-top: -4%"></textarea>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        <td style="width: 50%">
                            <table class="Account_Table2">
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label">Customer Name:</span>
                                            <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
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
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
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
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label">Fieldbook Number:</span>
                                            <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label">Field Book Page:</span>
                                            <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label"><span style = "color:red;"> * </span>Readability Assessment:</span>
                                            <select id="bookid" name="bookid" style="width:40%" required="true">
                                                <?php
                                                //this part will collect all book title fields and populate them to the dropdown booktitle
                                                $query = mysql_query("SELECT id,book_title FROM indicesinventory.books");
                                                while($row = mysql_fetch_array($query))
                                                    echo "<option value='" . $row[0] . "'>$row[1]</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label"><span style = "color:red;"> * </span>Rectifiability Assessment:</span>
                                            <select id="bookid" name="bookid" style="width:40%" required="true">
                                                <?php
                                                //this part will collect all book title fields and populate them to the dropdown booktitle
                                                $query = mysql_query("SELECT id,book_title FROM indicesinventory.books");
                                                while($row = mysql_fetch_array($query))
                                                    echo "<option value='" . $row[0] . "'>$row[1]</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label">Company Name:</span>
                                            <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label">Document type:</span>
                                            <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label"><span style = "color:red;"> * </span>Document Medium:</span>
                                            <select id="bookid" name="bookid" style="width:40%">
                                                <?php
                                                //this part will collect all book title fields and populate them to the dropdown booktitle
                                                $query = mysql_query("SELECT id,book_title FROM indicesinventory.books");
                                                while($row = mysql_fetch_array($query))
                                                    echo "<option value='" . $row[0] . "'>$row[1]</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell">
                                            <span class="label">Document Author:</span>
                                            <input type = "text" name = "customername" id = "customername" size="26" value="" required="true" /><span class = "errorInput" id = "customernameErr"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="col2">
                                        <div class="cell" style="height: 2.5em">
                                            <span class="label"><span style = "color:red;"> * </span>Scan Of Back:</span>
                                            <input type="file" name="fileupload" id="fileupload" accept="image/*" required="true" /><span class = "errorInput" id = "fileuploadErr"></span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </td>
                    </tr>
            </table></form>
</table>

<?php include '../../Master/footer.php'; ?>


</body>
</html>

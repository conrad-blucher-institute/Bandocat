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

    <title>Welcome to BandoCat!</title>
    <link rel = "stylesheet" type = "text/css" href = "CSS/Map_Collection.css" >
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>

</head>
<body>
<div id="wrap"></div>
    <div id="main"></div>
        <div id = "divleft">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </div>
        <div id="divright">
            <h2>Document Upload</h2>
            <table class="Collection_Table">
                <tr>
                    <td class="Collection_data">
                        <input type="file" name="file_array[]" id="file_array" class="bluebtn" accept="image/tiff" value="Input Map Information" multiple/>
                    </td>
                </tr>
                <tr>
                    <td class="Collection_data" >
                        <div class="Collection_Button" id = "selectedFiles">Selected Files</div>
                    </td>
                </tr>
                <tr>
                    <td class="Collection_data">
                        <input type="submit" class="bluebtn" value="Upload files" id="btnUpload"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="color:red; font-size: .45em"><br>*Recommended number of files for uploading: 70 files<br>If a file is more than 100MB, upload no more than 5 files simultaneously </p>
                    </td>
                </tr>
            </table>
        </div>


<script>
    var selDiv = "";
    var totalFsize = 0;


    document.addEventListener("DOMContentLoaded", init, false);

    function init() {
        document.querySelector('#file_array').addEventListener('change', handleFileSelect, false);
        selDiv = document.querySelector("#selectedFiles");
    }

    function handleFileSelect(e) {
        var total = 0;
        totalFsize = 0;
        if(!e.target.files) return;

        selDiv.innerHTML = "";

        var files = e.target.files;
        for(var i=0; i<files.length; i++) {
            var f = files[i];
            totalFsize += f.size/1000000;
            total = totalFsize.toFixed(2);

            selDiv.innerHTML += "<ul><li>" +  f.name + "\t" +(f.size/1000000).toFixed(2) + " mb" + "</li></ul>";
        }
        selDiv.innerHTML += "Total file upload " + total+" mb";
    }

</script>

<style>
    nav{margin-left: 8px;
        margin-top: 22px;}
    ul{
        text-align: left;
        margin:auto;
        white-space: pre;
    }
    #selectedFiles{
        height:10em;
        overflow: auto;
    }

    #file_array{
        height:90%;
        padding: 1%;
        overflow: auto;
    }
    pre{
        tab-size: 4;
    }
    #btnUpload{
        height:100%}
    .bluebtn{
        font-size: 0.4em !important;
        padding-bottom: 24px;
    }

    #divright{
        font-size:45% !important;
        vertical-align: top;
        background: #f1f1f1;
        border-radius: 2%;
        box-shadow: 0px 0px 2px;
    }
</style>
<?php include '../../Master/footer.php'; ?>

</body>
</html>

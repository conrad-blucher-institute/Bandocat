<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if($session->isAdmin() == false) {
    header('Location: ../../');
}
include '../../Library/DBHelper.php';
$DB = new DBHelper();
include '../../Library/ControlsRender.php';
$Render = new ControlsRender();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Manage Map Kind</title>

    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.js"></script>
    <style>
        #frm_user{
            height:300px;
            width:600px;
            min-width:500px;
            margin:0 auto;
        }
        #frm_user table{margin-top:20px;}
        #ddl_user{line-height:40px;}
        label{font-size:1.2em;}
        select{height:30px;}
    </style>
</head>
<body>
<div id="wrap">
    <div id="main">
        <div id="divleft">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php';?>
        </div>
        <div id="divright">
            <h2 id="page_title">Manage Users</h2>
            <div id="divscroller">
                <table class="Account_Table">
                    <form id="frm_mapkind" name="frm_mapkind" method="post">
                        <tr>
                            <td colspan="2" style="text-align: center">
                                <label>Input New Map Kind:</label><br><br>
                                <input type="text" name="mapkind"><br>
                                <div style="text-align:center"><p style="font-size:1.2em;" hidden id="txtResult"></div>
                            </td>
                        </tr>
                        <td colspan="2" style="text-align: center">
                            <br>
                            <input type="submit" value="Create" id="btnNewMapkind" class="bluebtn" name="btnNewMapkind"/>
                        </td>
                        <br>

                    </form>
                </table>
            </div>
            <?php include '../../Master/footer.php'; ?>
</body>
<script>
    $("#btnNewMapkind").click(function(event){
        $("#txtPrompt").hide();
        event.preventDefault();
        var bla = $('#mapkind').val(); //Debug
        $('#mapkind').val(bla) //Debug
        //alert(bla); //Debug
        $.ajax({
            type: 'post',
            url: "managemapkind_processing.php",
            data: $("#frm_mapkind").serializeArray()
        }).success(function (data) {
            $("#txtResult").show().text(data);
        });
    });
</script>
<style>
    /*Account Stylesheet Adaptation from Collection Name */
    .Account{
        border-radius: 2%;
        box-shadow: 0px 0px 4px;
    }

    .Account_Table{
        background-color: #e8eaed;
        padding: 3%;
        border-radius: 6%;
        box-shadow: 0px 0px 2px;
        margin: auto;
        font-family: verdana;
        vertical-align: middle;
        margin-top: 4%;
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

</style>
</html>

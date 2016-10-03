<?php
session_start();
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
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
</head>

<body>
<table id="thetable">
    <tr class="Top_Row">
        <th class="menu_left" id="thetable_left"> <?php include '../../Master/header.php'?> </th>
        <th class="Top" colspan="2"></th>
    </tr>
    <tr class="Bottom" style="height: 10px"></tr>
        <td class="menu_left" id="thetable_left"> <?php include '../../Master/sidemenu.php' ?> </td>
        <td class="Bottom-right" colspan="2">
            <h2>Error reporting</h2>
            <div class= "Error_Input" >
                <h3>Database Name:</h3>
                <select name="ddl_dbname" id="ddl_dbname">
                    <option value=1">Blucher Maps</option><option value=2">Green Maps</option><option value=3">Job Folder</option><option value=4">Field Book</option>				</select>

                <h3>Library Index:</h3>
                <input type = "text" name = "libraryindex" id = "libraryindex" size="32" required/>

                <h3>What's wrong?</h3>
                <textarea name = "desc" rows = "10" cols = "70"/></textarea>

                <br><input type = "submit" name = "Send" value = "Send" class="bluebtn"/>
                <input type = "hidden" name = "userIDInput" value = "31" />
            </div>
        </td>
    </tr>
</table>

<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0; width: 90%; height: 650px; table-layout: fixed;}
    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;overflow:hidden;word-break:normal;}
    .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal; text-align: left}
    .tg .Bottom-left{font-size:14px;font-family:serif !important;;text-align:center;vertical-align:top}
    .tg .Top{vertical-align:top; width: 35%; 100px}
    .tg .Bottom-right{font-size:13px;font-family:serif !important;;vertical-align:top; background-color: #f1f1f1; border-radius: 10px; border-width: 0px; box-shadow: 0px 0px 2px #0c0c0c;}
    .tg #Bottom_Left.Left{vertical-align: top}
    .tg .Left{width: 17%}
    .tg .Top_Row{height: 12%}
    .Error_Input{margin-left: 9%; margin-top: 5%;}
    nav{margin: 15px 0px 40px 15px !important;}
</style>

<?php include '../../Master/footer.php'; ?>

</body>
</html>
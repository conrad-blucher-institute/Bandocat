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
        <th class="Top" colspan="2"><h2>Error reporting</h2></th>
    </tr>
    <tr class="Bottom" style="height: 10px"></tr>
        <td class="menu_left" id="thetable_left"> <?php include '../../Master/sidemenu.php' ?> </td>
        <td class="Bottom-right" colspan="2">

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
    .Error_Input{margin-left: 10%; margin-top: 0%; background-color: #f1f1f1; border-radius: 10px; border-width: 0px; box-shadow: 0px 0px 2px #0c0c0c; padding-left: 8%; margin-right: 10%; padding-bottom: 5%; padding-top: 2.5%;}
    nav{margin: -1px 0px 40px 15px !important;}
</style>

<?php include '../../Master/footer.php'; ?>

</body>
</html>
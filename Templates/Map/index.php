<?php
//Menu
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col'])) {
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
}
else header('Location: ../../');
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title><?php echo $config["DisplayName"] . " Menu"; ?></title>
        <link rel = "stylesheet" type = "text/css" href = "CSS/Map_Collection.css" >
        <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
        <script type="text/javascript" src="ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>

    </head>
    <body>
    <table id = "thetable">
        <tr>
            <td class="menu_left" id="thetable_left">
                <?php include '../../Master/header.php';
                include '../../Master/sidemenu.php' ?>
            </td>
            <td class="Collection" id="thetable_right">
                    <h2>Menu</h2>
                    <table class="Collection_Table">
                        <tr>
                            <td>
                                <h4 class="Collection_Title"><?php echo $config["DisplayName"]; ?></h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="Collection_data">
                                <input type="button" class="Collection_Button" value="Input Map Information"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="Collection_data">
                                <input type="button" class="Collection_Button" value="Edit/View Map Information" />
                            </td>
                        </tr>
                        <tr>
    </table>

    <?php include '../../Master/footer.php'; ?>

    </body>
</html>

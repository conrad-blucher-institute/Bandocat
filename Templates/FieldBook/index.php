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

    <title><?php echo $config["DisplayName"]." Menu"; ?></title>
    <link rel = "stylesheet" type = "text/css" href = "CSS/Map_Collection.css" >
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>

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
                        <a class="Collection_Button" href="./upload.php?col=<?php echo $collection; ?>" style="text-decoration: none; color: white; display: block">Upload Documents</a>
                    </td>
                </tr>
                <tr>
                    <td class="Collection_data">
                        <a class="Collection_Button" href="./list.php?col=<?php echo $collection; ?>&action=catalog" style="text-decoration: none; color: white; display: block">Catalog Document</a>
                    </td>
                </tr>
                <tr>
                    <td class="Collection_data">
                        <a class="Collection_Button" href="./list.php?col=<?php echo $collection; ?>&action=review" style="text-decoration: none; color: white; display: block">Edit/View Document</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>

<?php include '../../Master/footer.php'; ?>
</body>
<style type="text/css">
    .Error_Input{margin-left: 10%; margin-top: 0%; background-color: #f1f1f1; border-radius: 10px; border-width: 0px; box-shadow: 0px 0px 2px #0c0c0c; padding-left: 8%; margin-right: 10%; padding-bottom: 5%; padding-top: 2.5%;}
    nav{margin: -1px 0px 40px 15px !important;}
    #thetable_left{padding-top: 8px}
    #thetable td{padding-top: 11px; padding-left: 1px}
</style>
</html>

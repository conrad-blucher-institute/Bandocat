<?php
//for admin to view ticket and update ticket status
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['id'])) {
    $tID = $_GET['id']; //ticket ID
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
}
else header('Location: ../../');

$ticket = $DB->SP_ADMIN_TICKET_SELECT($tID);
//var_dump($ticket); //uncomment this to display the array
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ticket view</title>
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
            <h2>Ticket View</h2>
            <form id="frmTicket" name="frmTicket">
                <table class="Account_Table">
                    <tr>
                        <td>
                            <div id="Left_Display" style="text-align: left">
                                <h4 class="Account_Title">Collection Name:<label> <?php echo $ticket['Collection']?></></label></h4>
                                <h4 class="Account_Title">Library Index/Subject: <label><?php echo $ticket['Subject']?></label></h4>
                                <h4 class="Account_Title">Description: <label><?php echo $ticket['Description']?></label></h4>
                                <h4 class="Account_Title">Status:
                                    <label>
                                        <?php $stat = $ticket['Status'];
                                        if($stat == 1) {
                                            $status = "Done";
                                        }
                                        else $status = "Incomplete";
                                        echo $status?>
                                    </label>
                                </h4>
                                <h4 class="Account_Title">Submitter:<label><?php echo $ticket['Submitter']?></label></h4>
                                <h4 class="Account_Title">Notes:</h4><label><?php echo $ticket['Notes']?></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="Collection_data">
                        </td>
                    </tr>
                    <tr>
                        <td class="Collection_data">
                        </td>
                    </tr>
                    <tr>
                </table>
            </form>
            <?php include '../../Master/footer.php'; ?>
</body>
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
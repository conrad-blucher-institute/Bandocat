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

    <title>Account Settings</title>
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>

</head>
<body>
<style>
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
        font-size: 1.5em;
        text-align: center;
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
<table id = "thetable">
    <script type="text/javascript" src="PasswordMatch.js"></script>
    <tr>
        <td class="menu_left" id="thetable_left">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </td>
        <td class="Account" id="thetable_right">
            <h2>Account Settings</h2>
            <table class="Account_Table">
                <form id="frm_auth" name="frm_auth" method="post" action="Account_Processing.php">
                <tr>
                    <td>
                        <h4 class="Account_Title">Change Password</h4>
                    </td>
                </tr>
                <tr>
                    <td><label class="unselectable" for="password">Current Password:</label>
                    </td>
                    <td>
                        <input type="password" id="txtPassword" name="oldpassword" >
                    </td>
                </tr>
                <tr>
                    <td><label class="unselectable" for="pass1">New Password:</label></td>
                    <td>
                        <input type="password" name="pass1" id="pass1">
                    </td>

                </tr>
                <tr>
                    <td><label class="unselectable" for="pass2">Confirm Password:</label></td>
                    <td>
                        <input type="password" name="pass2" id="pass2" onkeyup="checkPass(); return false;">
                    </td>
                </tr>
                <td colspan="2">
                    <br>
                    <input type="submit" name = "login" id="btnSubmit" value="Update" class="bluebtn"/>
                </td>
                </form>

                <form id="frm_info" name="frm_info" method="post" action="dosomething.php">
                <tr>
                    <td>
                        <h4 class="Account_Title">User Information</h4>
                    </td>
                </tr>
                    <tr>
                        <td><label class="unselectable" for="Email">Change Email:</label></td>
                        <td>
                            <input type="text" id="Email" name="Email">
                        </td>
                    </tr>
                <tr>
                    <td><label class="unselectable" for="CheckEmail">Re-enter Email:</label></td>
                    <td>
                        <input type="text" id="CheckEmail" name="CheckEmail" onkeyup="checkEmail(); return false;">
                    </td>
                </tr>
                <tr>
                    <td><label class="unselectable" for="ChangeName"> Change Name:</label></td>
                    <td>
                        <input type="text" id="ChangeName" name="ChangeName">
                    </td>
                </tr>
                <td colspan="2">
                    <br>
                    <input type="submit" name = "login" id="btnSubmit" value="Update" class="bluebtn"/>
                </td>
                </form>

            </table>
        </td>
    </tr>

</table>

<?php include '../../Master/footer.php'; ?>

</body>
</html>

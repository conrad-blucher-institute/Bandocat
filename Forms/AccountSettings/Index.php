<?php
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


</head>
<body>
<style>
    #thetable_Account
    {
        width: 100%;
        vertical-align: top;
        border: 1px solid #f2f2f2;

    }
    #thetable_password
    {
        width: 60%;
        vertical-align: top;
        margin-left: 20px;
        background-color: #e6e6e6;
        border: 1px solid #a6a6a6;
        padding: 10px;
        font-family: verdana;
        font-size: 1em;
    }
    #thetable_buttons
    {
        width: 60%;
        vertical-align: top;
        margin-left: 20px;
        text-align: center;
    }
</style>
<table id="thetable_Account">
    <tr >
        <td class="menu_left" id="thetable_left">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </td>
        <td class="container" id="thetable_right">
            <h2>Account Settings</h2>
            <table width="100%">
            </table>
            <form id="frm_auth" name="frm_auth" method="post" action="Account_Processing.php">
                <div id="Change Password">
                    <table id = "thetable_password" style="text-align:left">
                        <script type="text/javascript" src="PasswordMatch.js">
                        </script>
                        <th>Change Password</th>
                        <tr>
                            <td><label class="unselectable" for="password">Current Password:</label>
                            </td>
                            <td>
                                <input type="password" id="txtPassword" name="oldpassword" required>
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
                    </table>
                    <table id="thetable_buttons">
                        <tr>
                            <td colspan="2">
                                <br>
                                <input type="submit" name = "login" id="btnSubmit" value="Update" class="bluebtn"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
            <form id="frm_info" name="frm_info" method="post" action="dosomething.php">
                <div id="Something">
                    <table id = "thetable_password" style="text-align:left" >
                        <script>

                        </script>
                        <th>User Information</th>
                        <tr>
                            <td><label class="unselectable" for="Email">Change Email:</label></td>
                            <td>
                                <input type="text" id="Email" name="ChangeEmail">
                            </td>
                        </tr>
                        <tr>
                            <td><label class="unselectable" for="ChangeName"> Change Name:</label></td>
                            <td>
                                <input type="text" id="ChangeName" name="ChangeName">
                            </td>
                        </tr>
                            <td colspan="2"><p id="Info_Error"></p></td>
                        </tr>
                    </table>
                    <table id="thetable_buttons">
                        <tr>
                            <td colspan="2">
                                <br>
                                <input type="submit" name = "login" id="btnSubmit" value="Save" class="bluebtn"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </td>
    </tr>
</table>

<?php include '../../Master/footer.php'; ?>
</body>
</html>

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
        width: 70%;
        vertical-align: top;

    }
    #thetable_password
    {
        width: 100%;
        vertical-align: top;
        margin-left: 20px;

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
        <table id = "thetable_password" style="text-align:center">
            <th>Change Password</th>
            <tr>
                <td><label class="unselectable" for="password">Current Password</label>
                </td>
                <td>
                    <input type="password" id="txtPassword" name="oldpassword" required>
                </td>
            </tr>
            <tr>
                <td><label class="unselectable" for="password">New Password</label></td>
                <td>
                    <input type="password" id="txtPassword" name="newpassword" required>

                </td>

            </tr>
            <tr>
                <td><label class="unselectable" for="password">Re-enter Password</label></td>
                <td>
                    <input type="password" id="txtPassword" name="validatenew" required>

                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <input type="submit" name = "login" id="btnSubmit" value="Update" class="bluebtn"/>
                </td>
            </tr>
            <tr>
                <td colspan="2"><p id="Info_Error"></p></td>
            </tr>
        </table>
    </div>
</form>
            <form id="frm_auth" name="frm_auth" method="post" action="dosomething.php">
                <div id="Something">
                    <table id = "thetable_password" style="text-align:center">
                        <th>User Information</th>
                        <tr>
                            <td><label class="unselectable" for="UserName">Change Username</label>
                            </td>
                            <td>
                                <input type="text" id="UserName" name="ChangeUser" >
                            </td>
                        </tr>
                        <tr>
                            <td><label class="unselectable" for="Email">Change Email</label></td>
                            <td>
                                <input type="text" id="Email" name="ChangeEmail">

                            </td>

                        </tr>
                        <tr>
                            <td><label class="unselectable" for="FullName"> Change Full Name</label></td>
                            <td>
                                <input type="text" id="FullName" name="ChangeName">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br>
                                <input type="submit" name = "login" id="btnSubmit" value="Save" class="bluebtn"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><p id="Info_Error"></p></td>
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

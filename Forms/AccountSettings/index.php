<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
require '../../Library/DBHelper.php';
$DB = new DBHelper();
$userinfo = $DB->GET_USER_INFO($session->getUserID());
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
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
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
    <script type="text/javascript" src="PasswordMatch.js"></script>
<div id="wrap">
    <div id="main">
        <div id="divleft">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php';?>
        </div>
    <div id="divright">
            <h2 id="page_title">Account Settings</h2>
        <div id="divscroller">
            <table class="Account_Table">
                <form id="frmChangePassword" name="frmChangePassword">
                <tr>
                    <td colspan="2" style="text-align: center">
                        <h4 class="Account_Title">Change Password</h4>
                    </td>
                </tr>
                <tr>
                    <td><label class="unselectable" for="txtOldPassword"><span class="required">*</span>Current Password:</label>
                    </td>
                    <td>
                        <input type="password" id="txtOldPassword" name="txtOldPassword" required >
                    </td>
                </tr>
                <tr>
                    <td><label class="unselectable" for="txtPassword"><span class="required">*</span>New Password:</label></td>
                    <td>
                        <input type="password" name="txtPassword" id="txtPassword" required>
                    </td>

                </tr>
                <tr>
                    <td><label class="unselectable" for="txtRepeatPassword"><span class="required">*</span>Confirm Password:</label></td>
                    <td>
                        <input type="password" required name="txtRepeatPassword" id="txtRepeatPassword" onkeyup="checkPass(); return false;">
                    </td>
                </tr>
                    <td colspan="2" style="text-align: center">
                    <br>
                    <input type="submit" name = "btnSubmitChangePassword" id="btnSubmitChangePassword" value="Update" class="bluebtn"/>
                </td>
                </form>
                <form id="frmUserInformation" name="frmUserInformation">
                <tr>
                    <td colspan="2" style="text-align: center">
                        <br><br><h4 class="Account_Title">User Information</h4>
                    </td>
                </tr>
                    <tr>
                        <td><label class="unselectable" for="txtEmail">Change Email:</label></td>
                        <td>
                            <input type="text" id="txtEmail" name="txtEmail" value="<?php echo $userinfo['email']; ?>">
                        </td>
                    </tr>
                <tr>
                    <td><label class="unselectable" for="txtName"> Change Name:</label></td>
                    <td>
                        <input type="text" id="txtName" name="txtName" value="<?php echo $userinfo['fullname']; ?>">
                    </td>
                </tr>
                <td colspan="2" style="text-align: center">
                    <br>
                    <input type="submit" name = "btnSubmitUserInformation" id="btnSubmitUserInformation" value="Update" class="bluebtn"/>
                </td>
                </form>
            </table>
            </div>
        </div>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>

</body>
</html>

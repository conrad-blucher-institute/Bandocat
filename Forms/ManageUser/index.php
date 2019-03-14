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
<!-- HTML HEADER -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Manage Users</title>

    <!-- Font Awesome CDN CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container pad-bottom">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">Manage Users</h1>
            <hr>

            <!-- card -->
            <div class="d-flex justify-content-center">
                <div class="card" style="width: 30em;">
                    <div class="card-header">
                        <h3 class="text-center">Select User</h3>
                    </div>
                    <!-- Body -->
                    <div class="card-body">
                        <!-- Form -->
                        <form id="frm_user" name="frm_user" method="post">
                            <!---------Select Option Fields starts here------>
                            <div class="form-group">
                                <select name="ddl_user" id="ddl_user" multiple class="form-control" style="height: 20em;">
                                    <?php $Render->GET_DDL_USER($DB->GET_ACTIVE_USER_TABLE(),null); ?>
                                </select>
                            </div>
                            <!-- Radio Buttons -->
                            <!-- Admin and Reader -->
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rd_Role" id="rd_Role_admin" value="2">
                                    <label class="form-check-label" for="rd_Role_admin">Admin</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rd_Role" id="rd_Role_reader" value="4"/>
                                    <label class="form-check-label" for="rd_Role_reader">Reader</label>
                                </div>
                            </div>
                            <!-- Admin and Reader -->
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rd_Role" id="rd_Role_ru" value="3">
                                    <label class="form-check-label" for="rd_Role_ru">Regular</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rd_Role" id="rd_Role_inactive" value="0"/>
                                    <label class="form-check-label" for="rd_Role_inactive">Inactive</label>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <input type="submit" value="Update Role" id="btnUpdateRole" class="btn btn-primary" name="btnUpdateRole"/>
                            <input type="submit" value="Reset Password" id="btnResetPwd" class="btn btn-primary" name="btnResetPwd"/>
                        </form>
                        <div class="form-group pt-3" id="txtPrompt" style="display: none;">
                            <p>User's temporary password is <span id="txtNewPassword" style="font-weight: bold;"></span>. Please notify the user to change the password once they're logged in.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- col -->
    </div> <!-- row -->
</div><!-- Container -->
<?php include "../../Master/bandocat_footer.php" ?>


<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- JQuery UI cdn -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
    $(document).ready(function() {

        var docHeight = $(window).height() - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });

    $(window).resize(function() {
        var docHeight = $(window).height() - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });
</script>
<!-- Page Level Plugin -->
<script>

    $("#btnUpdateRole").click(function(event){
        $("#txtPrompt").hide();
        if($("#ddl_user :selected").text() == "Select")
            return false;
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "./updaterole_processing.php",
            data: $("#frm_user").serializeArray(),
            success: function (data) {
                alert($("#ddl_user :selected").text() + "'s role has been updated!");
                location.reload();
            }
        });
    });

    $("#btnResetPwd").click(function(event){
        $("#txtPrompt").hide();
        event.preventDefault();
        if($("#ddl_user :selected").text() == "Select")
        {
            alert("Password resetting has failed.");
            return false;
        }

        $.ajax({
            type: 'post',
            url: "resetpassword_processing.php",
            data: $("#frm_user").serializeArray(),
            success: function (data) {
                $("#txtNewPassword").text(JSON.parse(data));
                $("#txtPrompt").show();
            }
        });
    });

    $("#ddl_user").change(function(event){
        $("#txtPrompt").hide();
        event.preventDefault();
        $.ajax({
            type: 'post',
            url: "getrole_processing.php?user=" + $("#ddl_user :selected").val() ,
            data: {userID: $("#ddl_user :selected").val() },
            success:function (data) {
                var ret = data;
                $("#btnUpdateRole").prop("disabled", false);
                switch (ret) {
                    case "Admin":
                        $("#rd_Role_admin").prop("checked", true);
                        break;
                        5
                    case "Writer":
                        $("#rd_Role_ru").prop("checked", true);
                        break;
                    case "Reader":
                        $("#rd_Role_reader").prop("checked", true);
                        break;
//                case "Inactive":
//                    $("#rd_Role_inactive").prop("checked",true);
//                    break;
                    case "Super Admin":
                        $("#rd_Role_admin").prop("checked", true);
                        break;
                    default:
                        break;

                }
            }
        });
    });
</script>
</body>
</html>
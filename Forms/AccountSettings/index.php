<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
require '../../Library/DBHelper.php';
$DB = new DBHelper();
$userinfo = $DB->GET_USER_INFO($session->getUserID());
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

    <!-- Font Awesome CDN CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <title>Account Settings</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">Account Settings</h1>
            <hr>

            <div class="d-flex justify-content-center">
                <div class="card" style="width: 35em;">
                    <div class="card-body">
                        <form id="frmChangePassword" name="frmChangePassword" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <h5 class="text-center pl-3 pr-3 pb-3">Change Password</h5>
                            <!-- Current Password -->
                            <div class="form-group row">
                                <label for="txtOldPassword" class="col-sm-4 col-form-label">Current Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="txtOldPassword" name="txtOldPassword" placeholder="Current Password" required>
                                </div>
                            </div>
                            <!-- New Password -->
                            <div class="form-group row">
                                <label for="txtPassword" class="col-sm-4 col-form-label">New Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="New Password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col" id="passwordGroup">
                                    <div class="valid-feedback" id="match">
                                        Looks good!
                                    </div>
                                    <!-- Invalids -->
                                    <div class="invalid-feedback" id="lowercase">
                                        A lower case letter
                                    </div>
                                    <div class="invalid-feedback" id="uppercase">
                                        A capital letter
                                    </div>
                                    <div class="invalid-feedback" id="digit">
                                        A digit
                                    </div>
                                    <div class="invalid-feedback" id="special">
                                        A special character
                                    </div>
                                    <div class="invalid-feedback" id="size">
                                        8 or more characters, max 32
                                    </div>
                                </div>
                            </div>
                            <!-- Old Password -->
                            <div class="form-group row">
                                <label for="txtRepeatPassword" class="col-sm-4 col-form-label">Confirm Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="txtRepeatPassword" name="txtRepeatPassword" placeholder="Confirm Password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col" id="retypeGroup">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback" id="invalidRetype">
                                        These passwords do not match.
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <input type="submit" name = "btnSubmitChangePassword" id="btnSubmitChangePassword" value="Update" class="btn btn-primary"/>
                            </div>
                        </form>
                        <!-- Other Form -->
                        <form id="frmUserInformation" name="frmUserInformation" method="post" enctype="multipart/form-data">
                            <h5 class="pl-3 pr-3 pb-3 pt-5 text-center">User Information</h5>
                            <!-- Email -->
                            <div class="form-group row">
                                <label for="txtEmail" class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="txtEmail" name="txtEmail" value="<?php echo $userinfo['email']; ?>" required>
                                </div>
                            </div>
                            <!-- Name -->
                            <div class="form-group row">
                                <label for="txtName" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="txtName" name="txtName" value="<?php echo $userinfo['fullname']; ?>">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <input type="submit" name = "btnSubmitUserInformation" id="btnSubmitUserInformation" value="Update" class="btn btn-primary"/>
                            </div>
                        </form>
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
        console.log(docHeight);
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });

    $( window ).resize(function() {
        var docHeight = $(window).height() - $('#megaMenu').height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
        {
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
        }
    });
</script>
<!-- Page level plugin -->
<script>
    //update user Info
    $("#frmUserInformation").submit(function(event){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "index_processing.php?action=updateUserInfo",
            data: {txtEmail: $("#txtEmail").val(),txtName: $("#txtName").val()},
            success: function (data) {
                alert(data);
            }
        });
    });

    //update password
    $("#frmChangePassword").submit(function(event){
        event.preventDefault();
        if(checkPassword() === false)
        {
            alert("The confirmed password does not match the new password.");
        }

        else if(passwordPattern() === false)
        {
            alert("The new password you have typed does not meet our password requirements.");
        }

        else
        {
            $.ajax({
                type: "POST",
                url: "index_processing.php?action=updatePassword",
                data: {txtOldPassword: $("#txtOldPassword").val(),txtPassword: $("#txtPassword").val()},
                success: function (data) {
                    switch(data)
                    {
                        case "1":
                            alert("Success!");
                            $('#frmChangePassword').trigger("reset");
                            $('#txtPassword').removeClass('is-valid');
                            $('#txtRepeatPassword').removeClass('is-valid');
                            break;
                        case "0":
                            alert("Fail to update password.\nPlease make sure your old password is correct.");
                            break;
                        default:
                            alert("Fail to connect to database!\n Please contact administrator");
                            break;
                    }
                }
            });
        }
    });

    $('#txtPassword').keyup(function(e) {
        var good = 0;

        if(hasLowerCase() === false)
        {
            $('#lowercase').removeClass("valid-feedback");
            $('#lowercase').addClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
        }

        else
        {
            $('#lowercase').removeClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
            $('#lowercase').addClass("valid-feedback");
            $('#passwordGroup').addClass("has-success has-feedback");
            good++;
        }

        if(hasUpperCase() === false)
        {
            $('#uppercase').removeClass("valid-feedback");
            $('#uppercase').addClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
        }

        else
        {
            $('#uppercase').removeClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
            $('#uppercase').addClass("valid-feedback");
            $('#passwordGroup').addClass("has-success has-feedback");
            good++;
        }

        if(hasDigit() === false)
        {
            $('#digit').removeClass("valid-feedback");
            $('#digit').addClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
        }

        else
        {
            $('#digit').removeClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
            $('#digit').addClass("valid-feedback");
            $('#passwordGroup').addClass("has-success has-feedback");
            good++;
        }

        if(hasSpecialCharacter() === false)
        {
            $('#special').show();
            $('#special').removeClass("valid-feedback");
            $('#special').addClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
        }

        else
        {
            $('#special').removeClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
            $('#special').addClass("valid-feedback");
            $('#passwordGroup').addClass("has-success has-feedback");
            good++;
        }

        if(hasSize() === false)
        {
            $('#size').removeClass("valid-feedback");
            $('#size').addClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
        }

        else
        {
            $('#size').removeClass("invalid-feedback");
            $('#passwordGroup').removeClass("has-error has-feedback");
            $('#size').addClass("valid-feedback");
            $('#passwordGroup').addClass("has-success has-feedback");
            good++;
        }

        // Checking to make sure all cases have been met
        if(good === 5)
        {
            $('#lowercase').hide();
            $('#uppercase').hide();
            $('#digit').hide();
            $('#special').hide();
            $('#size').hide();

            $('#txtPassword').removeClass('is-invalid');
            $('#txtPassword').addClass("is-valid");
        }

        else
        {
            $('#lowercase').show();
            $('#uppercase').show();
            $('#digit').show();
            $('#special').show();
            $('#size').show();

            $('#txtPassword').removeClass('is-valid');
            $('#txtPassword').addClass("is-invalid");
        }
    });

    $('#txtRepeatPassword').keyup(function(e) {
        if(checkPassword() === false)
        {
            $('#invalidRetype').show();
            $('#retypeGroup').addClass("has-error has-feedback");
            $('#txtRepeatPassword').removeClass("is-valid");
            $('#txtRepeatPassword').addClass("is-invalid");
        }
        else
        {
            $('#invalidRetype').hide();
            $('#retypeGroup').addClass("has-success has-feedback");
            $('#txtRepeatPassword').removeClass("is-invalid");
            $('#txtRepeatPassword').addClass("is-valid");
        }
    });

    function checkPassword()
    {
        return $('#txtPassword').val() === $('#txtRepeatPassword').val();
    }

    function passwordPattern()
    {
        // Password must be:
        // one lowercase character
        // one uppercase character
        // one special character
        // no whitespaces
        // one number
        // https://search.proquest.com/docview/1863277285?pq-origsite=summon The link here discusses password strengths and why we use it
        var regex = new RegExp(/^(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,32}$/);
        var password = $("#txtPassword").val();

        // Make sure you call check password before calling this function
        return regex.test(password);
    }

    function hasLowerCase()
    {
        var password = $("#txtPassword").val();
        var regex = new RegExp(/[a-z]/);

        return regex.test(password);
    }

    function hasUpperCase()
    {
        var password = $("#txtPassword").val();
        var regex = new RegExp(/[A-Z]/);

        return regex.test(password);
    }

    function hasDigit()
    {
        var password = $("#txtPassword").val();
        var regex = new RegExp(/[0-9]/);

        return regex.test(password);
    }

    function hasSpecialCharacter()
    {
        var password = $("#txtPassword").val();
        var regex = new RegExp(/\W/);

        return regex.test(password);
    }

    function hasSize()
    {
        var password = $("#txtPassword").val();
        var regex = new RegExp(/(?!.*\s).{8,32}$/);

        return regex.test(password);
    }
</script>
</body>
</html>
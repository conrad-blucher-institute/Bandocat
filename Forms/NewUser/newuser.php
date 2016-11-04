<?php include '../../Library/DBHelper.php';
require '../../Library/ControlsRender.php';
$UserDB = new DBHelper();
$Render = new ControlsRender();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>New User</title>
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.js"></script>
</head>

<body>
<form id="userForm">
    Full name (optional)
    <input type="text" class="user"/></br>
    Username
    <input type="text" class="user" required/></br>
    Password
    <input type="password" class="user" required/></br>
    Repeat Password
    <input type="password" class="user" required/></br>
    Email (optional)
    <input type="text" class="user"/></br>

    User Permission
    <select id="permissionSelect" oninput="dropdownPermission()" required>
        <?php
        $userArray = $UserDB->GET_USER_ROLE_FOR_DROPDOWN();
        unset($userArray[1]);

        $Render->GET_DDL_ROLE($userArray, $userArray)
        ?>
    </select></br>
    <div>
        <!--Inactive-->
        <p class="1" style="display: none"><?php echo ($UserDB->GET_USER_ROLE_FOR_DROPDOWN()[0]["description"]); ?></p>
        <!--Super Admin-->
        <p class="0" style="display: none"><?php echo ($UserDB->GET_USER_ROLE_FOR_DROPDOWN()[1]["description"]); ?></p>
        <!--Admin-->
        <p class="2" style="display: none"><?php echo ($UserDB->GET_USER_ROLE_FOR_DROPDOWN()[2]["description"]); ?></p>
        <!--Writer-->
        <p class="3" style="display: none"><?php echo ($UserDB->GET_USER_ROLE_FOR_DROPDOWN()[3]["description"]); ?></p>
        <!--Reader-->
        <p class="4" style="display: none"><?php echo ($UserDB->GET_USER_ROLE_FOR_DROPDOWN()[4]["description"]); ?></p>
    </div>
    <input type="button" value="register" onclick="test()"/>
</form></br>

<script>
var User = document.getElementById("userForm");
var userInput = [];
var userPermission = [];

function dropdownPermission() {
    var selectedIndex = document.getElementById("permissionSelect").options.selectedIndex;
    var selectedLength = document.getElementById("permissionSelect").options.length;
    for(i=0; i < selectedLength; i++){
        if( $("." + i.toString()).is(":visible")){
            $("." + i.toString()).css("display", "none");
        }
    }

    if (selectedIndex > 0){
        $("." + selectedIndex.toString()).css("display", "block")
    }
}

function test(){
//$(document).ready(function () {
    //$("form").submit(function(){
        userInput = [];
        for(i = 0; i < User.length-1; i++){
            userInput.push(User.elements[i].value);
            console.log(User.elements[i].value);
        }
        if (User.elements[2].value == User.elements[3].value) {
            userInput.splice(2, 2, User.elements[2].value);
            var userJSON = JSON.stringify(userInput);
            var user = JSON.parse(userJSON);
            console.log(user[1]);
        }

        else {
            alert("Password check doesn't match");
            userInput = [];
            if (!validData())
                return false;
        }
        event.disabled;

       event.preventDefault();
        $.ajax({
            type: "POST",
            url: "newuser_processing.php",
            data: {data: user},
            success: function (data) {
                console.log(data);
                if(data == "true"){
                    console.log(data);
                    alert("New user created successfully!")
                }
                //data contains the response from the php file.
                //u can pass it here to the javascript function
            }
        });

    }

</script>
</body>

</html>
<?php include '../../Library/DBHelper.php';
$UserDB = new DBHelper();
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
    Full name
    <input type="text" class="user"></br>
    Username
    <input type="text" class="user"></br>
    Password
    <input type="password" class="user"></br>
    Password Repeat
    <input type="password" class="user"></br>
    Email
    <input type="text" class="user"></br>
</form>

<form id="permission">
    Write Permission</br>
    <select onclick="permissionFunction()">
        <option>Write</option>
        <option>Read</option>
    </select>
</form>
<div id="permissionDescription" style="display: none;"><p>write</p></div>
<input type="button" onclick="userthing()" value="register">

<script>
var User = document.getElementById("userForm");
var Permission = document.getElementById("permission");
var userInput = [];
var userPermission = [];

var permission = $('#mySelectBox option').filter(':selected').text();

//function permissionFunction() {
  //  $("#permissionDescription").css("display", "block")
//}

function userthing() {
    for (i = 0; i < User.elements.length; i++) {
        userInput.push(User.elements[i].value);

    }

    for (i = 0; i < Permission.elements.length; i++) {
        userPermission.push(Permission.elements[i].checked);
    }

    if (User.elements[2].value == User.elements[3].value) {
        userInput.splice(2, 2, User.elements[2].value);
        userInput.push(userPermission[0], userPermission[1]);
        var userJSON = JSON.stringify(userInput);
        var user = JSON.parse(userJSON);
        console.log(user);
    }

    else {
        alert("Password check doesn't match");
        userInput = [];
    }

    $.ajax({
        type: "POST",
        url: "newuser_processing.php",
        data: {data: user},
        success: function (data) {
            console.log(data)
            //data contains the response from the php file.
            //u can pass it here to the javascript function
        }
    });
}
</script>
</body>

</html>
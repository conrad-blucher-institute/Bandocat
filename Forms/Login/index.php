<!--This page allows users to login-->
<!--If login successfully, login form will disappear, user can see Main Menu-->
<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Welcome to BandoCat!</title>

    <link rel = "stylesheet" type = "text/css" href = "Master/master.css" >
    <script type="text/javascript" src="ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>

</head>
<body>
<?php include 'Master/header.php'; ?>
<div>
    <form id="frm_auth" name="frm_auth" method="post" action="Controllers/Auth.php">
        <label for="username">Username</label>
        <input type="text" id="txtUsername" name="name" required>
        <label for="password">Password</label>
        <input type="password" id="txtPassword" name="password" required>
        <input type="submit" name = "login" id="btnSubmit" value="Login">
    </form>
</div>
</body>
</html>
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
<div id="home-nav">
    <ul>
        <li><a href="../MapsDB/">Blucher Maps</a></li>
        <li><a href="../GreenCollection">Green Maps</a></li>
        <li><a href="../JobFolder">Job Folder</a></li>
        <li><a href="../FieldBook">FieldBook</a></li>
        <li><a href="Queries/">Queries</a></li>
        <li><a href="admin/stats.php">Statistics</a></li>
    </ul>
</div>


</body>

</html>

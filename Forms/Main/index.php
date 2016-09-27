<?php
    include '../../Library/SessionManager.php';
    $session = new SessionManager();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Welcome to BandoCat!</title>

    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="Greetings.js"></script>
</head>
<body>


<table class="tg">
    <tr>
        <th class="tg-yw4l">
            <?php include '../../Master/header.php'?>
        </th>
        <th class="tg-chpy" colspan="2">
            Welcome <span id= "lblUsername" class="Username"><?php echo $session->getUserName(); ?> </span><span id="Time_Day"></span><span id="Greetings"></span>
        </th>
    </tr>
    <tr style="height: 630px">
        <td class="tg-yw4l">
            <?php include '../../Master/sidemenu.php' ?>
        </td>
        <td class="tg-zhyu"><h2>BandoCat</h2></td>
        <td class="tg-0za1"><h2>Announcements</h2></td>
    </tr>
</table>

<?php include '../../Master/footer.php'; ?>
</body>

<script>
        var d = new Date();
        var n = d.getHours();
        if(n >= 12)
        {
            document.getElementById("Time_Day").innerHTML = "Good Afternoon! ";
        }

        else{
            document.getElementById("Time_Day").innerHTML= "Good Morning! ";
        }

        var Random = Math.random();
        var Generic_Number = Random * (Greetings.length);
        var Integer = parseInt(Generic_Number);
        console.log(Random);
        console.log(Greetings.length);
        document.getElementById("Greetings").innerHTML = Greetings[Integer];

</script>

<style>

    #lblUsername{
        text-decoration: underline;
    }

    .tg-0za1:hover{
        opacity: 0.95;
        box-shadow: none;
        outline: solid;
        outline-width: 1.0px;
        outline-color: #A8A8A8;
    }
    .tg-zhyu:hover{
        opacity: 0.95;
        box-shadow: none;
        outline: solid;
        outline-width: 1.0px;
        outline-color: #A8A8A8;
    }
    h2{
        font-size: 15px !important;
        font-family: sans-serif;
    }
    nav{margin: 15px 0px 40px 15px !important;}
    .tg  {border-collapse:collapse;border-spacing:0; width: 100%}
    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;overflow:hidden;word-break:normal; border-style: groove}
    .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;}
    .tg .tg-chpy{font-size:20px;font-family:serif !important;;text-align:right;vertical-align:top; width: 77%; background-color: #f1f1f1; border-radius: 2px;  box-shadow: 0px 0px 3px #0c0c0c;}
    .tg .tg-0za1{font-size:13px;font-family:serif !important;;vertical-align:top; background-color: #f1f1f1; border-radius: 2px; border-width: 0px; box-shadow: 0px 0px 2px #0c0c0c;}
    .tg .tg-yw4l{vertical-align:top; border-style: none}
    .tg .tg-zhyu{font-size:13px;font-family:serif !important;;vertical-align:top; background-color: #f1f1f1; border-radius: 2px; border-width: 0px; box-shadow: 0px 0px 2px #0c0c0c; width: 55%}
</style>

</html>





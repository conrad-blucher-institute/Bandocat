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
<table id="thetable">
    <tr>
        <td class="menu_left" id="thetable_left">
            <?php include '../../Master/header.php';?>
        </td>
    </tr>
    <tr>
        <td>
            <?php include '../../Master/sidemenu.php';?>
        </td>
        <td>
            <table id="thetable_right">
                <tr>
                    <td>
                        <!--New user input form-->
                        <form id="userForm">
                            <h2>New User</h2>
                            <!--Block of new user input types-->
                            <table id="innerRightTable">
                                <tr>
                                    <td>
                                        <label>
                                            Full name:
                                        </label>
                                    </td>
                                    <td>
                                        <input name="" type="text" class="user"/></br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            Username:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" class="user" required/></br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            Password:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="password" class="user" required/></br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            Repeat Password:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="password" class="user" required/></br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            Email:
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" class="user"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            User Permission:
                                        </label>
                                    </td>
                                    <td style="text-align: center">
                                        <select id="permissionSelect" oninput="dropdownPermission()" required>
                                            <?php
                                            $userArray = $UserDB->GET_USER_ROLE_FOR_DROPDOWN();
                                            unset($userArray[1]);
                                            $Render->GET_DDL_ROLE($userArray, $userArray)
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center">
                                        <!--Submit form-->
                                        <input type="submit" value="Register" class="bluebtn"/>
                                    </td>
                                </tr>
                            </table>

                            </br>

                            <!--User permission description-->
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



                        </form>
                        </br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!--End of new user input form-->

<?php include '../../Master/footer.php'; ?>
</body>


<script>
//Global variables
var User = document.getElementById("userForm");
var userInput = [];

//Function that is used to display the permission description when the drop-down receives an input//
function dropdownPermission() {
    var selectedIndex = document.getElementById("permissionSelect").options.selectedIndex;
    var selectedLength = document.getElementById("permissionSelect").options.length;

//For loop that makes the style of all properties in the form element display none//
    for(i=0; i < selectedLength; i++){
        if( $("." + i.toString()).is(":visible")){
            $("." + i.toString()).css("display", "none");
        }
    }

//Conditional statement that displays only the selected information linked to the permission drop-down//
    if (selectedIndex > 0){
        $("." + selectedIndex.toString()).css("display", "block")
    }
}

/*Submit event that obtains teh information from the user form and calls the newuser_processing.php page, which links to
a procedure in the database that insert the information into the bandocatdb database in the user table .*/
    $(document).ready(function () {
        $("#userForm").submit(function (e) {
            userInput = [];
            e.preventDefault();
            for (i = 0; i < User.length - 1; i++) {
                userInput.push(User.elements[i].value);
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
                return false;
            }

            $.ajax({
                type: "POST",
                url: "newuser_processing.php",
                data: {data: user},
                success: function (data) {
                    console.log(data);
                    if (data == "NEW") {
                        console.log(data);
                        alert("New user created successfully!");
                        window.location.href = "../NewUser/NewUser.php";
                    }
                    else {
                        alert("New user created unsuccessfully, user name already exists.");
                    }
                }
            });
        });
    });
</script>

<style>
    #thetable_right{
        width:65%;
        padding: 2%;
        margin-left: 15%;
        border-radius: 10px;
        box-shadow: 0px 0px 2px;
    }
    form{
        text-align: right;
    }

    label{
        display: inline-table;
        position: relative;
        font-size: 1.12em;
    }

    p{
        background: #0067C5;
        color:white;
        /*font-family:"DigifaceWide";*/
        text-align: center;
        font-weight:bold;
        margin: 10px;
        font-size: 1em;
        padding: 1%;
    }

    #innerRightTable{
        margin-left: 25%;
    }

</style>


</html>
/**
 * Created by User on 9/30/2016.
 */
function checkPass()
{
    var pass1 = document.getElementById('pass1');
    var pass2 = document.getElementById('pass2');
    var message = document.getElementById('confirmMessage');
    var goodColor = "#66cc66";
    var badColor = "red";

    if(pass1.value == pass2.value){
        pass2.style.backgroundColor = goodColor;
    }else{
        pass2.style.backgroundColor = badColor;
    }
}
/**
 * Created by John Lister on 9/30/2016.
 */
function checkPass()
{
    var pass1 = document.getElementById('txtPassword');
    var pass2 = document.getElementById('txtRepeatPassword');
    var goodColor = "#66cc66";
    var badColor = "red";

    if(pass1.value == pass2.value){
        pass2.style.backgroundColor = goodColor;
    }else{
        pass2.style.backgroundColor = badColor;
    }
}
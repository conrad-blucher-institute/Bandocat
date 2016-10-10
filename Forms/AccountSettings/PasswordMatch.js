/**
 * Created by John Lister on 9/30/2016.
 */
function checkPass()
{
    var pass1 = document.getElementById('pass1');
    var pass2 = document.getElementById('pass2');
    var goodColor = "#66cc66";
    var badColor = "red";

    if(pass1.value == pass2.value){
        pass2.style.backgroundColor = goodColor;
    }else{
        pass2.style.backgroundColor = badColor;
    }
}
function checkEmail()
{
    var em1 = document.getElementById('Email');
    var em2 = document.getElementById('CheckEmail');
    var goodColor = "#66cc66";
    var badColor = "red";

    if(em1.value == em2.value){
        em2.style.backgroundColor = goodColor;
    }else{
        em2.style.backgroundColor = badColor;
    }
}
/**
 * Created by snguyen1 on 1/18/2017.
 */
//Description: Javascript functions and jQuery Events function to handle add-ons or additional features

//Include this into html <head> tag on your web page:
/* <script type="text/javascript" src="../../Master/master.js"></script> */


/*
jQuery Events for Document History add-ons in review.php
*Note: Include this into html <head> tag on your web page:
 <link rel="stylesheet" type="text/css" href="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.css">
 <script type="text/javascript" src="../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.js"></script>
*/
//**********************************************************************/
//jQuery that allows the visibility of the draggable element if checked
$(document).ready(function() {
    //jQuery function that drags the draggable element
    $(function () {
        $("#documentHistory").draggable({helper: 'clone'});
    });
});

//*********************************************************************/

//*********************************************************************
// Takes in a string and check whether the string contains an underscore
// return true if it does
// return false if it does not
function validateFormUnderscore(str)
{

        if(document.getElementById(str).value.includes("_") == true)
        {
            return true;
        }
        else
        {
            return false;
        }

}
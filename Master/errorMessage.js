/********************************************************************
* Function is used to display bootstrap dismissible error messages.
* Error message can be changed by calling the function and simply
* passing the "id" of the div you want it to be appended to, the
* "message" to be displayed, and the "style" of your desired error
* message(style changes the color of the error message).
********************************************************************/
function errorReport(id, message, style)
{
    var html = '<div class="alert alert-' + style + ' alert-dismissible" >\n' +
        '                                                <button  type="button" class="close" data-dismiss="alert" aria-hidden="true">\n' +
        '                                                    &times;\n' +
        '                                                </button>\n' +
        '                                                '+ message +
        '                                            </div>';

    $("#" + id).append(html);
}
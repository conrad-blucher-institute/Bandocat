/***************************************************************************************
 Function: errorReport
 Description: Easily accessible dismissible error message template
 Parameter(s): id(div id the message will append to), message(message to be displayed),
               style(Success=green,Info=blue,Warning=yellow,Danger=red)
 Return value(s): void (appends dismissible error message wherever needed)
 **************************************************************************************/
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
function errorReport(id, message, style)
{
    var display = '<div class="alert alert-' + style + ' alert-dismissible" >\n' +
        '                                                <button  type="button" class="close" data-dismiss="alert" aria-hidden="true">\n' +
        '                                                    &times;\n' +
        '                                                </button>\n' +
        '                                                '+ message +
        '                                            </div>';

    $("#" + id).append(display);
}
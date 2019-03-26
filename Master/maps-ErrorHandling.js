/********************************************************************
* Function is used for error checking and handling of all map
* catalog pages. Takes in serialized array from given form and
* checks values.
********************************************************************/
function handleError(data)
{
    // Checking to see if form provided has values we want to check for errors **************************
    // Checking to see if form provided has txtLibraryIndex
    if(data.filter(data => (data.name === 'txtLibraryIndex')).length > 0)
    {
        var libIndexObj = data.filter(data => (data.name === 'txtLibraryIndex'))
        var libIndexValue = libIndexObj[0].value;
    }
    // Checking to see if form provided has txtTitle
    if(data.filter(data => (data.name === 'txtTitle')).length > 0)
    {
        var docTitleObj = data.filter(data => (data.name === 'txtTitle'))
        var docTitleValue = docTitleObj[0].value;
    }
    // Checking to see if form provided has fileUpload
    if(data.filter(data => (data.name === 'fileUpload')).length > 0)
    {
        var fileUploadObj = data.filter(data => (data.name === 'fileUpload'))
        var fileUploadValue = fileUploadObj[0].value;
    }
    // Checking to see if form provided has fileUploadBack
    if(data.filter(data => (data.name === 'fileUploadBack')).length > 0)
    {
        var fileUploadBackObj = data.filter(data => (data.name === 'fileUploadBack'))
        var fileUploadBackValue = fileUploadBackObj[0].value;
    }
    // Checking to see if form provided has ddlMedium
    if(data.filter(data => (data.name === 'ddlMedium')).length > 0)
    {
        var docMediumObj = data.filter(data => (data.name === 'ddlMedium'))
        var docMediumValue = docMediumObj[0].value;
    }

    // Displaying dismissible error messages if needed **************************************************

    // Variables that contain words or symbols that have to be checked in user input
    var dashUnderScoreCheck = /-_/g;
    var backCheck = /back/g;

    if(libIndexValue == "") // Library Index
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("libraryIndex", message, "danger");
    }
    else
    {
            if(dashUnderScoreCheck.test(libIndexValue) == false)
        {
            var message = '<strong>ERROR:</strong> File doesn\'t have -_ pattern\n'
            errorReport("libraryIndex", message, "danger");
        }
    }

    if(docTitleValue == "") // Document Title
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("docTitle", message, "danger");
    }

    if(fileUploadValue == "") // Front Scan
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("frontScan", message, "danger");
    }
    else
    {
        if(backCheck.test(fileUploadValue))
        {
            var message = '<strong>ERROR:</strong> Wrong File. Possibly Back Scan?\n'
            errorReport("frontScan", message, "danger");
        }
    }

    if(fileUploadBackValue == "") // Back Scan
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("backScan", message, "danger");
    }
    else
    {
        if(dashUnderScoreCheck.test(fileUploadBackValue) == false)
        {
            var message = '<strong>ERROR:</strong> File doesn\'t have -_ pattern\n'
            errorReport("backScan", message, "danger");
        }
        if(backCheck.test(fileUploadBackValue) == false)
        {
            var message = '<strong>ERROR:</strong> File doesn\'t contain back\n'
            errorReport("backScan", message, "danger");
        }
    }

    if(docMediumValue == "") // Document Medium
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("docMedium", message, "danger");
    }
}
/********************************************************************
* Function is used for error checking and handling of all map
* catalog pages. Takes in serialized array from given form and
* checks values.
********************************************************************/
function handleError(data)
{
    ///////////// Checking to see if form provided has values we want to check for errors ////////////////
    /*************************************** Library Index ***************************************/
    if(data.filter(data => (data.name === 'txtLibraryIndex')).length > 0)
    {
        var libIndexObj = data.filter(data => (data.name === 'txtLibraryIndex'))
        var libIndexValue = libIndexObj[0].value;
    }
    /*************************************** Doc Title ***************************************/
    if(data.filter(data => (data.name === 'txtTitle')).length > 0)
    {
        var docTitleObj = data.filter(data => (data.name === 'txtTitle'))
        var docTitleValue = docTitleObj[0].value;
    }
    /*************************************** Front Scan ***************************************/
    if(data.filter(data => (data.name === 'fileUpload')).length > 0)
    {
        var fileUploadObj = data.filter(data => (data.name === 'fileUpload'))
        var fileUploadValue = fileUploadObj[0].value;
    }
    /*************************************** Back Scan ***************************************/
    if(data.filter(data => (data.name === 'fileUploadBack')).length > 0)
    {
        var fileUploadBackObj = data.filter(data => (data.name === 'fileUploadBack'))
        var fileUploadBackValue = fileUploadBackObj[0].value;
    }
    /*************************************** Doc Medium ***************************************/
    if(data.filter(data => (data.name === 'ddlMedium')).length > 0)
    {
        var docMediumObj = data.filter(data => (data.name === 'ddlMedium'))
        var docMediumValue = docMediumObj[0].value;
    }
    ///////////////////////////////// Value Checking Ends Here //////////////////////////////////////////

    ////////////////////// Displaying dismissible error messages if needed //////////////////////////////

    // Variables contain words or symbols that have to be checked in user input
    var dashUnderScoreCheck = /-_/g;
    var backCheck = /back/g;

    /*************************************** Library Index ***************************************/
    if(libIndexValue == "") // if value is empty
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("libraryIndex", message, "danger");
    }
    else if(dashUnderScoreCheck.test(libIndexValue) == false) // if value doesn't contain -_ pattern
    {
        var message = '<strong>ERROR:</strong> File doesn\'t have -_ pattern\n'
        errorReport("libraryIndex", message, "danger");
    }

    /*************************************** Doc Title ***************************************/
    if(docTitleValue == "") // if value is empty
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("docTitle", message, "danger");
    }

    /*************************************** Front Scan ***************************************/
    if(fileUploadValue == "") // if value is empty
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("frontScan", message, "danger");
    }
    else if(backCheck.test(fileUploadValue)) // if value does have "back"
    {
        var message = '<strong>ERROR:</strong> Wrong File. Possibly Back Scan?\n'
        errorReport("frontScan", message, "danger");
    }

    /*************************************** Back Scan ***************************************/
    // Back scan has to check for "back" and "-_" so I set them to variables here for easier
    // testing with if statements.
    var checkDashUnder = dashUnderScoreCheck.test(fileUploadBackValue);
    var checkBack = backCheck.test(fileUploadBackValue);

    if(fileUploadBackValue == "") // if value is empty
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("backScan", message, "danger");
    }
    else if(checkDashUnder == false && checkBack == false) // if value doesn't have either
    {
        var message = '<strong>ERROR:</strong> File doesn\'t have -_ pattern or contain back\n'
        errorReport("backScan", message, "danger");
    }
    else
    {
        if(checkDashUnder == false) // if value doesn't contain -_ pattern
        {
            var message = '<strong>ERROR:</strong> File doesn\'t have -_ pattern\n'
            errorReport("backScan", message, "danger");
        }
        if(checkBack == false) // if value doesn't contain "back"
        {
            var message = '<strong>ERROR:</strong> File doesn\'t contain back\n'
            errorReport("backScan", message, "danger");
        }
    }

    /*************************************** Doc Medium ***************************************/
    if(docMediumValue == "") // if value is empty
    {
        var message = '<strong>ERROR:</strong> Required text field\n'
        errorReport("docMedium", message, "danger");
    }
    ///////////////////////////////// Error Displaying Ends Here ///////////////////////////////////////
}
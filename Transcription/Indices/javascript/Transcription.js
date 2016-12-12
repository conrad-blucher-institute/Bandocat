//Global Variables
var rectangleSelected = false;			//boolean keeping track of if the user has an active rectangle placed on the viewer 
var rectangleArray = new Array();		// array containing all the rectangles drawn by drawRectangles funciton

//This function sets path of image as local storage variable and opens Input_Transcription page.
function newEntry()
{
	localStorage.setItem('fileName', fileName);
	window.open("Transcription.php");
	window.close();
}

//This function calls drawRectanglesLeaflet.php and then calls drawRectangles() with JSON returned by drawRectanglesLeaflet.php as parameter.
function getRectangleCoords()
{
	var collection = document.getElementById('Collection').value;
	var fileName = window.localStorage.getItem('fileName');
	var docID = window.localStorage.getItem('docID');
	var rectangleCoords;
	
	$.ajax({
		type: 'post',
		url: 'php/drawRectanglesLeaflet.php',
		data: {"fileName": fileName, "docID": docID,"collection": collection},
		success:function(data){
			rectangleCoords = JSON.parse(data);
			drawRectangles(collection,rectangleCoords);
			}
		});
		
	
}

//This function draws rectangles on document viewer window using the JSON passed to it from getRectangleCoords() 
//it also initiates the click event on each drawn rectangle. 
function drawRectangles(collection,coords)
{
	var latlng = new Array();
	var latlng2 = new Array();
	
	//iterate through entire coords array unprojecting values and storing them 
	var i = 0;
	while(coords[i] != null)
	{
		latlng.push(rc.unproject([coords[i].x1, coords[i].y1]));
		latlng2.push(rc.unproject([coords[i].x2, coords[i].y2]));
		i++;
	}
	
	//iterate through entire coords array
	var j = 0;
	while(coords[j] != null)
	{
		var entryData; // This may not be in use anymore 
		//create a rectangle object based on unprojected values of coords
		//add that reactangle objecet to viewer 
		var rectangle = L.rectangle([[latlng[j].lat, latlng[j].lng],
		[latlng2[j].lat, latlng2[j].lng]],{color: "	 #58d68d", weight: 1});
		rectangleArray.push(rectangle);
		map.addLayer(rectangleArray[j]);
		
		//click function that highlights clicked object and calls getEntryData.php 
		//passes JSON from getEntryData.php as parameter to displayEntryData()
		rectangle.on('click', function(e)
		{ 
			// if the user has a marker drawn on the map exit click function early 
			if(markerCount > 0)
				return;
			
			rectangleSelected = true; 
			
			var highlight = {color: "#FF0000", weight: 1};
			var defaultColor = {color: "#58d68d", weight: 1};			

			for(var i = 0; i < rectangleArray.length; i++)
			{
				rectangleArray[i].setStyle(defaultColor);
			}
			
			this.setStyle(highlight);				
			
			//get projected bounds latlng values of clicked rectangle to be used for querying database 
			point1 = rc.project(this.getBounds()._southWest);
			point2 = rc.project(this.getBounds()._northEast);
			//initiates entryObject with coordinates and path of selected rectangle. 
			//for use in getEntryData.php to query for other entry field date.
			entryObject = addEntryObject(collection,docID,point1.x, point1.y, point2.x, point2.y, fileName);
			$.ajax({
				type: 'post',
				url: 'php/getEntryData.php',
				data: {"entryObject": JSON.stringify(entryObject)},
				success:function(data){
					entryData = JSON.parse(data);
					displayEntryData(entryData);
				}
			});	
		})
		j++;
	}
}

//This function is called when form is submitted performs proccesses to prepare page for next entry.
function submitEntry(results)
{	
	//Error handler that obtains the parameter from the ajax function to determine if the rectangle layer should be added and the markers deleted
	//or the rectangle should not be added and the point markers erased. 
	if (results == "New record created successfully"){
		document.getElementById("myForm").reset();
		location.reload();
	}
	else
		deletePrevious();
	
}

//form submit function
$(function () 
{	
	$("#myForm").submit(function(e)
	{
		if(markerCount < 2)
		{
			alert("Select area on document before submitting");
			e.preventDefault();
			return;			
		}

        getFieldBookTableJSON();
        getMapTableJSON();

        var entrycoordinates = JSON.parse($("#Entry_Coordinates").val());
         x1 = entrycoordinates.x1;
         y1 = entrycoordinates.y1;
         x2 = entrycoordinates.x2;
         y2 = entrycoordinates.y2;


        //retrieve values from form and set them to appropriate variables
        collection = document.getElementById("Collection").value;
        docID = document.getElementById("Document_ID").value;
        fileName = document.getElementById("File_Name").value;
        surveyOrSection = document.getElementById("Survey_Or_Section").value ;
        blockOrTract = document.getElementById("Block_Or_Tract").value;
        lotOrAcres = document.getElementById("Lot_Or_Acres").value;
        description = document.getElementById("Description").value ;
        client = document.getElementById("Client").value ;
        fieldBookInfo = document.getElementById("Field_Book_Info").value ;
        relatedPapersFileNo = document.getElementById("Related_Papers_File_No").value;
        //date = document.getElementById("Date").value;
        jobNumber = document.getElementById("Job_Number").value;
        mapInfo = document.getElementById("Map_Table_Info").value;
        comments = document.getElementById("Comments").value;

        /*Conditional statement that allows to update the entry by selecting a value from the Date table, so if the
         value selected is not a numeric value like; Month, Day, or Year, the value that will be stored in the
         database will be a 0000 value for a year, or 00 for month and day*/
        if (document.getElementById("Year").value == "Year")
        {
            var Year = "0000";
        }
        else
            Year = document.getElementById("Year").value;

        if(document.getElementById("Month").value == "Month")
        {
            var Month = "00"
        }
        else if(document.getElementById("Month").value < 10)
            Month = "0" + document.getElementById("Month").value;
        else
            Month = document.getElementById("Month").value;

        if (document.getElementById("Day").value == "Day")
        {
            var Day = "00";
        }
        else if(document.getElementById("Day").value < 10)
            Day = "0" + document.getElementById("Day").value;
        else
            Day = document.getElementById("Day").value;

        var dateString = Year + "-" + Month + "-" + Day;
        date = dateString;

        jobNumber = document.getElementById("Job_Number").value;


        //creates JSON that contains all information from form to update the db
        var newobject = addEntryObject(collection,docID,x1, y1, x2, y2, fileName, surveyOrSection, blockOrTract, lotOrAcres, description, client,
            fieldBookInfo, relatedPapersFileNo, mapInfo, date, jobNumber,comments);

		$.ajax({
			type: 'post',
			url: 'php/submitEntry.php',
			data: {"newobject": JSON.stringify(newobject)},
			success: function(results){
				alert(results);
				submitEntry(results);
				}
		});
	e.preventDefault();
	});
});

//This function obtains the information from the select form dropdown for 
function Map_Kind_Dropdown(){
		Map_Kind_Options = document.getElementById("Map_Kind").options;
	}

//This function retrieves fileName which in this case is the path to the image and writes result to hidden form field.
function getFileName()
{
	var fileName = window.localStorage.getItem('fileName');
	document.getElementById("File_Name").value = fileName;
	return fileName;
}

//This function is used to translate input from date drop down field to a JSON.
function getDateTableJSON()
{		

	if (document.getElementById("Year").value == "Year")
	{
		var Year = "0000";
	}
	else
		Year = document.getElementById("Year").value;
		
	if(document.getElementById("Month").value == "Month")
	{
		var Month = "00"
 	}
	else if(document.getElementById("Month").value < 10)
				Month = "0" + document.getElementById("Month").value;
	else
		Month = document.getElementById("Month").value;
			
	if (document.getElementById("Day").value == "Day")
	{
		var Day = "00";
	}
	else if(document.getElementById("Day").value < 10)
		Day = "0" + document.getElementById("Day").value;
	else
		Day = document.getElementById("Day").value;
		
	dateString = Year + "-" + Month + "-" + Day;
	document.getElementById("Date").value = dateString;
		
 }

//This function is binded to the button "Delete Active Rectangle" this deletes the 
//rectangle in blue that the user has drawn but not submitted. 
function deletePrevious()
{	
	if(markerCount == 2)
	{
		map.removeLayer(rectangleArray[rectangleArray.length-1]);
		rectangleArray.splice(rectangleArray.length, 1);
		rectangleCoords.splice(rectangleCount-1, 2);
		rectangleCount--;
		
		map.removeLayer(markerArray[markerCount-1]);
		map.removeLayer(markerArray[markerCount-2]);
		markerArray.splice(markerCount-2, 2);
		markerCount = markerCount-2;
		
		document.getElementById('Entry_Coordinates').value = "";
	}
	else if(markerCount == 1)
	{
		rectangleCoords.splice(0, 1)
		
		map.removeLayer(markerArray[markerCount-1]);
		markerArray.splice(-1,1 );
		markerCount--;
	}
	else
		return null;
}

//This function takes entryData JSON as parameter and writes values to appropriate HTML form location.
function displayEntryData(entryData)
{
	//write entry data to form
	document.getElementById("Document_ID").value = entryData[0].documentID;
	//document.getElementById("File_Name").value = entryData[0].Document;
	document.getElementById("Entry_Coordinates").value = "SouthWest(" + entryData[0].x1 + ", " + entryData[0].y1 + ")" 
														+ " " + "NorthEast(" + entryData[0].x2 + ", " +  entryData[0].y2 + ")";
	document.getElementById("Survey_Or_Section").value = entryData[0].surveyorsection;
	document.getElementById("Block_Or_Tract").value = entryData[0].blockortract;
	document.getElementById("Lot_Or_Acres").value = entryData[0].lotoracres;
	document.getElementById("Description").value = entryData[0].description;
	document.getElementById("Client").value = entryData[0].client;
	displayFieldBookTableInfo(entryData[0].fieldbookinfo, "Field_Book_Table");
	displayMapTableInfo(document.getElementById("Collection").value,entryData[0].mapinfo, "Map_Table");
	
	//document.getElementById("Field_Book_Info").value = entryData[0].Field_Book_Info;
	document.getElementById("Related_Papers_File_No").value = entryData[0].relatedpapersfileno;

	
	//document.getElementById("Map_Info").value = entryData[0].Map_Info;
	document.getElementById("Date").value = entryData[0].date;
	document.getElementById("Map_Table_Info").value = entryData[0].mapinfo;
	
	var Time = entryData[0].date.split("-");
	if (Time[1] == 00)
	{
		document.getElementById("Month").value = "Month";
	}
	else
	{
		var Month = parseInt(Time[1]);
		document.getElementById("Month").value = Month;
	}
	
	if (Time[2] == 00)
	{
		document.getElementById("Day").value = "Day";
	}
	else
	{
		var Day = parseInt(Time[2]);
		document.getElementById("Day").value = Day;
	}
	
	if (Time[0] == 0000)
	{
		document.getElementById("Year").value = "Year";
	}
	else
	{
		var Year = parseInt(Time[0]);
		document.getElementById("Year").value = Year;
	}
	
	document.getElementById("Job_Number").value = entryData[0].jobnumber;
	document.getElementById("Comments").value = entryData[0].comments;
	
	//write to hidden forms
	document.getElementById("x1").value = entryData[0].x1;
	document.getElementById("y1").value = entryData[0].y1;
	document.getElementById("x2").value = entryData[0].x2;
	document.getElementById("y2").value = entryData[0].y2;
}

//This function creates "updateObject" containing all data from HTML form, 
//and calls updateEntryData.php to update database with newly created updateObject values.
function updateEntryData()
{
	if (rectangleSelected == false)
	{
		alert("No rectangled selected");
		return null;
	}
	
	getFieldBookTableJSON();
	getMapTableJSON();
	
	//retrieve values from form and set them to appropriate variables
	collection = document.getElementById("Collection").value;
	docID = document.getElementById("Document_ID").value;
	fileName = document.getElementById("File_Name").value;
	x1 = document.getElementById("x1").value;
	y1 = document.getElementById("y1").value;
	x2 = document.getElementById("x2").value;
	y2 = document.getElementById("y2").value;
	surveyOrSection = document.getElementById("Survey_Or_Section").value ;
	blockOrTract = document.getElementById("Block_Or_Tract").value;
	lotOrAcres = document.getElementById("Lot_Or_Acres").value;
	description = document.getElementById("Description").value ;
	client = document.getElementById("Client").value ;
	fieldBookInfo = document.getElementById("Field_Book_Info").value ;
	relatedPapersFileNo = document.getElementById("Related_Papers_File_No").value;
	//date = document.getElementById("Date").value;
	jobNumber = document.getElementById("Job_Number").value;
	mapInfo = document.getElementById("Map_Table_Info").value;
	comments = document.getElementById("Comments").value;
	
	/*Conditional statement that allows to update the entry by selecting a value from the Date table, so if the
	value selected is not a numeric value like; Month, Day, or Year, the value that will be stored in the 
	database will be a 0000 value for a year, or 00 for month and day*/
	if (document.getElementById("Year").value == "Year")
		{
			var Year = "0000";
		}
		else
			Year = document.getElementById("Year").value;
		
		if(document.getElementById("Month").value == "Month")
		{
				var Month = "00"
		}
		else if(document.getElementById("Month").value < 10)
			Month = "0" + document.getElementById("Month").value;
		else
			Month = document.getElementById("Month").value;
			
		if (document.getElementById("Day").value == "Day")
		{
			var Day = "00";
		}
		else if(document.getElementById("Day").value < 10)
			Day = "0" + document.getElementById("Day").value;
		else
			Day = document.getElementById("Day").value;
		
		var dateString = Year + "-" + Month + "-" + Day;
		date = dateString;

	jobNumber = document.getElementById("Job_Number").value;
	
	
	//creates JSON that contains all information from form to update the db
	updateObject = addEntryObject(collection,docID,x1, y1, x2, y2, fileName, surveyOrSection, blockOrTract, lotOrAcres, description, client,
						fieldBookInfo, relatedPapersFileNo, mapInfo, date, jobNumber,comments);
	$.ajax({
		type: 'post',
		url: 'php/updateEntryData.php',
		data: {"updateObject": JSON.stringify(updateObject)},
		success:function(data){
			alert(data);
		}
	});	
}

//This function deletes the object that is selected from the viewer and reloads the page.
function deleteSelected()
{
	if (rectangleSelected == false)
	{
		alert("No rectangled selected");
		return null;
	}
	var collection = document.getElementById("Collection").value;
	var docID = document.getElementById("Document_ID").value;
	var x1 = document.getElementById("x1").value;
	var y1 = document.getElementById("y1").value;
	var x2 = document.getElementById("x2").value;
	var y2 = document.getElementById("y2").value;
	
	//creates object that contains location of selected rectangle so that the db can be queried and delete it 
	var deleteObject = addEntryObject(collection,docID,x1, y1, x2, y2);
	
	$.ajax({
		type: 'post',
		url: 'php/deleteEntry.php',
		data: {"deleteObject": JSON.stringify(deleteObject)},
		success:function(data){
			alert("Entry Deleted");
			location.reload();
		}
	});
}

//This function is an auxillary function to displayEntryData
//The purpose of this function is to add the functionality to display the JSON for fieldBookInfo as a table.
function displayFieldBookTableInfo(string, id)
{	
	//creates JSON from JSON string that is passed to it in the "string" parameter
	object = JSON.parse(string);
	var table = document.getElementById(id);
	
	deleteTable(id);
	
	for(var i = 0 ; i < object.length; i++)
	{	
		//if the table is already the correct size leave for loop
		if(table.rows.length-1 == object.length)
			break;
		
		//inserts rows and populates cells with values
		var row = table.insertRow(-1)
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		
		cell1.innerHTML = '<input type="text" class= "Input_Field"' + 'value = "' + object[i].bookNumber + '">';
		cell2.innerHTML = '<input type="text" class= "Input_Field"' + 'value = "' + object[i].pageNumbers + '">';
	}
}

//same as displayFieldBookTableInfo() but for the mapInfo
function displayMapTableInfo(collection,string, id)
{	
	object = JSON.parse(string);
	var table = document.getElementById(id);
	
	deleteTable(id);
	

//$.get("php/MapKind_Options.php",{collection: collection})
	for(var i = 0 ; i < object.length; i++)
	{
        //if the table is already the correct size leave for loop
        if(table.rows.length-1 == object.length)
            break;

		// var row = table.insertRow(-1);
		// var cell1 = row.insertCell(0);
		//table.innerHTML+= '<tr><td><input type="text" class= "Input_Field"' + 'value = "' + object[i].mapNumber + '"></td>';

		$.ajax({
			url: 'php/MapKind_Options.php', //This is the current doc
			type: "POST",
			//dataType:'json', // add json datatype to get json
			data: {"collection": collection,"mapKind": object[i].mapKind,"mapNumber": object[i].mapNumber,"id": i},
			success: function(data) {
                table.innerHTML += data;
			}
		});
	}
		// if(table.rows.length-1 == object.length)
		// 	return;

}

//This function is an auxillary function for displayFieldBookTableInfo() and displayMapTableInfo().
//The purpose of this function is to delete the table completely before the new table is created so avoid having unused rows .
function deleteTable(id)
{
	var table = document.getElementById(id);
	if(table.rows.length == 1)
		return;
	
	table.deleteRow(table.rows.length-1);
	
	//recursive call 
	deleteTable(id);
}

//creates stringified JSON from values in Field_Book_Table
function getFieldBookTableJSON()
{
	table = document.getElementById("Field_Book_Table");
	var fieldBookObjectArray = new Array();
	
	//loop to iterate through each row of table populating fieldBookObjectArray with values 
	for(var i = 1; i < table.rows.length; i++)
	{
		fieldBookObject = addFieldBookObject(document.getElementById("Field_Book_Table").rows[i].cells[0].firstChild.value,
											document.getElementById("Field_Book_Table").rows[i].cells[1].firstChild.value)
		fieldBookObjectArray.push(fieldBookObject);
	}
	
	//sets value of form to stringified JSON of fieldBookObjectArray
	document.getElementById("Field_Book_Info").value = JSON.stringify(fieldBookObjectArray);
}

//creates stringified JSON from values in Map_Table
function getMapTableJSON()
{
	table = document.getElementById("Map_Table");
	var mapObjectArray = new Array();
	
	for(var i = 1; i < table.rows.length; i++)
	{
		mapObject = addMapObject(document.getElementById("Map_Table").rows[i].cells[0].firstChild.value,
											document.getElementById("Map_Table").rows[i].cells[1].firstChild.value)
		mapObjectArray.push(mapObject);
	}

	document.getElementById("Map_Table_Info").value = JSON.stringify(mapObjectArray);

}

function addFieldRow(id)
{	
		var table = document.getElementById(id);
		var row = table.insertRow(-1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		cell1.innerHTML = '<input type="text" class= "Input_Field">';
		cell2.innerHTML = "<input type= 'text' class= 'Input_Field' id = 'Field_Book_Page'>";
}

//functionality for "+" button 
function addMapRow(collection,id)
{
    var table = document.getElementById(id);

    $.ajax({
        url: 'php/MapKind_Options.php', //This is the current doc
        type: "POST",
        //dataType:'json', // add json datatype to get json
        data: {"collection": collection,"mapKind": "","mapNumber": "","id":""},
        success: function(data) {
            var row = table.insertRow(-1);
            row.innerHTML += data;
        }
    });
}

//functionality for "-" button
function deleteMapRow(id)
{
	if(id == 'Field_Book_Table')
	{
		var table = document.getElementById("Field_Book_Table");
		if(table.rows.length == 2)
			return;
		table.deleteRow(-1);
	}
	else if(id == 'Map_Table')
	{
		var table1 = document.getElementById("Map_Table");
		if(table1.rows.length == 2)
			return;
		table1.deleteRow(-1);
	}
}

function incompleteTranscription()
{
    var collection = document.getElementById("Collection").value;
    var id = document.getElementById("Document_ID").value;

    $.ajax({
        url: 'php/incompleteTranscription.php',
        type: "POST",
        data: {"collection": collection,"docID": id,"fileName": window.localStorage.getItem('fileName')},
        success: function(data) {
            window.close();
        }
    });
}

function completeTranscription()
{
    var collection = document.getElementById("Collection").value;
	var id = document.getElementById("Document_ID").value;

    $.ajax({
        url: 'php/completeTranscription.php',
        type: "POST",
        data: {"collection": collection,"docID": id,"fileName": window.localStorage.getItem('fileName')},
        success: function(data) {
            alert(data);
            window.close();
        }
    });
}

//called on refresh of page 
$(document).ready(function () {
    resetForms();
});

//resets every field in the form
function resetForms() {
    document.forms['myForm'].reset();
}

//Object Construction Functions
function addEntryObject(collection,docID,x1,y1, x2, y2, fileName, surveyOrSection, blockOrTract, lotOrAcres, description, client,
						fieldBookInfo, relatedPapersFileNo, mapInfo, entryDate, jobNumber, comments)
{
	var entryObject = new entryObjectConstructor(collection,docID,x1,y1, x2, y2, fileName, surveyOrSection, blockOrTract, lotOrAcres, description, client,
						fieldBookInfo, relatedPapersFileNo, mapInfo, entryDate, jobNumber,comments);
	return entryObject;
}

function entryObjectConstructor(collection,docID,x1,y1, x2, y2, fileName,surveyOrSection, blockOrTract, lotOrAcres, description, client,
						fieldBookInfo, relatedPapersFileNo, mapInfo, entryDate, jobNumber,comments)
{
	this.collection = collection;
	this.docID = docID;
	this.x1 = x1;
	this.y1 = y1;
	this.x2 = x2;
	this.y2 = y2;
	this.fileName = fileName;
	this.surveyOrSection = surveyOrSection;
	this.blockOrTract = blockOrTract;
	this.lotOrAcres = lotOrAcres;
	this.description = description;
	this.client = client;
	this.fieldBookInfo = fieldBookInfo;
	this.relatedPapersFileNo = relatedPapersFileNo;
	this.mapInfo = mapInfo
	this.entryDate = entryDate;
	this.jobNumber = jobNumber;
    this.comments = comments;
}

function addFieldBookObject(bookNumber, pageNumbers)
{
	var fieldBookObject = new fieldBookObjectConstructor(bookNumber, pageNumbers);
	return fieldBookObject;
}

function fieldBookObjectConstructor(bookNumber, pageNumbers)
{
	this.bookNumber = bookNumber;
	this.pageNumbers = pageNumbers;
}

function addMapObject(mapNumber, mapKind)
{
	var mapObject = new mapObjectConstructor(mapNumber, mapKind);
	return mapObject;
}

function mapObjectConstructor(mapNumber, mapKind)
{
	this.mapNumber = mapNumber;
	this.mapKind = mapKind;
}

function addEntryCoordinateObject(x1,y1, x2, y2)
{
	var entryCoordinateObject = new entryCoordinateObjectConstructor(x1,y1, x2, y2);
	return entryCoordinateObject;
}

function entryCoordinateObjectConstructor(x1,y1, x2, y2)
{
	this.x1 = x1;
	this.y1 = y1;
	this.x2 = x2;
	this.y2 = y2;
}


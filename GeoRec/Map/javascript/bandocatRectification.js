//Tutorial page that is opened when the Tutorial button is pressed.
function Tutorial() {
    window.open("../html_css/Slide_Tutorial.html", resizable= "yes", scrollbars="yes", height= "400px", width= "450px" );
}

//Called when deletePrevious button is pressed. The purpose of this function is 
//to delete the most recently placed marker. The arrays and counters have to be spliced
//and decrements as well.
function deletePrevious()
{
	var mapIndex = count - 1;
	var rasterIndex = rasterCount - 1;
	
	//if there is nothing to delete 
	if(rasterIndex < 0)
		return null;
	
	//if last marker to be placed was on the map viewer
	if(mapIndex == rasterIndex)
	{
		map.removeLayer(markerArray[mapIndex]);
		markerArray.splice(mapIndex, 1);
		table.deleteRow(count+1);
		gcpList.pop();
		count--;
		rasterSelected = true;
		mapSelected = false;
		colorCount--;
	}
	//if last marker to be placed was on the raster viewer 
	else
	{
	raster.removeLayer(rasterMarkerArray[rasterIndex]);
	rasterMarkerArray.splice(rasterIndex, 1);
	rasterCoords.pop();
	rasterCount--;
	rasterSelected = false;
	mapSelected = true;
	}
}


//NEEDS FURTHER IMPLEMENTATION
function openCatalog()
{
	var pdf = "catalogInfo.html";
	window.open(pdf);
}

//This function is executed when the "Cancel" button is pressed. 
//The funtion creates a "scriptObject" containing the name of the file, this is used by cancel.php to delete
//the directory and all of its contents created during the tiling proccess. 
function cancel()
{
	openModal(2);
	
	var gdalTranslateScript = 0;
	var gdalWarpScript = 0;
	var scriptObject = addScriptObject(gdalTranslateScript, gdalWarpScript, rasterJSON);
	
	$.post("php/cancel.php", { jsonData: JSON.stringify(scriptObject)}, function(results){
			closeModal(2), window.close()});
}

//Called when the "Rectify" button is pressed. The purpose of this function is to 
//prepare the JSON containing the strings to be executed in "submitRectification.php" and 
//make the AJAX call to execute it.
function rectify()
{
	//if user has selected more points on raster viewer than on map viewer
	if(rasterCount > count)
	{
		alert("Uneven amount of points selected");
		return null;
	}
	//if the user has not selected at least 3 points 
	if(count < 3)
	{
		alert("3 points or more must be placed");
		return null;
	}
	//if user has met requirements for successful rectification 
	else
	{
		openModal(1);
		var gdalTranslateScript = translateScriptCreator();
		var gdalWarpScript = warpScriptCreator();
		var scriptObject = addScriptObject(gdalTranslateScript, gdalWarpScript, rasterJSON);

		//collect point number, control points and raster points from #table
		var pointEntries = [];
        var table = document.getElementById("table");
        for (var i = 1, row; row = table.rows[i]; i++) {
            //iterate through rows
            //rows would be accessed using the "row" variable assigned in the for loop
            var entry = [];
            for (var j = 0, col; col = row.cells[j]; j++) {

                entry.push(col.innerText);
            }
            	pointEntries.push(entry);
        }

		$.post("php/submitRectification.php", { jsonData: JSON.stringify(scriptObject),pointEntries: JSON.stringify(pointEntries)}, function(results){
			alert(results),closeModal(1),window.close()});
	}
}

//This function is used by the "rectify" function to create the string that will be executing gdaltranslate on the TIFF.
function translateScriptCreator()
{
		var prefix = 'gdal_translate  -of GTiff -a_srs WGS84 ';
		//var suffix = translateInputFile + " " + translateOutputFile;
		var suffix = "";
	    var complete = "";
		for(var i = 0; i < count; i++)
		{
			prefix = prefix + " -gcp " + JSON.stringify(gcpList[i].x) + " " + JSON.stringify(gcpList[i].y) + " " + JSON.stringify(gcpList[i].lng) + " " + JSON.stringify(gcpList[i].lat);
		}
		complete = prefix + " " + suffix;
		return complete;
}

//This function is used by the "rectify" function to create the string that will be executing gdalwarp on the TIFF .
function warpScriptCreator()
{
	var prefix = "gdalwarp -s_srs WGS84 -t_srs WGS84 -tps ";
	//var complete = prefix + " " + translateOutputFile + " " + warpOutputFile;
	var complete = prefix;
	return complete;
}

//This function displays the loading modal on the screen.
function openModal(num)
{
	document.getElementById('modal_' + num).style.display = 'block';
	document.getElementById('fade_' + num).style.display = 'block';
}

//This function closes the loading modal.
function closeModal(num) 
{
	document.getElementById('modal_' + num ).style.display = 'none';
	document.getElementById('fade_' + num).style.display = 'none';
}

//This function receives a latlng coordinate as a paramter and performs a linear search 
//on the "coords" array and returns the index of the matching coordinate.
function getIndex(latlng)
{
	for(var i = 0; i < coords.length +1; i++)
	{
		if(coords[i] == latlng)
		{
			return i;
		}
	}
	return null;
};

//This function is valled whenever button representing a marker on the gcp table is pressed.
//The purpose of this function is to set the view of both viewers to the point that corresponds with the button that was pressed.
//The parameter "i" is the value of count when the button was created. 
function zoomToMarker(i)
{
	map.setView(markerArray[i-1].getLatLng(), 15);
	raster.setView(rc.unproject([gcpList[i-1].x, gcpList[i-1].y]), 4);
}

//JSON Object Contructor Functions 
function scriptObjectConstructor(translate, warp, fileName)
{
	this.translate = translate;
	this.warp = warp;
	this.fileName = fileName;
}

function addScriptObject(translate, warp, tiles)
{
	var scriptObject = new scriptObjectConstructor(translate, warp, tiles);
	return scriptObject;
}

function gcpConstructor(id, lat, lng,rlat,rlong, x, y)
{
	this.id = id;
	this.lat = lat;
	this.lng = lng;
	this.rlat = rlat;
	this.rlong = rlong;
	this.x = x;
	this.y = y;
};

function addGCP(id, lat, lng,rlat,rlong, x, y)
{
	var gcp = new gcpConstructor(id, lat, lng,rlat,rlong, x, y);
	gcpList.push(gcp);
};

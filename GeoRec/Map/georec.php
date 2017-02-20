<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(!isset($_GET['col']) || !isset($_GET['docID']) || !isset($_GET['type'])) {
    //if either one of three above param is not set, alert the user, then close the windows
    echo "<script>alert('Forbidden access!')</script>";
    echo "<script>window.close()</script>";
}

//needs control render class to render dropdownlist of divGeoRecStatus
require_once('../../Library/ControlsRender.php');
$Render = new ControlsRender();
require_once('../../Library/DBHelper.php');
require_once('../../Library/MapDBHelper.php');
$DB = new MapDBHelper();

//get collection information from bandocatdb table (to get DisplayName mostly)
$collection = $DB->SP_GET_COLLECTION_CONFIG($_GET['col']);

$DB->SWITCH_DB($_GET['col']); //switch to this collection's database
$isBack = $_GET['type'] == "back" ? true : false; //identify if this map is a front or a back scan

//get georec information for this map from georectification table
$georec_entries = $DB->GEOREC_ENTRIES_SELECT($_GET['docID'],$isBack);
//get georec status for this map from document table
$georec_status = $DB->DOCUMENT_GEORECSTATUS_SELECT($_GET['docID'],$isBack);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $collection['DisplayName']; ?> Georectification</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <link href='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css' rel='stylesheet' />
    <link href='css/styles.css' rel='stylesheet' />
    <link rel="stylesheet" href= "css/l.geosearch.css"/>
    <link rel="stylesheet" href= "../../Master/master.css"/>
    <script src='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js'></script>
    <script src='../../ExtLibrary/rastercoords.js'></script>
    <script src="../../ExtLibrary/Leaflet.MakiMarkers.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script src="javascript/l.control.geosearch.js"></script>
    <script src="javascript/l.geosearch.provider.esri.js"></script>
    <script type="text/javascript" src="javascript/bandocatRectification.js"></script>
</head>
<style>
/*    Styles for popup Update Status */
    #divUpdateGeoRecStatus{
        position:absolute;
        z-index: 1000;
        width:100%;
        height:90%;
        background: #fff;
        opacity:0.9;
        padding:100px;
    }
    #frmUpdateStatus
    {
        font-size:1.5em;
        padding-top:180px;
        line-height: 3em;
        width:500px;
        margin:0 auto;
    }
</style>
<body>

<!--Title Section/Loading Modal Divs-->

<div id = 'title'><?php echo $collection['DisplayName']; ?> Georeferencer</div>
<div id = 'logo'> <img src = "../../Images/Logos/bando-logo-small.png"></div>
<h1 id = 'fileName'></h1>

<div id="fade_1"></div>
<div id="modal_1">
    <img id="loader_1" src="../../Images/loading.gif" />
    <div>Rectifying...</div>
</div>
<div id="fade_2"></div>
<div id="modal_2">
    <img id="loader_2" src="../../Images/loading.gif" />
    <div>Cleaning Temporary Workspace...</div>
</div>

<!--Buttons and Button Functionality-->
<!--<form id = "Tutorial">-->
<!--    <button href= "#" onclick="Tutorial(); return false;">Tutorial</button>-->
<!--</form>-->

<div id = 'buttons'>
    <button onclick = "deletePrevious()" id = 'deletePrevious' name="deletePrevious" class="bluebtn">Delete Previous</button>
    <button onclick="document.getElementById('divUpdateGeoRecStatus').style.visibility = 'visible';" class="bluebtn"> Set Status </button>
    <button onclick = "rectify()" id = "rectify" class="bluebtn"> Rectify / Update </button>
    <button onclick = "cancel()" id = "cancel" class="bluebtn"> Cancel </button>

</div>

<!-- Map Display and Functionality -->

<div id="map"></div>
<script id = "test" language="javascript" type="text/javascript">
    //need mapbox API key
    L.mapbox.accessToken = "";
    L.MakiMarkers.accessToken = "";


    var map = L.mapbox.map("map");

    //Base layers with leaflet layer control
    var street = L.tileLayer('https://api.mapbox.com/styles/v1/xuan27/ciylr8exe004r2smg2t48oxpz/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoieHVhbjI3IiwiYSI6IktzT0hVNjAifQ.v97O2GRYRJ8ZxhLHtTn30g', {
        attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a> contributors'
    }).addTo(map);

    var satellite = L.tileLayer('https://api.mapbox.com/styles/v1/xuan27/cip8mhdpt000obunqv4jc47zp/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoieHVhbjI3IiwiYSI6IktzT0hVNjAifQ.v97O2GRYRJ8ZxhLHtTn30g', {
        attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a> contributors'
    });

    var baseMaps = {
        "Street": street,
        "Satellite": satellite
    };
    L.control.layers(baseMaps).addTo(map);

    //adds geocoder to map
    new L.Control.GeoSearch({
        provider: new L.GeoSearch.Provider.Esri(),
        showMarker: false
    }).addTo(map);

    var markerColors = new Array('#e74c3c','#2980b9', '#2ecc71','#ffee58','#9b59b6',
        '#FE9A2E', '#f1948a', '#80deea', '#abebc6',  '#f9e79f','#b388ff', '#ffcc80'); // array of possible colors of markers
    var colorCount = 0;						//counter that increments when ever a new color from markerColors needs to be used
    var count = 0;							//might be identical to colorCount and currently be unnecessary
    var mapSelected = true;				//boolean keeping track of if a point on the map was placed most recently
    var marker;								//variable that contains the marker to be placed
    markerArray = new Array();		//array that holds all the markers that are created on the map
    var gcpList = [];							// array of JSON that contains latlng and index of gcp from both map and raster
    var UTM = "+proj=utm +zone=14 +ellps=WGS84 +datum=WGS84 +units=m +no_defs"

    map.setView([27.73725068372226, -97.43062019348145], 12);

    //executes on click of map
    map.addEventListener("click", function(event)
    {
        //sets marker color and style
        Maki_Icon = icon = L.MakiMarkers.icon({icon: colorCount+1, color: markerColors[colorCount], size: "m"});
        if(!mapSelected) {
            //creates new object of gcpList
            addGCP(count, event.latlng.lat, event.latlng.lng, rasterCoords[rasterCount - 1].rlat, rasterCoords[rasterCount - 1].rlong, rasterCoords[rasterCount - 1].x, rasterCoords[rasterCount - 1].y);
            //creates marker and popup
            marker = L.marker(event.latlng, {icon: icon});
            marker.bindPopup("Edit").openPopup().closePopup;
            markerArray.push(marker);
            map.addLayer(marker);

            var markerIndex = marker.on('click', function clickIndex(event) {
                for (i = 0; i < gcpList.length; i++) {
                    if (gcpList[i].lat == event.latlng.lat) {
                        markerIndex = i;
                        console.log(markerIndex);
                    }
                }
                event.target.dragging.enable();
                return markerIndex;
            });

            marker.on('dragend', function (event) {
                var marker = event.target;
                var position = marker.getLatLng();
                var latitude = marker._latlng.lat;
                gcpList[markerIndex].lat = latitude;
                var longitude = marker._latlng.lng;
                gcpList[markerIndex].lng = longitude;

            });


            //alert(colorCount);
            colorCount++;
            if (colorCount > 11)
                alert("Maximum amount of points reached");

            count++;
            rasterSelected = false;
            mapSelected = true;

            //creates table
            var table = document.getElementById("table");
            var row = table.insertRow(count + 1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            var cell6 = row.insertCell(5);
            var cell7 = row.insertCell(6);
            cell1.innerHTML = "<button id = 'zoomToMarker' onclick = 'zoomToMarker(" + count + ")' style = 'background-color:" + markerColors[colorCount - 1] + "'>" + count + "</button>";
            cell2.innerHTML = gcpList[count - 1].lat;
            cell3.innerHTML = gcpList[count - 1].lng;
            cell4.innerHTML = gcpList[count - 1].rlat;
            cell5.innerHTML = gcpList[count - 1].rlong;
            cell6.innerHTML = gcpList[count - 1].x;
            cell7.innerHTML = gcpList[count - 1].y;
            //hide column raster lat and raster long
            cell4.style.display = "none";
            cell5.style.display = "none";
        }else {
            alert("Select point from raster");
        }
    })

</script>
<!-- Raster Display and Functionality -->

<div id = "raster">
    <script>
        //retrieve relevant raster information from index page
        file_data = window.localStorage.getItem("imageInfo");									//retrieves name of file from local storage set by index page
        rasterJSON = JSON.parse(file_data);													//parses and assigns to variable stringifiied JSON also set by index page
        document.getElementById("fileName").innerHTML = "File name: <a target='_blank' href='../../Templates/Map/review.php?col=" + rasterJSON.collection + "&doc=" + rasterJSON.docID + "'>" + rasterJSON.fileName + "</a>";	// writes name of file to top of raster viewer

        var rasterCount = 0;							//counter that increments every time a point is placed on raster viewer
        var rasterSelected = false;					//boolean that keeps track of if the raster viewer was most recently selected
        rasterMarkerArray = new Array();		// array that holds all of the points placed on the raster viewer
        rasterCoords = new Array();			// array that holds all the coordinates of points on raster viewer

        var minZoom = 0,
            maxZoom = 5,
            img = [
                rasterJSON.width,  // original width of raster
                rasterJSON.height  // original height of raster
            ];

        // create the raster
        var raster = L.map("raster",{
            minZoom: minZoom,
            maxZoom: maxZoom,
        });

        // assign raster and raster dimensions
        rc = new L.RasterCoords(raster, img);

        // set the bounds on raster
        rc.setMaxBounds();

        //sets starting view of raster viewer to center of the document
        raster.setView(rc.unproject([img[0]/2, img[1]/2]), 2);

        var tiles = './Temp/Tiles/' + rasterJSON.tempSubDirectory + '/{z}/{x}/{y}.png';	// the tile layer containing the raster generated with gdal2tiles --leaflet ...

        //add tiles to raster viewer
        L.tileLayer(tiles, {
            noWrap: true,
            attribution: '',

        }).addTo(raster);


        //executes on click event on raster viewer
        raster.on("click", function(event)
        {
            Maki_Icon = icon = L.MakiMarkers.icon({icon: colorCount+1, color: markerColors[colorCount], size: "m"});

            if(!rasterSelected) {
                //pushes data from click into appropriate arrays
                var coords = rc.project(event.latlng);
                rasterCoords.push({rlat:event.latlng.lat,rlong:event.latlng.lng,x:event.layerPoint.x,y:event.layerPoint.y});
                //creates marker and popup
                var rasterMarker = L.marker(rc.unproject(coords), {icon: icon})
                rasterMarkerArray.push(rasterMarker);
                raster.addLayer(rasterMarker);
                rasterMarker.bindPopup("Point Number: " + (rasterCount + 1)).openPopup;
                //increment counters and adjust booleans
                rasterCount++;
                rasterSelected = true;
                mapSelected = false;
            }
            else
            {
                alert("Select point on map");
            }
        });

    </script>
</div>

<!-- GCP Table -->

<div id = "Table_Container">
    <table class = "tg" id ="table" style = "width: 100%; height:23%; bottom: 0;">
        <tbody style = "align-items: center; background-color: #ccffff">
        <tr style = "background-color: #ccffcc">
            <th>Point Number</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th style="display: none">Raster Latitude</th>
            <th style="display: none">Raster Longitude</th>
            <th>Raster X</th>
            <th>Raster Y</th>
        <tr/>
    </table>
</div>
<div id="divUpdateGeoRecStatus" style="visibility: hidden">
    <form id="frmUpdateStatus" name="frmUpdateStatus">
        <table>
            <tr>
                <td width="300px">Update GeoRec Status</td>
                <td><select style="height: 2.5em" id="ddlGeoStatus" name="ddlGeoStatus" required>
                        <?php
                            $arrGeoStatus = array(array(0,"Not Rectified"),array(2,"Not Rectifiable"));
                            $Render->GET_DDL3($arrGeoStatus,$georec_status);
                        ?>
                        ?>
                    </select>
                <input type="hidden" name="txtDocID" id="txtDocID" value="<?php echo $_GET['docID']; ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center"><input type="Submit" class="bluebtn" name="btnUpdate" id="btnUpdate" value="Update">
                &nbsp;&nbsp;
                    <button type="button" onclick="document.getElementById('divUpdateGeoRecStatus').style.visibility = 'hidden';" class="bluebtn">Close</button></td>
            </tr>
        </table>
    </form>
</div>
</body>
<script>
    //this will be fired when the tab is reloaded/redirected or close
    window.onbeforeunload = function (e) {

    };

    $(document).ready(function(){

            //load the points into the map and raster if there is any
            var entries = <?php echo json_encode($georec_entries); ?>;

            for(var i = 0; i < entries.length; i++)
            {
                var rasterlatlng = L.latLng(entries[i][3],entries[i][4]);
                var rasterXY = L.point(entries[i][5],entries[i][6]);
                var maplatlng = L.latLng(entries[i][1],entries[i][2]);
                raster.fireEvent('click',{latlng:rasterlatlng,containerPoint:rasterXY,layerPoint:rasterXY});
                map.fireEvent('click',{latlng:maplatlng});
            }

    });
    //action when submitting popup Update GeoRec Status
    $("#frmUpdateStatus").submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/updateGeoStatus_processing.php?col=" + rasterJSON.collection + "&type=" + '<?php echo $_GET['type']; ?>',
            data: $("#frmUpdateStatus").serializeArray(),
            success: function (data) {
                if(data == 1){ //success
                    alert("Status updated successfully!");
                    cancel();
                }

            }
        });
    });
</script>
</html>

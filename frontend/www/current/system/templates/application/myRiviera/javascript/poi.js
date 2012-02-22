var filterArray = [];
var positionMarkers = [], prevMarker=null;//id of prevmarker
var poisMarkers = {
		"cityway": { markers: [], prevMarkers: null},
		"mymed": { markers: [], prevMarkers: null},
		"carf": { markers: [], prevMarkers: null}
}; //markers: list of gmap markers, prevmarker: id of markers last displayed
var currentLatitude = 0;
var currentLongitude = 0;
var currentSegmentID;
var updatezoom = true; //if we update zoom with focusonposition
var isPersistent = true; //global behaviour, if we keep or not previous location POIS when we focus elsewhere

Array.prototype.contains = function(aValue){
	if( this.toString().match(aValue)) return true;
};

function updateFilter(){
	
	// just update for the newly selected items or all for new location, ToDo erase the newly unselected item if !isPersistent, put isPersistent in options 
	$.each($("#select-filter").val() || [], function(i, type) {
		if (filterArray.indexOf(type)<0 || filterArray.length==($("#select-filter").val() || []).length)
			switch(type){
			case "cityway":
				citywayPOI(currentSegmentID);
				break;
			default:
				if((pois = getPOIs(currentLatitude, currentLongitude, type, 500)).length != 0){
					otherPOI(pois, type, currentSegmentID);
				}
				break;
			}
	});
	
	filterArray = $("#select-filter").val() || [];
}

/**
 * Zoom on a position
 * @param position
 */
function focusOnPosition(latitude, longitude){
	
	// memorize the current position
	currentLatitude = latitude;
	currentLongitude = longitude;

	// focus on the position
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	map.setCenter(myLatlng);
	window.scrollTo(0,0);

	// clear the markerArray (POIs array TODO rename it)
	
	//clearPOIs();/* no longer used to re-use cached markers*/

}

/**
 * Get the complete list of POI around the position according to the radius
 * @param latitude
 * 		latitude in degree
 * @param longitude
 * 		longitude in degree	
 * @param radius
 * 		radius in meter
 */
function getPOIs(latitude, longitude, type, radius) {

	var result = new Array();
	args = "code=1";
	args += "&application=" + $("#applicationName").val();
	args += "&type=" + type;
	args += "&latitude=" + latitude;
	args += "&longitude=" + longitude;
	args += "&radius=" + radius;
	args += "&accessToken=" + $("#accessToken").val();

	var res = $.ajax({
		url : "backend/POIRequestHandler",
		dataType : 'json',
		data : args,
		async : false
	}).responseText;

	if((resJSON = $.parseJSON(res)) != null) {
		if((pois = $.parseJSON(resJSON.data.pois)) != null) {
			result = result.concat(pois);
		}
	}
	return result;
}

/**
 * Add marker according to the myMed jSon format
 * @param elt
 */

function updatePOIs(latitude, longitude, icon, title, index){
	currentSegmentID = index;
	positionPOI(latitude, longitude, icon, title, index);
	updateFilter();
}

function addPOI(latitude, longitude, icon, title, description){
	var marker = new google.maps.Marker({
		animation: google.maps.Animation.DROP,
		position: new google.maps.LatLng(latitude, longitude),
		title: title,
		icon: icon,
		map: map
	});
	var contentString = 
		"<div class='poiContent'>" +
			"<h2 class='poiFirstHeading'>" + title + "</h2>"+
			"<div class='poiBodyContent'>"+description+"</div>" +	
		"</div>";
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map, this);
	});
	google.maps.event.addListener(map, 'click', function(){
		infowindow.close();
	});
	return marker;
}


function positionPOI(latitude, longitude, icon, title, index){
	if ( !index || (index && !positionMarkers[index])){ //create new marker
		var marker = addPOI(latitude, longitude, icon, title, $("#poicomment_"+index).html());
		if (index){ //index argument is given, we store this marker for future reuse
			positionMarkers[index] = marker;
			if (updatezoom) {updatezoom=false; map.setZoom(16);}//only once or it gets annoying
		}
	} else {//already existing, redrop it
		positionMarkers[index].setAnimation(google.maps.Animation.DROP);
		marker=positionMarkers[index];
		if (updatezoom) {updatezoom=false; map.setZoom(16);}//only once or it gets annoying
	}
	
	if(!isPersistent){
		if (prevMarker && prevMarker!=index){
			marker[prevMarker].setMap(null);
			prevMarker = index;
		}
	}
}

function clearPOIs(){
	for (var i = 0; i < markerArray.length; i++ ) {
		markerArray[i].setMap(null);
	}
	markerArray = new Array();
}

/**
 * Focus on a Trip Segment (CityWay API)
 * @param id
 * 
 */

function citywayPOI(id, latitude, longitude){
	if (!poisMarkers.cityway.markers[id]){ //create them if not exist
		poiJson = decodeURIComponent($("#poi_"+id).val().replace(/\+/g, ' '));
		poi = JSON.parse(poiJson);
		var poiIterator = 0;
		poisMarkers.cityway.markers[id] = [];
		for (var i =0; i < poi.length; i++) {
			setTimeout(function() {
				var myMarkerImage = "";
				if(poi[poiIterator].category == "4"){
					myMarkerImage = 'system/templates/application/myRiviera/img/velobleu.png';
				} else if (poi[poiIterator].category == "5"){
					myMarkerImage = 'system/templates/application/myRiviera/img/veloparc.png';
				} else if (poi[poiIterator].category == "10"){
					myMarkerImage = 'system/templates/application/myRiviera/img/info.png';
				} else if (poi[poiIterator].category == "1" || poi[poiIterator].category == "2" || poi[poiIterator].category == "3" || poi[poiIterator].category == "8"){
					myMarkerImage = 'system/templates/application/myRiviera/img/lieu.png';
				} else {
					myMarkerImage = 'system/templates/application/myRiviera/img/trip.png';
				}
				
				var marker = addPOI(poi[poiIterator].latitude, poi[poiIterator].longitude, myMarkerImage, poi[poiIterator].name, "<p> type: " + poi[poiIterator].type.toLowerCase() + 
						"<br />" + "ville: " + poi[poiIterator].localityName.toLowerCase());

				poisMarkers.cityway.markers[id].push(marker);
				poiIterator++;
			}, i * 200);	
		}
	}else { //redrop them, well see how it's better?
		/*for (var i =0; i < poisMarkers.cityway.markers[id].length; i++) {
			setTimeout(function() {
				poisMarkers.cityway.markers[id][i].setAnimation(google.maps.Animation.DROP);
			}, i * 200);
		}*/
	}
	
	if (!isPersistent){
		if (poisMarkers.cityway.prevMarkers && poisMarkers.cityway.prevMarkers!=index){
			t = poisMarkers.cityway.markers[poisMarkers.cityway.prevMarkers];
			for (i in t) {
				t[i].setMap(null);
			}
			poisMarkers.cityway.prevMarkers = index;
		}
	}
}

function otherPOI(pois, type, index){
	if (!poisMarkers[type].markers[index]){ //create them if not exist
		poisMarkers[type].markers[index] =  [];
		$.each(pois, function(i, poi) {
			value = $.parseJSON(poi.value);
			var marker = addPOI(value.latitude,  value.longitude, value.icon, value.title, value.description);
			poisMarkers[type].markers[index].push(marker);
		});
	}
	if (!isPersistent){
		if (poisMarkers[type].prevMarkers && poisMarkers[type].prevMarkers!=index){
			t = poisMarkers[type].markers[poisMarkers[type].prevMarkers];
			for (i in t) {
				t[i].setMap(null);
			}
			poisMarkers[type].prevMarkers = index;
		}
	}
}
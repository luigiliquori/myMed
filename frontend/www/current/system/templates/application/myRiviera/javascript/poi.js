var filterArray = new Array();
var markerArray = new Array();
var currentLatitude = 0;
var currentLongitude = 0;
var currentSegmentID = 0;

Array.prototype.contains = function(aValue){
	if( this.toString().match(aValue)) return true;
};

function updateFilter(){
	filterArray = new Array();
	$.each($("#select-filter").val(), function(i, item) {
		filterArray[i] = item;
	});
	if(currentLatitude && currentLongitude && currentSegmentID){
		focusOn(currentSegmentID, currentLatitude, currentLongitude);
	}
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
	map.setZoom(16);
	window.scrollTo(0,0);
	
	//drop Pin ToDo remove after
	var marker = new google.maps.Marker({
		animation: google.maps.Animation.DROP,
		position: myLatlng,
		/*icon: 'system/templates/application/myRiviera/img/position.png',*/
		map: map
	});

	
	// clear the markerArray
	clearPOIs();
	
	// add the POIs around the position
	if((pois = getPOIs(latitude, longitude, 500)).length != 0){
		$.each(pois, function(i, poi) {
			value = $.parseJSON(poi.value);
			addPOI(value.latitude,  value.longitude, value.icon, value.title, value.title);
		});
	}
	
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
function getPOIs(latitude, longitude, radius) {

	var result = new Array();
	$.each(filterArray, function(i, type) {
		
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
	});
	return result;
}

/**
 * Add marker according to the myMed jSon format
 * @param elt
 */
function addPOI(latitude, longitude, icon, title, description){
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	var marker = new google.maps.Marker({
		animation: google.maps.Animation.DROP,
		position: myLatlng,
		title: title,
		icon: icon
	});
	markerArray.push(marker);
	marker.setMap(map);

	var contentString = 
		"<div class='poiContent'>" +
		"<h2 class='poiFirstHeading'>" + title + "</h2>"+
		"<div class='poiBodyContent'>" +
		+ description +
		"</div>" +	
		"</div>";
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

function clearPOIs(){
    for (var i = 0; i < markerArray.length; i++ ) {
    	markerArray[i].setMap(null);
    }
    markerArray = new Array();
}

/* ****************** */
/* DEPRECATED METHODs */
/* ****************** */
/**
 * Focus on a Trip Segment (CityWay API)
 * @param id
 * @deprecated
 */
function focusOn(id, latitude, longitude){
	currentSegmentID = id;
	
	// new Method
	focusOnPosition(latitude, longitude);

	// add marker only for cityway
	// TODO Remove this part
	if (filterArray.contains("cityway")) {
		poiJson = $("#" + id + "_poi").val();
		poi = JSON.parse(poiJson);
		var poiIterator = 0;
		for (var i =0; i < poi.length; i++) {
			setTimeout(function() {
				var myLatlng = new google.maps.LatLng(poi[poiIterator].latitude, poi[poiIterator].longitude);
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
				var marker = new google.maps.Marker({
					animation: google.maps.Animation.DROP,
					position: myLatlng,
					title:poi[poiIterator].name,
					icon: myMarkerImage
				});
				markerArray.push(marker);
				marker.setMap(map);

				var contentString = 
					"<div class='poiContent'>" +
					"<h2 class='poiFirstHeading'>" + poi[poiIterator].name.toLowerCase() + "</h2>"+
					"<div class='poiBodyContent'>" +
					"<p> type: " + poi[poiIterator].type.toLowerCase() + "<br />" +
					"ville: " + poi[poiIterator].localityName.toLowerCase() +
					"</div>" +	
					"</div>";
				var infowindow = new google.maps.InfoWindow({
					content: contentString
				});

				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map,marker);
				});
				poiIterator++;
			}, i * 200);
		}
	}
}


var currentLatitude = 0;
var currentLongitude = 0;
var currentSegmentID;

var updatezoom = true;

// extends Array
Array.prototype.contains = function(aValue){
	if( this.toString().match(aValue)) return true;
};


/**
 * Zoom on the position define by the latitude and the longitude
 * 
 * @param latitude
 * @param longitude
 */
function focusOnPosition(latitude, longitude){
	
	// memorize the current position
	currentLatitude = latitude;
	currentLongitude = longitude;

	// focus on the position
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	map.setCenter(myLatlng);
	window.scrollTo(0,0);

}

/**
 * Get the complete list of Marker around the position according to the radius
 * @param latitude
 * 		latitude in degree
 * @param longitude
 * 		longitude in degree	
 * @param radius
 * 		radius in meter
 */
function getMarkers(latitude, longitude, type, radius) {

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
 * Add a marker on the map
 * @param latitude
 * @param longitude
 * @param icon
 * @param title
 * @param description
 * @returns {google.maps.Marker}
 */
function addMarker(latitude, longitude, icon, title, description) {
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

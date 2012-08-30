var geocoder;

$(document).ready(function() {
	// INITIALIZE DASP->MAP
	setupDASPMap("myMap", displayPosition, displayError, false);
	geocoder = new google.maps.Geocoder();
});

function displayPosition(position) {

	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

	// create current position marker
	new google.maps.Marker({
		position : latlng,
		title : "ma position",
		icon : "img/position.png",
		map : map
	});

	// focus on the position
	focusOnLatLng(latlng);

}

function displayError(error) {

}

/**
 * 
 * @param type
 * @param lon
 * @param lat
 * @param rad
 */
function printMarkers(type, lon, lat, rad) {

	var params = {
			'application': $("#applicationName").val(),
			'type': type,
			'latitude': lat,
			'longitude': lon,
			'radius': rad,
			'accessToken': $("#accessToken").val(),
			'code': 1
	};

	$.ajax({
		url: "../../lib/dasp/ajax/POI.php",
		data: params,
		dataType: "json",
		success: function(data){
			if ( data && data.dataObject.pois.length){
				$.each(data.dataObject.pois, function(i, poi) {
					value = JSON.parse(poi.value);
					id = poi.id;
					icon = null;
					// type
					description = "<p>Type: 	" + type + "</p>";
					// description
					description += value.description;
					// delete button;
					description += "<br /><br /><a href='#' onClick='deleteMarker(\"" + value.latitude + "\", \"" + value.longitude + "\", \"" + poi.id + "\", \"" + type +"\")' data-role='button' data-theme='r' >Delete</a>";
					var marker = addMarker(new google.maps.LatLng(value.latitude, value.longitude), icon, value.title, description, null, false, id);
					google.maps.event.addListener(marker, "click", function(e) {
						marker.ib.open(map, this);
					});
				});
			}
		},
		error: function(data){
			alert("error: " + data)	;
		}
	});
	
	focusOnPosition(lat, lon);
}

function deleteMarker(latitude, longitude, itemId, type) {
	var params = {
			'application': $("#applicationName").val(),
			'type': type,
			'latitude': latitude,
			'longitude': longitude,
			'itemId': itemId,
			'accessToken': $("#accessToken").val()
	};
	
	$.ajax({
		url: "../../lib/dasp/ajax/POI.php",
		data: params,
		dataType: "json",
		success: function(data){
			alert("POI deleted");
		},
		error: function(data){
			alert("error: " + data);
		}
	});
}

/**
 * 
 * @param lat
 * @param lng
 * @param address
 */
function convertLatLng(lat, lng, address){
	if(address == "") {
		var latlng = new google.maps.LatLng(lat, lng);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[1]) {
					$('#converted_address').val(results[0].formatted_address);
				}
			}
		});
	} else {
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				$('#converted_longitude').val(results[0].geometry.location.lng());
				$('#converted_latitude').val(results[0].geometry.location.lat());
			}
		});
	}
}

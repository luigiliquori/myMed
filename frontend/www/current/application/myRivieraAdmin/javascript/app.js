var geocoder;
var markersArray;
var currentMarker;

$(document).ready(function() {
	// INITIALIZE DASP->MAP
	setupDASPMap("myMap", displayPosition, displayError, false);
	geocoder = new google.maps.Geocoder();
	markersArray = new Array();
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
	currentMarker=null;
	deleteOverlays();
	
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
					try {
						value = JSON.parse(poi.value);
						
						id = poi.id;
						icon = 		value.icon ? 		value.icon : null;
						adresse = 	value.adresse ? 	value.adresse : null;
						email = 	value.email ? 		value.email : null;
						link = 		value.link ? 		value.link : null;
						idMedia = 	value.idMedia ? 	value.idMedia : null;
						altitude = 	value.altitude ? 	value.altitude : null;
						
						description = "<p>Type: " + type + "</p>";
						description += value.description;
						// TO DELETE THE POI
						description += "<br /><br /><a href='#' onClick='deleteMarker(\"" + value.latitude + "\", \"" + value.longitude + "\", \"" + poi.id + "\", \"" + type +"\")' data-role='button' data-theme='r' >Delete</a>";
						
						// add marker
						var marker = addMarker(new google.maps.LatLng(value.latitude, value.longitude), icon, value.title, description, null, false, id, adresse, email, link, idMedia, altitude);
						markersArray.push(marker); 
						google.maps.event.addListener(marker, "click", function(e) {
							marker.ib.open(map, this);
							currentMarker = marker;
						});
						
					} catch (err) {
//						alert("ERR : " + poi.value);
					}
				});
			}
		},
		error: function(data) {
			alert("error: " + data)	;
		}
	});

	focusOnPosition(lat, lon);
}

function deleteMarker(latitude, longitude, itemId, type) {
	var params = {
			'delete' : true,
			'application': $("#applicationName").val(),
			'type': type,
			'latitude': latitude,
			'longitude': longitude,
			'itemId': itemId.replace(/\+/g, '%20'),
			'accessToken': $("#accessToken").val()
	};

	$.ajax({
		url: "../../lib/dasp/ajax/POI.php",
		data: params,
		dataType: "json",
		success: function(data){
			removeMarker(currentMarker);
			alert("POI deleted!");
		},
		error: function(data){
			alert("error: " + data);
		}
	});
}

function deleteOverlays() {
	if (markersArray) {
		for (i in markersArray) {
			markersArray[i].setMap(null);
		}
		markersArray.length = 0;
	}
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

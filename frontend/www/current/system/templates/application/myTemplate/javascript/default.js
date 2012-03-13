var geocoder;
var currentPositionMarker;

function initialize() {
	// INIT JAVASCRIPT FOR THE TEMPLATE
	setupDASP($("#userID").val(),
			$("#accessToken").val(), 
			$("#applicationName").val());

	// INITIALIZE DASP->MAP
	setupDASPMap($("#applicationName").val() + "Map", displayPosition, displayError);

	// INTI GEOCODER
	geocoder = new google.maps.Geocoder();
	
	// Auto Complete
//	for(var i = 1 ; i < 4 ; i++){
//		for(var j = 0 ; j < 4 ; j++) {
//			if(document.getElementById("formatedAddress" + "View" + i + j)){
//				var autocompleteAddr = new google.maps.places.Autocomplete(document.getElementById("formatedAddress" + "View" + i + j));
//				autocompleteAddr.bindTo('bounds', map);
//			}
//		}
//	}
	
	// resize the map on page change
	for(var i = 1 ; i < 4 ; i++) {
		$("#View" + i).live("pageshow", function (event, ui) {
			google.maps.event.trigger(map, 'resize');
			
		});
	}
}


/**
 * Géoloc - ok
 * 
 * @param position
 */
function displayPosition(position) {
	
	// reverse geocode
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	
	// add position marker
	if(!currentPositionMarker) { // WATCH POSITION - do it only the first time
		currentPositionMarker = addMarker(latlng, null, "your current position", "<h3>current position</h3>", google.maps.Animation.BOUNCE);
		focusOnLatLng(latlng);
	}
	currentPositionMarker.setMap(map);
	
	// Field position address
	geocoder.geocode({'latLng': latlng}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				for(var i = 0 ; i < 4 ; i++) {
					$("#formatedAddress" + i).val(results[0].formatted_address);
				}
			}
		} else {
			alert("Geocoder failed due to: " + status);
		}
	});

}

/**
 * Géoloc - ko
 * 
 * @param error
 */
function displayError(error) {
	var errors = {
			1 : 'Permission refusée',
			2 : 'Position indisponible',
			3 : 'Requête expirée'
	};
	console.log("Erreur géolocalisation: " + errors[error.code]);
	if (error.code == 3)
		navigator.geolocation.getCurrentPosition(displayPosition, displayError);
}

/**
 * 
 * @param address
 */
function refreshMap(address, icon) {
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			// FOCUS
			focusOnPosition(results[0].geometry.location.lat(), results[0].geometry.location.lng());
			// ADD MARKER
			addMarker(results[0].geometry.location, icon, address, "<h3>" + address + "</h3>", google.maps.Animation.DROP);
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});

}

/**
 * Load the destination address from a contact
 * 
 * @param dest
 */
function changeAddress(dest) {
	picture = $("#select" + dest).val().split("&&")[0];
	address = $("#select" + dest).val().split("&&")[1];
	$("#formatedAddress" + dest).val(address);
	refreshMap(address, picture.replace("?type=large", ""));
}
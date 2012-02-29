var geocoder;

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
	for(var i = 0 ; i < 4 ; i++) {
		var autocompleteAddr = new google.maps.places.Autocomplete(document.getElementById("formatedAddress" + i));
		autocompleteAddr.bindTo('bounds', map);
	}
	
}

/**
 * Géoloc - ok
 * 
 * @param position
 */
function displayPosition(position) {

	// ADD POSITION Marker
	addMarker(position.coords.latitude, position.coords.longitude, null, "your current position", "<h3>your current position</h3>", google.maps.Animation.BOUNCE);
	
	// focus on the position
	if (focusOnCurrentPosition) {
		focusOnPosition(position.coords.latitude, position.coords.longitude);
	}

	// reverse geocode
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
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
function refreshMap(address) {
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			// FOCUS
			focusOnPosition(results[0].geometry.location.lat(), results[0].geometry.location.lng());
			// ADD MARKER
			addMarker(results[0].geometry.location.lat(), results[0].geometry.location.lng(), null, address, "<h3>" + address + "</h3>", google.maps.Animation.DROP);
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});

}
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
function refreshMap(address, icon) {
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			// FOCUS
			focusOnPosition(results[0].geometry.location.lat(), results[0].geometry.location.lng());
			// ADD MARKER
			addMarker(results[0].geometry.location.lat(), results[0].geometry.location.lng(), icon, address, "<h3>" + address + "</h3>", google.maps.Animation.DROP);
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});

}

/**
 * Load the desotherMarkertination address from a contact
 * 
 * @param dest
 */
function changeAddress(dest) {
	picture = $("#select" + dest).val().split("&&")[0];
	address = $("#select" + dest).val().split("&&")[1];
	$("#formatedAddress" + dest).val(address);
	refreshMap(address, picture.replace("?type=large", ""));
}


/* --------------------------------------------------------- */
/* Create Application */
/* --------------------------------------------------------- */
function createApplication() {
	
	url = "?APPLICATION_NAME=" + $("#myAppName").val();
	
	for(var i=1 ; i < 4 ; i++) {
		url += "&VIEW_" + i + "_GEOLOC=" + $("#VIEW_" + i + "_GEOLOC").val() +
		"&VIEW_" + i + "_MAP=" + $("#VIEW_" + i + "_MAP").val() +
		"&VIEW_" + i + "_PUBLISH=" + $("#VIEW_" + i + "_PUBLISH").val() +
		"&VIEW_" + i + "_SUBSCRIBE=" + $("#VIEW_" + i + "_SUBSCRIBE").val() +
		"&VIEW_" + i + "_FIND=" + $("#VIEW_" + i + "_FIND").val() +
		"&VIEW_" + i + "_PROFILE=" + $("#VIEW_" + i + "_PROFILE").val() +
		"&VIEW_" + i + "_SOCIAL_NETWORK=" + $("#VIEW_" + i + "_SOCIAL_NETWORK").val();
	}
	
	$(location).attr('href',url);
}



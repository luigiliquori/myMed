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
	
	// reverse geocode
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	
	// add position marker
	if(!currentPositionMarker) { // WATCH POSITION - do it only the first time
		currentPositionMarker = addMarker(latlng, null, "your current position", "<h3>your current position</h3>", google.maps.Animation.BOUNCE);
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
		"&VIEW_" + i + "_PUBLISH_keyword=" + $("#VIEW_" + i + "_PUBLISH_keyword").val() +
		"&VIEW_" + i + "_PUBLISH_address=" + $("#VIEW_" + i + "_PUBLISH_address").val() +
		"&VIEW_" + i + "_PUBLISH_date=" + $("#VIEW_" + i + "_PUBLISH_date").val() +
		
		"&VIEW_" + i + "_SUBSCRIBE=" + $("#VIEW_" + i + "_SUBSCRIBE").val() +
		"&VIEW_" + i + "_SUBSCRIBE_keyword=" + $("#VIEW_" + i + "_SUBSCRIBE_keyword").val() +
		"&VIEW_" + i + "_SUBSCRIBE_address=" + $("#VIEW_" + i + "_SUBSCRIBE_address").val() +
		"&VIEW_" + i + "_SUBSCRIBE_date=" + $("#VIEW_" + i + "_SUBSCRIBE_date").val() +
		
		"&VIEW_" + i + "_FIND=" + $("#VIEW_" + i + "_FIND").val() +
		"&VIEW_" + i + "_FIND_keyword=" + $("#VIEW_" + i + "_FIND_keyword").val() +
		"&VIEW_" + i + "_FIND_address=" + $("#VIEW_" + i + "_FIND_address").val() +
		"&VIEW_" + i + "_FIND_date=" + $("#VIEW_" + i + "_FIND_date").val() +
		
		"&VIEW_" + i + "_PROFILE=" + $("#VIEW_" + i + "_PROFILE").val() +
		"&VIEW_" + i + "_SOCIAL_NETWORK=" + $("#VIEW_" + i + "_SOCIAL_NETWORK").val();
	}
	
	$(location).attr('href',url);
}


function initialize() {
	// INITIALIZE DASP->MAP
	setupDASPMap("myMap", displayPosition, displayError, false);
}

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
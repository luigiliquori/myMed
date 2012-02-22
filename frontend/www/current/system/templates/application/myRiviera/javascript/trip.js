var directionsService = new google.maps.DirectionsService();
var map;
var currentLatitude, currentLongitude, accuracy;
var focusOnCurrentPosition = true;


/**
 * Initialize the application
 */
function initialize() {
	if(!map) {
		directionsDisplay =  new google.maps.DirectionsRenderer();

		// resize the map canvas
		$("#myRivieraMap").height($("body").height() - 45);

		map = new google.maps.Map(document.getElementById("myRivieraMap"), {
			zoom: 16,
			center: new google.maps.LatLng(43.7, 7.27),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		// autocompletes Google Maps Places API, should make it work
		var autocompleteDepart = new google.maps.places.Autocomplete(document.getElementById('depart'));
		var autocompleteArrivee = new google.maps.places.Autocomplete(document.getElementById('arrivee'));
		autocompleteArrivee.bindTo('bounds', map);
		autocompleteDepart.bindTo('bounds', map);

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(displayPosition, displayError,
					{enableHighAccuracy : true, timeout: 5000, maximumAge: 0});	
		} else {
			alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
		}
	}
}

/**
 * Géoloc - ok 
 * @param position
 */
function displayPosition(position) {
	currentLatitude = position.coords.latitude;
	currentLongitude = position.coords.longitude;
	accuracy = position.coords.accuracy;
	$('#departGeo').val(currentLatitude+'&'+currentLongitude);
	$('#depart').attr("placeholder", "Ma position");

	// ADD POSITION Marker
	var latlng = new google.maps.LatLng(currentLatitude, currentLongitude);
	marker = new google.maps.Marker({
		animation: google.maps.Animation.BOUNCE,
		position: latlng,
		icon: 'system/templates/application/myRiviera/img/position.png',
		map: map
	});

	// if the accuracy is good enough, print a circle to show the area
	if (accuracy && accuracy<1500){ // is use watchPosition instead of getCurrentPosition don't forget to clear previous circle, using circle.setMap(null)
		circle = new google.maps.Circle({
			strokeColor: "#0000ff",
			strokeOpacity: 0.2,
			strokeWeight: 2,
			fillColor: "#0000ff",
			fillOpacity: 0.1,
			map: map,
			center: latlng,
			radius: accuracy
		});
	}

	// focus on the position on show the POIs around
	if(focusOnCurrentPosition){
		focusOnPosition(currentLatitude, currentLongitude);
	}
}

/**
 * Géoloc - ko
 * @param error
 */
function displayError(error) {
	var errors = { 
			1: 'Permission refusée',
			2: 'Position indisponible',
			3: 'Requête expirée'
	};
	console.log("Erreur géolocalisation: " + errors[error.code]);
	if (error.code == 3)
		navigator.geolocation.getCurrentPosition(displayPosition, displayError);
}

/**
 * Print the trip on the current map
 * @param trip - jSon trip - Google Based
 * @returns
 */
function showTrip(trip) {
	// TODO
}
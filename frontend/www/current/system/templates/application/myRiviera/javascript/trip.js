var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;
var poi;
var poiMem = {};
var poiIterator;
var myLat, myLng;

/**
 * Initialize the application
 */
function initialize() {
	directionsDisplay =  new google.maps.DirectionsRenderer();

	// resize the map canvas
	$("#myRivieraMap").height($("body").height() - 45);

	map = new google.maps.Map(document.getElementById("myRivieraMap"), {
		zoom: 13,
		center: new google.maps.LatLng(43.7, 7.27),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay.setMap(map);
	
	// autocompletes Google Maps Places API, should make it work
     var autocompleteDepart = new google.maps.places.Autocomplete(document.getElementById('depart'));
     var autocompleteArrivee = new google.maps.places.Autocomplete(document.getElementById('arrivee'));
     autocompleteDepart.bindTo('bounds', map);

	// GEOLOC
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			myLat = position.coords.latitude;
			myLng = position.coords.longitude;
			//$("#mapos").val(myLat+'&'+myLng);
			$('#depart').val(myLat+'&'+myLng);
			focusOnPosition(position.coords.latitude, position.coords.longitude);
		}, 
		null, 
		{enableHighAccuracy:true});
	} else {
		alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
	}
}

/**
 * Zoom on a position
 * @param position
 */
function focusOnPosition(latitude, longitude){
	// ZOOM
	map.panTo(new google.maps.LatLng(latitude, longitude));

	// ADD POSITION Marker
	myMarkerImage = 'system/templates/application/myRiviera/img/position.png';
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(latitude, longitude),
		icon: myMarkerImage,
		map: map
	});
}


/**
 * Print route (itineraire) from Google API
 * In case of cityway errors
 */
function calcRouteFromGoogle() {
	var start = document.getElementById("start").value; 
	var end = document.getElementById("end").value;
	if (start.indexOf('&') >0){
		start = new google.maps.LatLng(myLat, myLng);
	}
	var request = {
			origin:start,
			destination:end,
			travelMode: google.maps.TravelMode.DRIVING
	};
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			$("#itineraire").delay(1500).fadeIn("slow");
			//alert("L'API Cityway n'a pu trouver de résultats pour cette date ou ces lieux.\n Le résultat affiché est donné par Google Maps API");
			directionsDisplay.setDirections(result);
			var listview = $("<ul data-role='listview' data-inset='true' data-theme='d' data-divider-theme='e'></ul>");
			console.log(result.routes[0].legs[0].distance.value+" m");
			/*for (var i=0; i < result.routes[0].legs[0].steps.length; i++){
				
			}*/
		}
	});
}

/**
 * Print route (itineraire) from CityWay API
 * @param url
 */
function calcRouteFromCityWay(url){
//	$("#myRivieraMap").height($("body").height() - ($("body").height()/2));
	$("#itineraire").delay(1500).fadeIn("slow");
	var KmlObject = new google.maps.KmlLayer(url);
	KmlObject.setMap(map);
}

/**
 * Load the destination address from a contact
 * @param dest
 */
function changeDestination(dest){
	picture = $("#select"+dest).val().split("&&")[0];
	address = $("#select"+dest).val().split("&&")[1];
	$("#"+dest).val(address);
	$("#"+dest).css("background-image", 'url('+picture+')');
}

/**
 * Set the current time on the date field
 */
function setTime(){
	var d=new Date();
	$("#date").val( d.getHours()+":"+("0" + (d.getMinutes())).slice(-2)+" le "+ d.getDate()+'/'+d.getMonth() +'/'+d.getFullYear());
}

function getMap(){
	
}
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;
var poi;
var poiMem = {};
var poiIterator;
var latitude, longitude, accuracy; //Html geolocation response

/**
 * Initialize the application
 */
function initialize() {
	directionsDisplay =  new google.maps.DirectionsRenderer();

	// resize the map canvas
	$("#myRivieraMap").height($("body").height() - 45);

	map = new google.maps.Map(document.getElementById("myRivieraMap"), {
		zoom: 16,
		center: new google.maps.LatLng(43.7, 7.27),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay.setMap(map);
	
	// autocompletes Google Maps Places API, should make it work
     var autocompleteDepart = new google.maps.places.Autocomplete(document.getElementById('depart'));
     var autocompleteArrivee = new google.maps.places.Autocomplete(document.getElementById('arrivee'));
     autocompleteArrivee.bindTo('bounds', map);
     //autocompleteDepart.bindTo('bounds', map); should also be bound, toDo test it 2 can be bound

	// GEOLOC
    function displayPosition(position) {
    	latitude = position.coords.latitude;
    	longitude = position.coords.longitude;
		accuracy = position.coords.accuracy;
		$('#departGeo').val(latitude+'&'+longitude);
		$('#depart').attr("placeholder", "");
		myMarkerImage = 'system/templates/application/myRiviera/img/position.png';
		$('#depart').css("background-image", 'url('+myMarkerImage+')');
		
		// ADD POSITION Marker
		var latlng = new google.maps.LatLng(latitude, longitude);
		
		var marker = new google.maps.Marker({
			position: latlng,
			icon: myMarkerImage,
			map: map
		});

		// if the accuracy is good enough, print a circle to show the area
		if (accuracy && accuracy<1500){ // is use watchPosition instead of getCurrentPosition don't forget to clear previous circle, using circle.setMap(null)
			var circle = new google.maps.Circle({
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
		focusOnPosition(latitude, longitude);
    }
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
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(displayPosition, displayError,
			{enableHighAccuracy : true, timeout: 5000, maximumAge: 0});	
	} else {
		alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
	}
	
}

/**
 * Print route (itineraire) from Google API
 * In case of cityway errors
 */
function calcRouteFromGoogle(start, end, isMobile) {
	//var start = document.getElementById("start").value; 
	//var end = document.getElementById("end").value;
	console.log(start+" "+end);
	if (start==""){
		start = new google.maps.LatLng(latitude, longitude);
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
			
			$('<h3>Vers '+result.routes[0].legs[0].start_address+' '+result.routes[0].legs[0].distance.text+'</h3>').prependTo($("#itineraire ul"));
			for (var i=0; i < result.routes[0].legs[0].steps.length; i++){
				var step = result.routes[0].legs[0].steps[i];
				$('<li data-role="list-divider"><span>'+step.travel_mode+'</span></li>').appendTo($('#itineraire ul'));
				var desc = $('<li style="font-size: 9pt; font-weight: lighter; padding:2px;"><a href="#" onclick="focusOn('+i+'); '+(isMobile?'$("#itineraire").trigger("collapse")':'')+' data-icon="search"></a></li>');
				
				$('<li data-role="list-divider"><span>gyjg,</span></li>').appendTo(desc.find('a'));
				
				desc.appendTo($('#itineraire ul'));
			}
			$("#itineraire ul").listview("refresh");
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


function getMap(){
	
}
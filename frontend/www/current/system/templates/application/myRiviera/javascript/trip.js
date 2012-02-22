var directionsService = new google.maps.DirectionsService();
var map;

var poi;
var poiMem = {};
var poiIterator;
var currentLatitude, currentLongitude, accuracy; //Html geolocation response

var focusOnCurrentPosition = true;


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

/**
 * Géoloc 
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
	/*marker = new google.maps.Marker({
		animation: google.maps.Animation.BOUNCE,
		position: latlng,
		icon: 'system/templates/application/myRiviera/img/position.png',
		map: map
	});*/

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
 * Print route (itineraire) from Google API
 * In case of cityway errors
 */
function calcRouteFromGoogle(start, end, isMobile) {
	//var start = document.getElementById("start").value; 
	//var end = document.getElementById("end").value;
	console.log(start+" "+end);
	if (start==""){
		start = new google.maps.LatLng(currentLatitude, currentLongitude);
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

			$("<li data-role='list-divider'><span>"+result.routes[0].legs[0].steps[0].travel_mode.toLowerCase()+"</span></li>").appendTo($('#itineraire ul')); //ToDo should check if travel_mode changes in the following loop
			//$('<h3>Vers '+result.routes[0].legs[0].start_address+' '+result.routes[0].legs[0].distance.text+'</h3>').prependTo($("#itineraire ul"));
			for (var i=0; i < result.routes[0].legs[0].steps.length; i++){
				st = result.routes[0].legs[0].steps[i];
				desc = $("<li style='font-size: 9pt; font-weight: lighter; padding:2px;'><a href='#' onclick=focusOnPosition('"+st.start_point.Pa+"','"+st.start_point.Qa+"', 'true'); "+(isMobile?"$('#itineraire').trigger('collapse')":"")+" data-icon='search'></a></li>");
				//desc = $('<li style="font-size: 9pt; font-weight: lighter; padding:2px;"><a href="#" onclick=focusOnPosition("'+st.start_point.Pa+'","'+st.start_point.Qa+'"); '+(isMobile?'$("#itineraire").trigger("collapse")':'')+' data-icon="search"></a></li>');


				$("<li data-role='list-divider'><span>Distance: "+st.distance.text+" durée; "+st.duration.text+"</span></li>").appendTo(desc.find('a'));
				$('<p style="width: 90%;">'+st.instructions+'</p>').appendTo(desc);

				desc.appendTo($('#itineraire ul'));
			}
			$("#itineraire ul").listview("refresh");
		}
	});
}

function calcRoute(start, end, mobile) {
	//if (resulttype=="Cityway"){
		listDivider = null;
		icon = null;
		routes=[];
		for (i in result.ItineraryObj.tripSegments.tripSegment){
			tripSegment = result.ItineraryObj.tripSegments.tripSegment[i];
			if (tripSegment.type && (listDivider == null || listDivider != tripSegment.type)) {
				item = $('<div class="grup" data-role="collapsible" data-mini="true" data-theme="b" data-content-theme="d" data-collapsed='+(i?'false':'true')+' onclick="$(\'.grup\').not(this).trigger(\'collapse\');"></div>');
				switch(tripSegment.type){
				case 'WALK':
					$("<h3>Marche</h3>").appendTo(item);
					titre="Marche";
					icon = "system/templates/application/myRiviera/img/" + tripSegment.type.toLowerCase()+ ".png";
					break;
				case 'CONNECTION':
					$("<h3>Connection</h3>").appendTo(item);
					break;
				case 'WAIT':
					$("<h3>Attendre</h3>").appendTo(item);
					$("<span style='font-size: 9pt; font-weight: lighter; padding:2px;'>Durée: "+tripSegment.duration+" min</span>").appendTo(item);
					break;
				default:
					$("<h3>"+tripSegment.transportMode.toLowerCase()+"</h3>").appendTo(item);
					titre=tripSegment.transportMode.toLowerCase();
					icon = "system/templates/application/myRiviera/img/" +tripSegment.transportMode.toLowerCase()+ ".png";
					break;
				}
				listDivider = tripSegment.type;
				item.appendTo($('#itineraire div:first'));
			}
			
			if (tripSegment.departurePoint && tripSegment.arrivalPoint){
				desc = $('<div style="font-size: 9pt; font-weight: lighter; padding:2px;"><a href="#" '+
						' onclick="focusOnPosition('+tripSegment.departurePoint.latitude+','+tripSegment.departurePoint.longitude+'); '+
						' updatePOIs('+tripSegment.departurePoint.latitude+','+tripSegment.departurePoint.longitude+',\''+icon+'\',\''+titre+'\',\''+i+'\');'+
						(mobile=="mobile"?' $(\'#itineraire\').trigger(\'collapse\');"':' map.panBy('+(-$("#itineraire").width())/2+',0);"')+
						' data-icon="search"></a></div>'
						);
				$('<span>'+(tripSegment.distance>0?'Distance: '+tripSegment.distance+' m':'Durée: '+tripSegment.duration+' min')+'</span>').appendTo(desc.find('a'));
				$('<p style="width: 90%;" id=poicomment_'+i+'>'+tripSegment.comment+'</p>').appendTo(desc);
				desc.appendTo(item);

				if (listDivider=="TRANSPORT"){
					routes.push({origin:new google.maps.LatLng(tripSegment.departurePoint.latitude, tripSegment.departurePoint.longitude),
						destination:new google.maps.LatLng(tripSegment.arrivalPoint.latitude, tripSegment.arrivalPoint.longitude),
						travelMode: google.maps.TravelMode.DRIVING
					});
				}else if(listDivider=="WALK"){
					routes.push({origin:new google.maps.LatLng(tripSegment.departurePoint.latitude, tripSegment.departurePoint.longitude),
						destination:new google.maps.LatLng(tripSegment.arrivalPoint.latitude, tripSegment.arrivalPoint.longitude),
						travelMode: google.maps.TravelMode.WALKING
					});
				}
			}			
		}
		
		$('#itineraire').find('div[data-role=collapsible]').collapsible({refresh:true});
		$('.grup').not(':eq(0)').trigger('collapse'); //todo: fix div are always expanded after the above line
		$(routes).each(function(i){
			var request = {
					origin: routes[i].origin,
					destination: routes[i].destination,
					travelMode: routes[i].travelMode
			};
			var directionsDisplay = new google.maps.DirectionsRenderer({
				map: map,
				suppressMarkers : true
			});
			directionsService.route(request, function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					result.routes[0].bounds = null; //to prevent automatic fitbounds
					directionsDisplay.setDirections(result);		
				}
			});
		});
		
	//}
}

/**
 * Print route (itineraire) from CityWay API
 * @param url
 */
function showTrip(start, end){
	
	focusOnCurrentPosition = false;
	
	// ZOOM ON THE TRIP
	startLatLng = $("#geocodeStartingPoint").val().split(",");
	endLatLng = $("#geocodeEndingPoint").val().split(",");
	
	bounds = new google.maps.LatLngBounds();
	bounds.extend(new google.maps.LatLng(startLatLng[0], startLatLng[1]));
	bounds.extend(new google.maps.LatLng(endLatLng[0], endLatLng[1]));
	map.fitBounds(bounds);
	
	// PRINT THE STARTING POINT
	addPOI(startLatLng[0], startLatLng[1], 'system/templates/application/myRiviera/img/start.png', "Départ", "<b>Lieu: </b>" + start, true);
	addPOI(endLatLng[0], endLatLng[1], 'system/templates/application/myRiviera/img/end.png', "Arrivée", "<b>Lieu: </b>" + end, true);
	
	// SHOW ITINERAIRE
	$("#itineraire").delay(1500).fadeIn("slow");
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
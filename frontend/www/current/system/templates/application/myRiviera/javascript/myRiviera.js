var filterArray = [];
var positionMarkers = [], prevMarker = null;// id of prevmarker
var poisMarkers = {
		"carf" : {
			markers : [],
			prevMarkers : null
		},
		"mymed" : {
			markers : [],
			prevMarkers : null
		},
}; // markers: list of gmap markers, prevmarker: id of markers last displayed
var isPersistent = true; // global behaviour, if we keep or not previous
//location MarkerS when we focus elsewhere

var poi;
var poiMem = {};
var poiIterator;

var currentSegmentID;

function initialize() {
	// INITIALIZE DASP
	setupDASP($("#userID").val(),
			$("#accessToken").val(), 
			$("#applicationName").val());
	
	// INITIALIZE DASP->MAP
	setupDASPMap($("#applicationName").val() + "Map");
	
	// autocompletes Google Maps Places API
	var autocompleteDepart = new google.maps.places.Autocomplete(document.getElementById('depart'));
	var autocompleteArrivee = new google.maps.places.Autocomplete(document.getElementById('arrivee'));
	autocompleteArrivee.bindTo('bounds', map);
	autocompleteDepart.bindTo('bounds', map);
	
	// resize the map canvas
	$("#" + $("#applicationName").val() + "Map").height($("body").height() - 45);
}

/**
 * Update the filter list for the POIs
 */
function updateFilter() {
	filterArray = $("#select-filter").val() || [];
}

/**
 * 
 * @param pois
 * @param type
 * @param index
 */
function otherMarker(pois, type, index) {
	if (!poisMarkers[type].markers[index]) { // create them if not exist
		poisMarkers[type].markers[index] = [];
		$.each(pois, function(i, poi) {
			value = $.parseJSON(poi.value);
			var marker = addMarker(value.latitude, value.longitude, value.icon,
					value.title, value.description);
			poisMarkers[type].markers[index].push(marker);
		});
	}
	if (!isPersistent) {
		if (poisMarkers[type].prevMarkers
				&& poisMarkers[type].prevMarkers != index) {
			t = poisMarkers[type].markers[poisMarkers[type].prevMarkers];
			for (i in t) {
				t[i].setMap(null);
			}
			poisMarkers[type].prevMarkers = index;
		}
	}
}

/**
 * 
 * @param latitude
 * @param longitude
 * @param icon
 * @param title
 * @param index
 */
function positionMarker(latitude, longitude, icon, title, index) {
	if (!index || (index && !positionMarkers[index])) { // create new marker
		var marker = addMarker(latitude, longitude, icon, title, $(
				"#poicomment_" + index).html());
		if (index) { // index argument is given, we store this marker for
			// future reuse
			positionMarkers[index] = marker;
			if (updatezoom) {
				updatezoom = false;
				map.setZoom(16);
			}// only once or it gets annoying
		}
	} else {// already existing, redrop it
		positionMarkers[index].setAnimation(google.maps.Animation.DROP);
		marker = positionMarkers[index];
		if (updatezoom) {
			updatezoom = false;
			map.setZoom(16);
		} // only once or it gets annoying
	}

	if (!isPersistent) {
		if (prevMarker && prevMarker != index) {
			marker[prevMarker].setMap(null);
			prevMarker = index;
		}
	}
}

/**
 * Add marker according to the myMed jSon format
 * 
 * @param elt
 */
function updateMarkers(latitude, longitude, icon, title, index) {
	
	// ADD THE MARKER OF THE CURRENT SEGMENT
	currentSegmentID = index;
	positionMarker(latitude, longitude, icon, title, index);
	
	// ADD THE MARKERs CORRESPONDING TO ALL THE POIs AROUND THE SEGMENT
	$.each($("#select-filter").val() || [], function(i, type) {
		if (filterArray.indexOf(type) < 0|| filterArray.length == ($("#select-filter").val() || []).length) {
			if ((pois = getMarkers(currentLatitude, currentLongitude, type, 500)).length != 0) {
				otherMarker(pois, type, currentSegmentID);
			}
		}
	});
}

/**
 * 
 * @param start
 * @param end
 * @param mobile
 */
function calcRoute(start, end, mobile) {

	currentType = null;
	icon = null;
	routes = [];
	collapsed = 0;

	for (i in result.ItineraryObj.tripSegments.tripSegment) {

		tripSegment = result.ItineraryObj.tripSegments.tripSegment[i];

		if (tripSegment.type && (currentType == null || currentType != tripSegment.type)) {

			item = $('<div data-role="collapsible" data-collapsed='+ (collapsed++ == 0 ? 'false' : 'true') + '></div>');

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

			currentType = tripSegment.type;
			item.appendTo($('#itineraireContent'));
		}

		if (tripSegment.departurePoint && tripSegment.arrivalPoint) {
			desc = $('<div style="font-size: 9pt; font-weight: lighter; padding:2px;"><a href="#" '
					+ ' onclick="'

					// FOCUS ON POSITION
					+ 'focusOnPosition('
					+ tripSegment.departurePoint.latitude
					+ ','
					+ tripSegment.departurePoint.longitude
					+ '); '

					// UPDATE MARKER
					+ ' updateMarkers('
					+ tripSegment.departurePoint.latitude
					+ ','
					+ tripSegment.departurePoint.longitude
					+ ',\''
					+ icon
					+ '\',\''
					+ titre
					+ '\',\''
					+ i
					+ '\');'
					+ (mobile == "mobile" ? ' $(\'#itineraire\').trigger(\'collapse\');"'
							: ' map.panBy(' + (-$("#itineraire").width()) / 2
							+ ',0);"')
							+ ' data-icon="search"></a></div>');
			$('<span>'+ (tripSegment.distance > 0 ? 'Distance: '
					+ tripSegment.distance + ' m' : 'Durée: '
						+ tripSegment.duration + ' min')
						+ '</span>').appendTo(desc.find('a'));
			$('<p style="width: 90%;" id=poicomment_' + i + '>'
					+ tripSegment.comment + '</p>').appendTo(desc);
			desc.appendTo(item);

			if (currentType == "TRANSPORT") {
				routes.push({
					origin : new google.maps.LatLng(
							tripSegment.departurePoint.latitude,
							tripSegment.departurePoint.longitude),
							destination : new google.maps.LatLng(
									tripSegment.arrivalPoint.latitude,
									tripSegment.arrivalPoint.longitude),
									travelMode : google.maps.TravelMode.DRIVING
				});
			} else if (currentType == "WALK") {
				routes.push({
					origin : new google.maps.LatLng(
							tripSegment.departurePoint.latitude,
							tripSegment.departurePoint.longitude),
							destination : new google.maps.LatLng(
									tripSegment.arrivalPoint.latitude,
									tripSegment.arrivalPoint.longitude),
									travelMode : google.maps.TravelMode.WALKING
				});
			}
		}
	}

	// REFRESH THE ITINERARY
	$('#itineraireContent').find('div[data-role=collapsible]').collapsible({
		refresh : true
	});

	// UI - ADD SEGMENT ON THE MAP - TODO MOVE THIS PART -> showTrip function
	$(routes).each(function(i) {
		var request = {
				origin : routes[i].origin,
				destination : routes[i].destination,
				travelMode : routes[i].travelMode
		};
		var directionsDisplay = new google.maps.DirectionsRenderer({
			map : map,
			suppressMarkers : true
		});
		directionsService.route(request, function(result, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				result.routes[0].bounds = null; // to prevent automatic
				// fitbounds
				directionsDisplay.setDirections(result);
			}
		});
	});

}

/**
 * Print route (itineraire) from CityWay API
 * 
 * @param url
 */
function myRivieraShowTrip(start, end, icon) {

	if (!map) {
		$("#myRivieraMap").height($("body").height() - 45);

		map = new google.maps.Map(document.getElementById("myRivieraMap"), {
			zoom : 16,
			center : new google.maps.LatLng(43.7, 7.27),
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});
		directionsDisplay = new google.maps.DirectionsRenderer();
		directionsDisplay.setMap(map);
	}

	focusOnCurrentPosition = false;

	// ZOOM ON THE TRIP
	startLatLng = $("#geocodeStartingPoint").val().split(",");
	endLatLng = $("#geocodeEndingPoint").val().split(",");

	bounds = new google.maps.LatLngBounds();
	bounds.extend(new google.maps.LatLng(startLatLng[0], startLatLng[1]));
	bounds.extend(new google.maps.LatLng(endLatLng[0], endLatLng[1]));
	map.fitBounds(bounds);

	// PRINT THE STARTING POINT
	addMarker(startLatLng[0], startLatLng[1],
			'system/templates/application/myRiviera/img/start.png', "Départ",
			"<b>Lieu: </b>" + start, true);
	addMarker(endLatLng[0], endLatLng[1],
			'system/templates/application/myRiviera/img/end.png', "Arrivée",
			"<b>Lieu: </b>" + end, true);

	// SHOW ITINERAIRE
	$("#itineraire").delay(1500).fadeIn("slow");
}

/**
 * Load the destination address from a contact
 * @param dest
 */
function changeDestination(dest) {
	picture = $("#select" + dest).val().split("&&")[0];
	address = $("#select" + dest).val().split("&&")[1];
	$("#" + dest).val(address);
	$("#" + dest).css("background-image", 'url(' + picture + ')');
}

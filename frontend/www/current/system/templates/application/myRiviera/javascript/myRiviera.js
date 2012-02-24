var filterArray = ["mymed", "carf"];
var pmarkers = [], fmarkers = []; //position markers and flag (start end) markers
var markers = {}; // all other markers
var directionsDisplays = [];

var isPersistent = false; // global behaviour, if we keep or not previous, location MarkerS when we focus elsewhere

var poi;
var poiMem = {};
var poiIterator;

var currentSegmentID=0, prevSegmentID=0;

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
	$("#myRivieraMap").height($("body").height() - ((!mobile)?0:$('body').find('div[data-role=header]').outerHeight()));

	//setup markers cache keys from options
	$("#select-filter option:not(:first)").each(function(){
	    markers[$(this).val()]=[];
	});

	// init filterArray
	for ( var i = 0; i < filterArray.length; i++) {
		$("#POIFilter").html('<input type="checkbox" name="' + filterArray[i] + '" id="' + filterArray[i] + '" class="custom" /><label for="' + filterArray[i] + '">' + filterArray[i] + '</label>');
	}
}

/**
 * Update the filter list for the POIs
 */
function updateFilter() {
	filterArray = $("#select-filter").val() || [];
}

/**
 * clear all markers from map, is necessary since 80496024d1dd2d8dc6, because we no longer refresh page (ajax)
 */
function clearAll(){
	
	for (var i=0; i<pmarkers.length && pmarkers[i]; i++)
		pmarkers[i].setMap(null);
	for (var i=0; i<fmarkers.length && fmarkers[i]; i++)
		fmarkers[i].setMap(null);
	for (key in markers)
		for (var i=0; i<markers[key] && markers[key][i]; i++)
			for (var j=0; j<markers[key][i]; j++)
				markers[key][i][j].setMap(null);
	for (var i=0; i<directionsDisplays.length; i++)
		directionsDisplays[i].setMap(null);
	
	pmarkers = [];
	fmarker = [];
	for (key in markers)
		markers[key] = [];
	directionsDisplays = [];
	
	$('#itineraireContent').html("");
	
}

/**
 * 
 * @param pois
 * @param type
 * @param index
 */
function otherMarkers(latitude, longitude, type, index) {
	if (!markers[type][index]) { // create them if not exist
		if ((pois = getMarkers(latitude, longitude, type, 500)).length != 0) {
			markers[type][index] = [];
			$.each(pois, function(i, poi) {
				value = $.parseJSON(poi.value);
				var marker = addMarker(value.latitude, value.longitude, value.icon,
						value.title, value.description);
				markers[type][index].push(marker);
			});
		}
	} else {// already existing, redrop them
		for ( var i = 0; i < markers[type][index].length; i++) {
			markers[type][index][i].setMap(map);
			markers[type][index][i].setAnimation(google.maps.Animation.DROP);
		}
	}

	if (!isPersistent && prevSegmentID && prevSegmentID != index
			&& markers[type][prevSegmentID]) {// clear previous step markers if !persistent
		for ( var i = 0; i < markers[type][prevSegmentID].length; i++) {
			markers[type][prevSegmentID][i].setMap(null);
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
	if (!index || (index && !pmarkers[index])) { // create new marker
		var marker = addMarker(latitude, longitude, icon, title, $('#itineraireContent').find('.ui-li p').eq(index).html());
		if (index) { // index argument is given, we store this marker for
			// future reuse
			pmarkers[index] = marker;
			if (updatezoom) {
				updatezoom = false;
				map.setZoom(16);
			}// only once or it gets annoying
		}
	} else {// already existing, redrop it
		pmarkers[index].setMap(map);
		pmarkers[index].setAnimation(google.maps.Animation.DROP);
		if (updatezoom) {
			updatezoom = false;
			map.setZoom(16);
		} // only once or it gets annoying
	}

	if (!isPersistent && prevSegmentID && prevSegmentID != index && pmarkers[prevSegmentID]) {// clear previous position marker if !persistent
		pmarkers[prevSegmentID].setMap(null);
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
	filterArray = $("#select-filter").val() || [];
	for ( var i = 0; i < filterArray.length; i++) {
		otherMarkers(latitude, longitude, filterArray[i], index);
	}
	len = $('#itineraireContent').find('.ui-li a').length;
	var c = eval(currentSegmentID);
	$('#next-step').attr('onclick', $('#itineraireContent').find('.ui-li a').eq(c<len-1?c+1:len-1).attr('onclick'));
	$('#prev-step').attr('onclick', $('#itineraireContent').find('.ui-li a').eq(c>0?c-1:0).attr('onclick'));
	
	prevSegmentID = index;
}

/**
 * 
 * @param start
 * @param end
 * @param mobile
 */
function calcRoute(json) {

	result = JSON.parse(json);
	
	if (result.ItineraryObj && result.ItineraryObj.Status.code == "0")
		calcRouteByCityway(result);
	else
		calcRouteByGoogle();
	
	myRivieraShowTrip($("#depart").val() || "Ma position",$("#arrivee").val());
}

function calcRouteByCityway(result){
	
	currentType = null;
	icon = null;
	routes = [];
	var collapsed = 0, i=0;
	$('#itineraire h3:first').find('.ui-btn-text').html($('#itineraire h3:first').find('.ui-btn-text').html().replace(/(Feuille de route)( \w+|)/, '$1 Cityway'));

	for (j in result.ItineraryObj.tripSegments.tripSegment) {

		tripSegment = result.ItineraryObj.tripSegments.tripSegment[j];
		if (tripSegment.type
				&& (currentType == null || currentType != tripSegment.type)) {

			item = $('<div data-role="collapsible" data-collapsed='+ (collapsed++ == 0 ? 'false' : 'true') + '></div>');

			switch (tripSegment.type) {
			case 'WALK':
				$("<h3>Marche</h3>").appendTo(item);
				titre = "Marche";
				icon = "system/templates/application/myRiviera/img/"
						+ tripSegment.type.toLowerCase() + ".png";
				break;
			case 'CONNECTION':
				$("<h3>Connection</h3>").appendTo(item);
				break;
			case 'WAIT':
				$("<h3>Attendre</h3>").appendTo(item);
				$("<span style='font-size: 9pt; font-weight: lighter; padding:2px;'>Durée: "
								+ tripSegment.duration + " min</span>").appendTo(item);
				i--; // no markers for Wait
				break;
			default:
				$("<h3>" + tripSegment.transportMode.toLowerCase() + "</h3>").appendTo(
						item);
				titre = tripSegment.transportMode.toLowerCase();
				icon = "system/templates/application/myRiviera/img/"
						+ tripSegment.transportMode.toLowerCase() + ".png";
				break;
			}

			currentType = tripSegment.type;
			$('<ul data-role="listview" data-inset="true"></ul>').appendTo(item);
			item.appendTo($('#itineraireContent'));
		}

		if (tripSegment.departurePoint && tripSegment.arrivalPoint) {
			desc = $('<li style="font-weight: lighter; padding-left:5px;"><a style="max-height:1em;" href="#" '
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
					+ i++
					+ '\');'
					+ (mobile == "mobile" ? ' $(\'#itineraire\').trigger(\'collapse\');"'
							: ' map.panBy(' + (-$("#itineraire").width()) / 2 + ',0);"')
					+ ' data-icon="search"></a></li>');
			$('<span>' + (tripSegment.distance > 0 ? 'Distance: ' + tripSegment.distance
									+ ' m' : 'Durée: ' + tripSegment.duration + ' min')
							+ '</span>').appendTo(desc.find('a'));
			$('<p style="width: 90%;">'
							+ tripSegment.comment + '</p>').appendTo(desc);
			desc.appendTo(item.find('ul'));

			if (currentType == "TRANSPORT" && [ 'AVION', 'BOAT', 'TER', 'TRAIN' ].indexOf(tripSegment.transportMode) < 0) {
				routes.push({
					origin : new google.maps.LatLng(tripSegment.departurePoint.latitude,
							tripSegment.departurePoint.longitude),
					destination : new google.maps.LatLng(
							tripSegment.arrivalPoint.latitude,
							tripSegment.arrivalPoint.longitude),
					travelMode : google.maps.TravelMode.DRIVING
				});
			} else if (currentType == "WALK") {
				routes.push({
					origin : new google.maps.LatLng(tripSegment.departurePoint.latitude,
							tripSegment.departurePoint.longitude),
					destination : new google.maps.LatLng(
							tripSegment.arrivalPoint.latitude,
							tripSegment.arrivalPoint.longitude),
					travelMode : google.maps.TravelMode.WALKING
				});
			}
		}
	}

	// create jquerymobile styled elmts
	$('.ui-page').trigger('create');

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
				result.routes[0].bounds = null; // to prevent automatic fitbounds
				directionsDisplay.setDirections(result);
				directionsDisplays.push(directionsDisplay); //for clearing them later
			}
		});
	});

}

function calcRouteByGoogle(){
	
	$('#itineraire h3:first').find('.ui-btn-text').html($('#itineraire h3:first').find('.ui-btn-text').html().replace(/(Feuille de route)( \w+|)/, '$1 GoogleMaps'));
	var request = {
			origin:geocodestart,
			destination:geocodeend,
			travelMode: google.maps.TravelMode.DRIVING
	};
	var directionsDisplay = new google.maps.DirectionsRenderer({
		map: map,
		suppressMarkers : true
	});
	
	icon = "system/templates/application/myRiviera/img/voiture.png";
	titre = "Voiture";
	var collapsed = 0;
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {

			$("<li data-role='list-divider'><span>"+result.routes[0].legs[0].steps[0].travel_mode.toLowerCase()+"</span></li>").appendTo($('#itineraire ul')); //ToDo should check if travel_mode changes in the following loop

			for (var i=0; i < result.routes[0].legs[0].steps.length; i++){
				if (i%5 == 0) { //all 5 results we groupe them by category
					item = $('<div data-role="collapsible" data-collapsed='+ (collapsed++ == 0 ? 'false' : 'true') + '></div>');
					$("<h3>Voiture</h3>").appendTo(item);
					$('<ul data-role="listview" data-inset="true"></ul>').appendTo(item);
					item.appendTo($('#itineraireContent'));
				}
				
				st = result.routes[0].legs[0].steps[i];	
				desc = $('<li style="font-weight: lighter; padding-left:5px;"><a style="max-height:1em;" href="#" '
						+ ' onclick="'
						// FOCUS ON POSITION
						+ 'focusOnPosition('
						+ st.start_location.lat()
						+ ','
						+ st.start_location.lng()
						+ '); '
						// UPDATE MARKER
						+ ' updateMarkers('
						+ st.start_location.lat()
						+ ','
						+ st.start_location.lng()
						+ ',\''
						+ icon
						+ '\',\''
						+ titre
						+ '\',\''
						+ i
						+ '\');'
						+ (mobile == "mobile" ? ' $(\'#itineraire\').trigger(\'collapse\');"'
								: ' map.panBy(' + (-$("#itineraire").width()) / 2 + ',0);"')
						+ ' data-icon="search"></a></li>');
				$('<span>Distance: '+st.distance.text+', durée: '+st.duration.text+'</span>').appendTo(desc.find('a'));
				$('<p style="width: 90%;">'
								+ st.instructions + '</p>').appendTo(desc);
				desc.appendTo(item.find('ul'));
				
			}
			
			// create jquerymobile styled elmts
			$('.ui-page').trigger('create');
			
			// UI - ADD SEGMENT ON THE MAP - TODO MOVE THIS PART -> showTrip function
			directionsDisplay.setDirections(result);
		}
	});
	
}



/**
 * Print route (itineraire) from CityWay API
 * 
 * @param url
 */
function myRivieraShowTrip(start, end, icon) {

	if (!map) {
		$("#myRivieraMap").height($("body").height() - ((!mobile)?0:$('body').find('div[data-role=header]').outerHeight()));

		map = new google.maps.Map(document.getElementById("myRivieraMap"), {
			zoom : 16,
			center : new google.maps.LatLng(43.7, 7.27),
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});
		directionsDisplay = new google.maps.DirectionsRenderer();
		directionsDisplay.setMap(map);
	}

	focusOnCurrentPosition = false;
	bounds = new google.maps.LatLngBounds();
	bounds.extend(geocodestart);
	bounds.extend(geocodeend);
	map.fitBounds(bounds);

	// PRINT THE STARTING POINT
	fmarkers.push(addMarker(geocodestart.lat(), geocodestart.lng(),
			'system/templates/application/myRiviera/img/start.png', "Départ",
			"<b>Lieu: </b>" + start));
	fmarkers.push(addMarker(geocodeend.lat(), geocodeend.lng(),
			'system/templates/application/myRiviera/img/end.png', "Arrivée",
			"<b>Lieu: </b>" + end));

	// SHOW ITINERAIRE
	$("#itineraire").delay(1500).fadeIn("slow");
	$("#next-prev").delay(1500).fadeIn("slow");
	$("#next-prev").css('top', $("body").height() - $("#next-prev").height() );
	$('#next-step').attr('onclick', $('#itineraireContent').find('.ui-li a').eq(1).attr('onclick'));
	$('#prev-step').attr('onclick', $('#itineraireContent').find('.ui-li a').eq(0).attr('onclick'));
}

/**
 * Load the desotherMarkertination address from a contact
 * 
 * @param dest
 */
function changeDestination(dest) {
	picture = $("#select" + dest).val().split("&&")[0];
	address = $("#select" + dest).val().split("&&")[1];
	$("#" + dest).val(address);
	$("#" + dest).css("background-image", 'url(' + picture + ')');
}

var geocoder = new google.maps.Geocoder(), geocoder2 = new google.maps.Geocoder();
var geocodestart=0, geocodeend, date;

function validateIt() {

	if ($("#depart").val() != "") {
		geocoder.geocode({'address' : $("#depart").val()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				geocodestart = results[0].geometry.location
				console.log(results[0].geometry.location);
				validateIt2();
			} else {
				console.log("Geocode was not successful for the following reason: "+ status);
				alert("Départ non valide");
			}
		});
	} else if (currentLatitude) {
		console.log("great");
		validateIt2();
	} else {
		console.log("Départ non rempli");
		alert("Départ non rempli");
		return;
	}

}

function validateIt2() {
	
	if ($("#arrivee").val() != "") {
		geocoder.geocode({'address' : $("#arrivee").val()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				geocodeend = results[0].geometry.location
				console.log(results[0].geometry.location);
				
				date = $('#select-year').val()+"-"+
				("0" + ($('#select-month').val())).slice(-2)+"-"+
				("0" + ($('#select-day').val())).slice(-2)+"_"+
				("0" + ($('#select-hour').val())).slice(-2)+"-"+
				("0" + ($('#select-minute').val())).slice(-2);

				geocodestart = geocodestart?geocodestart:new google.maps.LatLng(currentLatitude, currentLongitude);
				console.log(date);
				console.log(geocodestart);
				
				clearAll();
				
				$.ajax({
					type: "POST",
					url: "system/templates/application/myRiviera/handler/cityway.php",
					data: "startlat="+geocodestart.lat()+
								"&startlng="+geocodestart.lng()+
								"&endlat="+geocodeend.lat()+
								"&endlng="+geocodeend.lng()+
								"&date="+date,
					success: function(data){
						calcRoute(data);
					}
				});
				
				
			} else {
				console.log("Geocode was not successful for the following reason: "+ status);
				alert("Arrivée non valide");
			}
		});
	} else {
		console.log("Arrivée non remplie");
		alert("Arrivée non remplie");
		return;
	}
	
}


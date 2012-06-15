/* --------------------------------------------------------- */
/* Attributes */
/* --------------------------------------------------------- */
var filterArray = [];
var startmarker, endmarker, posmarker; // start and end markers
var pmarkers = []; // position markers
var markers = {}; // all other markers
var directionsDisplays = [];

var isPersistent = false; // global behaviour, if we keep or not previous,
//location MarkerS when we focus elsewhere
//is now $('#flip-per').val() set in option dialog

var poi;
var poiMem = {};
var poiIterator;

var steps = []; 
var currentSegmentID = 0, prevSegmentID = 0;
var autocompleteDepart, autocompleteArrivee, useautocompletion = true;

var start = null, end = null;
var circle;

var focusOnCurrentPosition = true;
var currentPositionMarker = null;

var refreshRoadMap = false;

var EndMarkerIcon = false;

/* --------------------------------------------------------- */
/* Initialize */
/* --------------------------------------------------------- */
function initialize() {
	

	// INITIALIZE DASP
	setupDASP($("#userID").val(), $("#accessToken").val(),
			$("#applicationName").val());

	// INITIALIZE DASP->MAP
	setupDASPMap($("#applicationName").val() + "Map", displayPosition,
			displayError, false);

	// autocompletes Google Maps Places API
	if (useautocompletion) {
		autocompleteDepart = new google.maps.places.Autocomplete(document
				.getElementById('depart'));
		autocompleteArrivee = new google.maps.places.Autocomplete(document
				.getElementById('arrivee'));
		autocompleteArrivee.bindTo('bounds', map);
		autocompleteDepart.bindTo('bounds', map);
	}

	// setup the marker for the itinerary
	startmarker = new google.maps.Marker({
		animation : google.maps.Animation.DROP,
		icon : 'system/templates/application/myRiviera/img/start.png',
		title : 'Départ\nMa position',
		zIndex : -1
	});

	endmarker = new google.maps.Marker({
		animation : google.maps.Animation.DROP,
		icon : 'system/templates/application/myRiviera/img/end.png',
		title : 'Arrivée',
		zIndex : -1
	});

	// resize the map on page change
	$("#Map").live("pageshow", function(event, ui) {
		google.maps.event.trigger(map, 'resize');

		// refocus on lastest position
		focusOnLatLng(currentPos);
	});

	// initialize the filter for the markers
	updateFilter();

	resizeMap();

}

function resizeMap() {
	$("#" + $("#applicationName").val() + "Map").height(
			$("body").height() + 100);
//	- $('body').find('div[data-role=header]').outerHeight());
}

/**
 * Géoloc - ok
 * 
 * @param position
 */
function displayPosition(position) {

	// reverse geocode
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
//	var latlng = new google.maps.LatLng(43.774481, 7.49754);  // menton
//	var latlng = new google.maps.LatLng(43.696036, 7.265592); // nice
//	var latlng = new google.maps.LatLng(43.580418, 7.125102); // antibes
//	var latlng = new google.maps.LatLng(43.87793, 7.449154);  // sospel
//	var latlng = new google.maps.LatLng(43.7904171, 7.607139);  // vintimille
//	var latlng = new google.maps.LatLng(43.757808, 7.473754);	// roquerbune
	
//   xhr.open( "GET", "index.php?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude,  true); 
//   xhr.send(null); 
	
	// Store the position into cassandra
	$.get("#", { latitude: latlng.lat(), longitude: latlng.lng() } );
	
	// add position marker
	if(currentPositionMarker == null) { // WATCH POSITION
		// create current position marker
		currentPositionMarker = new google.maps.Marker({
			map : map,
			animation : google.maps.Animation.DROP,
			icon : 'system/templates/application/myRiviera/img/position.png',
			title : 'Départ\nMa position',
			zIndex : -1
		});

		// focus on the position
		if (focusOnCurrentPosition) {
			focusOnLatLng(latlng || new google.maps.LatLng(43.774481, 7.49754));
			focusOnCurrentPosition = false;
		}
	}
	// set position of the currentPositionMarker
	currentPositionMarker.setPosition(latlng);

	// if the accuracy is good enough, print a circle to show the area
	// is use watchPosition instead of getCurrentPosition don't
	// forget to clear previous circle, using
	// circle.setMap(null)
	accuracy = position.coords.accuracy;
	if (accuracy) {
		if (circle) {
			circle.setCenter(latlng);
			circle.setRadius(accuracy);
		} else {
			circle = new google.maps.Circle({
				strokeColor : "#0000ff",
				strokeOpacity : 0.2,
				strokeWeight : 2,
				fillColor : "#0000ff",
				fillOpacity : (accuracy < 500) ? 0.1 : 0,
						map : map,
						center : latlng,
						radius : accuracy
			});
		}
	}

	// add the position to the popup
	$('#depart').attr("placeholder", "Ma position");
	
	// print the marker around me
	for ( var i = 0; i < filterArray.length; i++) {
		
		otherMarkers(0, filterArray[i], latlng.lat(), latlng.lng(), $('#slider-radius').val());
		
		/*pois = getMarkers2(latlng.lat(), latlng.lng(), filterArray[i], $('#slider-radius').val());
		pois.type = filterArray[i];
		$.each(pois, function(i, poi) {
			value = $.parseJSON(poi.value);
			var marker = addMarker(new google.maps.LatLng(value.latitude,
					value.longitude), 'system/templates/application/myRiviera/img/pois/' + pois.type + '.png', value.title,
					"<p>Type: 	" + pois.type + "</p>" + value.description);
			google.maps.event.addListener(marker, "click", function(e) {
				marker.ib.open(map, this);
			});
		});*/
	}
	hideLoadingBar();
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

/* --------------------------------------------------------- */
/* public methods */
/* --------------------------------------------------------- */
/**
 * Update the filter list for the POIs
 */
function updateFilter() {
	filterArray = [];
	$("#" + currentApplication + "Filter input:checked").each(function(index) {
		filterArray.push($(this).attr('id'));
		markers[$(this).attr('id')] = [];
	});
}

/**
 * clear all markers from map, is necessary since 80496024d1dd2d8dc6, because we
 * no longer refresh page (ajax)
 */
function clearAll() {

	clearMarkers();
	for ( var i = 0; i < directionsDisplays.length; i++)
		directionsDisplays[i].setMap(null);
	pmarkers = [];
	for (key in markers)
		markers[key] = [];
	directionsDisplays = [];
	currentSegmentID = 0, prevSegmentID = 0;
	$('#itineraireContent').html("");
	updatezoom = true;
}

function clearMarkers() {
	for ( var i = 0; i < pmarkers.length && pmarkers[i]; i++) {
		pmarkers[i].ib.close();
		pmarkers[i].setMap(null);
	}

	for (key in markers) {
		for ( var i=0 ; i < markers[key].length ; i++) {    
			if(markers[key][i]) {
				for ( var j=0 ; j< markers[key][i].length ; j++) {
					if(markers[key][i][j]) {
						console.log(markers[key][i][j]);
						markers[key][i][j].setMap(null);
					}
				}
			}
		}
	}

}

/**
 * 
 * @param pois
 * @param type
 * @param index
 */
function otherMarkers(index, type, lat, lon, rad) {
	if (!markers[type][index]) { // create them if not exist
		
		// Classic async POI get, seems faster, (loading bar hides fast, and pois are then get and dropped on the map)
		$.get('POI.php', {
			'application': $("#applicationName").val() + "Admin",
			'type': type,
			'latitude': lat || steps[index].position.lat(),
			'longitude': lon || steps[index].position.lng(),
			'radius': rad || $('#slider-radius').val()
		}, function(data){
			if ( (res = $.parseJSON(data)) != null){
				var pois = res.dataObject.pois;
				console.log("__"+res.dataObject.pois); // remove it after before pushing MASTER, console.log fail on IE
				markers[type][index] = [];
				$.each(pois, function(i, poi) {
					value = $.parseJSON(poi.value);
					id = poi.id;
					iconAvailable = $('#poiIcon').val().split(",");
					if(iconAvailable.contains(type + '.png')){
						icon = 'system/templates/application/myRiviera/img/pois/' + type + '.png';
					} else {
						icon = null;
					}
					var marker = addMarker(new google.maps.LatLng(value.latitude,
							value.longitude), icon, value.title,
							"<p>Type: 	" + type + "</p>" + value.description, null, false, id);
					google.maps.event.addListener(marker, "click", function(e) {
						marker.ib.open(map, this);
					});
					markers[type][index].push(marker);
				});
			}
			
		});

		// async: false POI get: warning async:false will be deprecated in next jQuery version
		// quote  "As of jQuery 1.8, the use of async: false is deprecated."
//		var res = $.ajax({
//			url : "POI.php",
//			data : {
//				'application': $("#applicationName").val() + "Admin",
//				'type': type,
//				'latitude': lat || latitude,
//				'longitude': lon || longitude,
//				'radius': rad || $('#slider-radius').val()
//			},
//			async : false
//		}).responseText;
//
//		if ((resJSON = $.parseJSON(res)) != null) {
//			var pois = resJSON.dataObject.pois;
//			//console.log("##"+resJSON.dataObject.pois); // remove it after before pushing MASTER, console.log fail on IE
//			markers[type][index] = [];
//			$.each(pois, function(i, poi) {
//				value = $.parseJSON(poi.value);
//				id = poi.id;
//				iconAvailable = $('#poiIcon').val().split(",");
//				if(iconAvailable.contains(type + '.png')){
//					icon = 'system/templates/application/myRiviera/img/pois/' + type + '.png';
//				} else {
//					icon = null;
//				}
//				var marker = addMarker(new google.maps.LatLng(value.latitude,
//						value.longitude), icon, value.title,
//						"<p>Type: 	" + type + "</p>" + value.description, null, false, id);
//				google.maps.event.addListener(marker, "click", function(e) {
//					marker.ib.open(map, this);
//				});
//				markers[type][index].push(marker);
//			});
//		}
		
		
	} else {// already existing, redrop them
		for ( var i = 0; i < markers[type][index].length; i++) {
			markers[type][index][i].setMap(map);
			markers[type][index][i].setAnimation(google.maps.Animation.DROP);
		}
	}

	if (!$('#flip-per').val() && prevSegmentID != index
			&& markers[type][prevSegmentID]) {// clear previous step markers
		// if !persistent
		for ( var i = 0; i < markers[type][prevSegmentID].length; i++) {
			markers[type][prevSegmentID][i].setMap(null);
			markers[type][prevSegmentID][i].ib.close();
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
function positionMarker(index) {
	if (!pmarkers[index]) { // create new marker
		var marker = addMarker(steps[index].position, steps[index].icon,
				steps[index].title, steps[index].desc);
		pmarkers[index] = marker;
		google.maps.event.addListener(marker, "click", function(e) {
			marker.ib.open(map, this);
		});
	} else {// already existing, redrop it
		pmarkers[index].setMap(map);
		pmarkers[index].setAnimation(google.maps.Animation.DROP);
	}

	if (updatezoom) {
		updatezoom = false;
		map.setZoom(16);
	} // only once or it gets annoying

	pmarkers[index].ib.open(map, pmarkers[index]);

	if (!$('#flip-per').val() && prevSegmentID != index
			&& pmarkers[prevSegmentID]) {// clear previous position marker if
		// !persistent
		pmarkers[prevSegmentID].setMap(null);
		pmarkers[prevSegmentID].ib.close();
	}
}

/**
 * Add marker according to the myMed jSon format
 * 
 * @param elt
 */
function updateMarkers(index) {

	showLoadingBar("Recherche de points d'interêts...");
	// FOCUS ON POSITION
	focusOnLatLng(steps[index].position);

	// ADD THE MARKER OF THE CURRENT SEGMENT
	positionMarker(index);

	currentSegmentID = index;
	if (index < steps.length - 1)
		$('#next-step').attr('onclick', 'updateMarkers(' + (index + 1) + ')');
	if (index > 0)
		$('#prev-step').attr('onclick', 'updateMarkers(' + (index - 1) + ')');

	// ADD THE MARKERs CORRESPONDING TO ALL THE POIs AROUND THE SEGMENT
	updateFilter();
	for ( var i = 0; i < filterArray.length; i++) {
		otherMarkers(index, filterArray[i]);
	}

	prevSegmentID = index;
	hideLoadingBar();
}

/**
 * 
 * @param start
 * @param end
 * @param mobile
 */
function calcRoute(json) {

	result = JSON.parse(json);
	// TODO CREATE SHOW TRIP FUNCTION!!
	// use to force to drow the trip
	if (result.ItineraryObj && result.ItineraryObj.Status.code == "0") {
		calcRouteByCityway(result);
	} else {
		calcRouteByGoogle(true);
	}

	refreshRoadMap = true;
	$("#roadMap").live("pageshow", function() {
		if(refreshRoadMap) {
			var $this = $(this),
			ul   = $('<ul id="itineraireContent" data-role="listview" data-theme="c" >'); // create the ul element

			$this.find("#itineraire ul").detach();  // remove the existing ul
			$this.find("#itineraire").append(ul);  // attach the new ul

			// append the dynamic list items (uses underscore library)

			if (result.ItineraryObj && result.ItineraryObj.Status.code == "0") {
				calcRouteByCityway(result);
				// call the listview constructor on the ul
				ul.listview({
					"inset": true
				});
			} else {
				calcRouteByGoogle(false);
			}
			refreshRoadMap = false;
		}
	});
	if (result.ItineraryObj && result.ItineraryObj.Status.code == "0") {
		myRivieraShowTrip($("#depart").val() || "Ma position", $("#arrivee").val());
	}
}

function calcRouteByCityway(result) {

	currentType = null;
	icon = null;
	routes = [];
//	$('#itineraire h3:first').find('.ui-btn-text').html(
//	$('#itineraire h3:first').find('.ui-btn-text').html().replace(
//	/(Feuille de route)( \w+|)/, '$1 Cityway'));
	startmarker.setTitle("Départ\n"
			+ result.ItineraryObj.originPoint.name[0]
			+ result.ItineraryObj.originPoint.name.substr(1).toLowerCase()
			+ ", "
			+ result.ItineraryObj.originPoint.localityName[0]
			+ result.ItineraryObj.originPoint.localityName.substr(1)
			.toLowerCase());
	endmarker.setTitle("Arrivée\n"
			+ result.ItineraryObj.destinationPoint.name[0]
			+ result.ItineraryObj.destinationPoint.name.substr(1).toLowerCase()
			+ ", "
			+ result.ItineraryObj.destinationPoint.localityName[0]
			+ result.ItineraryObj.destinationPoint.localityName.substr(1)
			.toLowerCase());

	for ( var i = 0; i < result.ItineraryObj.tripSegments.tripSegment.length; i++) {

		tripSegment = result.ItineraryObj.tripSegments.tripSegment[i];
		if (tripSegment.type
				&& (currentType == null || currentType != tripSegment.type)) {

			switch (tripSegment.type) {
			case 'WALK':
				titre = "Marche";
				icon = "system/templates/application/myRiviera/img/"
					+ tripSegment.type.toLowerCase() + ".png";
				break;
			case 'CONNECTION':
				titre = "Connection";
				icon = "system/templates/application/myRiviera/img/info.png";
				break;
			case 'WAIT':
				titre = "Attendre";
				icon = "system/templates/application/myRiviera/img/info.png";
				break;
			default:
				titre = tripSegment.transportMode.toLowerCase();
			icon = "system/templates/application/myRiviera/img/"
				+ tripSegment.transportMode.toLowerCase() + ".png";
			break;
			}

			if(currentType != tripSegment.type) {
				$('<li data-role="list-divider">' + titre + '</li>').appendTo($('#itineraireContent'));
				currentType = tripSegment.type;
			}
		}

		if (tripSegment.departurePoint) {
			steps[i] = {
					'position' : new google.maps.LatLng(
							tripSegment.departurePoint.latitude,
							tripSegment.departurePoint.longitude)
			};
		} else { // type = 'Attendre'
			steps[i] = {
					'position' : new google.maps.LatLng(
							result.ItineraryObj.tripSegments.tripSegment[i + 1].departurePoint.latitude,
							result.ItineraryObj.tripSegments.tripSegment[i + 1].departurePoint.longitude)
			};
		}
		steps[i]['icon'] = icon;
		steps[i]['title'] = titre;
		content1 = tripSegment.distance > 0 ? 'Distance: ' + tripSegment.distance + ' m' : 'Durée: ' + tripSegment.duration + ' min';
		content2 = (tripSegment.comment || '&nbsp;');
		steps[i]['desc'] = content1 + '<br />' + content2;
		
		desc = $('<li style="padding:5px;"><img alt="no picture" src="' + icon + '" /><a href="#Map" onclick="updateMarkers('+ i+ ');"><p style="position: relative; left: -16px;">' + content1 + '<br />' + content2 + '</p></a></li>');

		desc.appendTo($('#itineraireContent'));

		if (currentType == "TRANSPORT"
			&& [ 'AVION', 'BOAT', 'TER', 'TRAIN', 'TRAM' ]
		.indexOf(tripSegment.transportMode) < 0) {
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
				result.routes[0].bounds = null; // to prevent automatic
				// fitbounds
				directionsDisplay.setDirections(result);
				directionsDisplays.push(directionsDisplay); // for clearing them
				// later
			}
		});
	});
}

function calcRouteByGoogle(printTrip) {

//	$('#itineraire h3:first').find('.ui-btn-text').html(
//	$('#itineraire h3:first').find('.ui-btn-text').html().replace(
//	/(Feuille de route)( \w+|)/, '$1 GoogleMaps'));
	var request = {
			origin : startmarker.getPosition(),
			destination : endmarker.getPosition(),
			travelMode : google.maps.TravelMode.DRIVING
	};
	var directionsDisplay = new google.maps.DirectionsRenderer({
		map : map,
		suppressMarkers : true
	});

	icon = "system/templates/application/myRiviera/img/voiture.png";
	titre = "Voiture";
	directionsService
	.route(
			request,
			function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {

					startmarker.setTitle("Départ\n"
							+ result.routes[0].legs[0].start_address);
					endmarker.setTitle("Arrivée\n"
							+ result.routes[0].legs[0].end_address);

					for ( var i = 0; i < result.routes[0].legs[0].steps.length; i++) {
						if (i % 5 == 0) { // all 5 results we groupe
							$('<li data-role="list-divider">Voiture</li>').appendTo($('#itineraireContent'));
						}

						st = result.routes[0].legs[0].steps[i];

						content1 = 'Distance: ' + st.distance.text + ' (' + st.duration.text + ')';
						content2 = st.instructions;
						
						steps[i] = {
								'position' : st.start_location,
								'icon' : icon,
								'title' : titre,
								'desc' : content1 + '<br />' + content2
						};

						desc = $('<li style="padding:5px;"><img alt="no picture" src="' + icon + '" /><a href="#Map" onclick="updateMarkers('+ i+ ');"><p style="position: relative; left: -16px;">' + content1 + '<br />' + content2 + '</p></a></li>');
						desc.appendTo($('#itineraireContent'));
					}

					// create jquerymobile styled elmts
					$('.ui-page').trigger('create');
					$('#itineraireContent').listview({"inset": true });

					// UI - ADD SEGMENT ON THE MAP - TODO MOVE THIS PART
					// -> showTrip function
					if(printTrip) {
						directionsDisplay.setDirections(result);
						directionsDisplays.push(directionsDisplay);
						myRivieraShowTrip($("#depart").val() || "Ma position", $("#arrivee").val());
					}
				} else {
					alert("pas de resultat");
				}
			});

}

/**
 * Print route (itineraire) from CityWay API
 * 
 * @param url
 */
function myRivieraShowTrip(start, end, icon) {

	// Fit Bounds on the Map
	bounds = new google.maps.LatLngBounds();
	bounds.extend(startmarker.getPosition());
	bounds.extend(endmarker.getPosition());
	map.fitBounds(bounds);
	startmarker.setMap(map);
	endmarker.setMap(map);

	// SHOW ITINERAIRE
	$("#itineraire, #steps, #prev-step").delay(1000).fadeIn("slow");
	$('#next-step, #prev-step').attr('onclick', 'updateMarkers(0)');
	$('#next-step')
	.click(
			function() {
				$('#itineraireContent .ui-li a').eq(
						currentSegmentID + 1).closest(
						'[data-role="collapsible"]').trigger('expand');
			});
	$('#prev-step').click(
			function() {
				$('#itineraireContent .ui-li a')
				.eq((currentSegmentID || 1) - 1).closest(
				'[data-role="collapsible"]').trigger('expand');
			});

}

/**
 * Load the desotherMarkertination address from a contact
 * 
 * @param dest
 */
function changeDestination() {
	$("#arrivee").val($("#selectarrivee").val().split("&&")[1]);
	$("#arrivee").trigger('change');
}

function validateIt() {
	var geocoder = new google.maps.Geocoder();

	// change EndMarker icon
	if(EndMarkerIcon) {
		endmarker.setIcon($("#selectarrivee").val().split("&&")[0].replace("?type=large", ""));
		EndMarkerIcon = false;
	} else {
		endmarker.setIcon('system/templates/application/myRiviera/img/end.png');
	}
	// Validate the starting point
	geocoder.geocode({'address' : $('#depart').val()}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) { // use the position define by the user 
			start = results[0].geometry.location;
		} else if($('#depart').val() == "") { // use the current position
			start = currentPositionMarker.getPosition();
		} else { // Error with the position define by the user 
			alert("Départ non valide");
			return;
		}

		// Validate the ending point
		geocoder.geocode({'address' : $('#arrivee').val()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) { // use the position define by the user 

				end = results[0].geometry.location;

				startmarker.setPosition(start);
				endmarker.setPosition(end);

				var date = $('#select-year').val() + "-"
				+ ("0" + ($('#select-month').val())).slice(-2) + "-"
				+ ("0" + ($('#select-day').val())).slice(-2) + "_"
				+ ("0" + ($('#select-hour').val())).slice(-2) + "-"
				+ ("0" + ($('#select-minute').val())).slice(-2);

				var optimize = $("#cityway-search input:radio:checked").val();

				var tModes0 = $('#cityway-search input:checkbox:not(:checked)');
				var transitModes = 0;
				if (tModes0.length) {
					var tModes = [ 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
					               1, 1, 1 ]; //all 20 modes enabled
					for ( var i = 0; i < tModes0.length; i++) {
						tModes[tModes0[i].id.match(/\d+/)] = 0; //get the number in the id, that represent the cityway mode index
					}
					transitModes = tModes.join('');
				}

				clearAll();

				showLoadingBar("Calcul de l'itinéraire en cours...");
				$.ajax({
					type : "POST",
					url : "system/templates/application/myRiviera/handler/cityway.php",
					data : "startlng=" + startmarker.getPosition().lng() + "&startlat="
					+ startmarker.getPosition().lat() + "&endlng="
					+ endmarker.getPosition().lng() + "&endlat="
					+ endmarker.getPosition().lat()
					+ (optimize != "fastest" ? "&optimize=" + optimize : "")
					+ (transitModes ? "&transitModes=" + transitModes : "")
					+ "&date=" + date,
					success : function(data) {
						calcRoute(data);
						hideLoadingBar();
					},
					error : function(data) {
						hideLoadingBar();
					}
				});
			} else {
				alert("Arrivée non valide");
			}});
	});
}

function changeEndMarkerIcon() {
	EndMarkerIcon = true;
	
}

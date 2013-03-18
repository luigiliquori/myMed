var startmarker, endmarker, posmarker; // start and end markers
var start = null;
end = null;
var focusOnCurrentPosition = true;
var currentPositionMarker = null;
var steps = []; 
var directionsDisplays = [];

function initialize_map() {
	// INITIALIZE DASP
	setupDASP($("#userID").val(), $("#accessToken").val(),
			$("#applicationName").val());
	
	// INITIALIZE DASP->MAP
	setupDASPMap("myMap", displayPosition, displayError, false);

	// setup the marker for the itinerary
	startmarker = new google.maps.Marker({
		animation : google.maps.Animation.DROP,
		icon : 'img/start.png',
		title : 'Départ\nMa position',
		zIndex : -1
	});

	endmarker = new google.maps.Marker({
		animation : google.maps.Animation.DROP,
		icon : 'img/end.png',
		title : 'Arrivée',
		zIndex : -1
	});
	
}


function displayPosition(position) {
	
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

	
	// add position marker
	if(currentPositionMarker == null) {
		// create current position marker
		currentPositionMarker = addMarker(latlng, 
				'img/position.png',
				'Ma position',
				null, 
				google.maps.Animation.DROP);

		currentPositionMarker.setMap(map);
		// focus on the position
		if (focusOnCurrentPosition) {
			focusOnLatLng(latlng );
			focusOnCurrentPosition = false;
		}
	}
	

}

function displayError(error) {

}



function goingBack(address){
	
	// Google GeoCoder
	var geocoder = new google.maps.Geocoder();
	
	// starting point
	start = currentPositionMarker.getPosition();

	
	
	geocoder.geocode({'address' : address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {

			end = results[0].geometry.location;
		
			// Put the markers
			startmarker.setPosition(start);
			endmarker.setPosition(end);
			
			
			// date (in the exotic format of citiway API : AAAA-MM-DD_HH-MM, grrrr
			var d = (new Date()).toISOString();
			var date_array = d.split('T');
			var date = date_array[0];
			var time = date_array[1].split(':');
			var departureTime = date + '_' + time[0] + '-' + (parseInt(time[1])+2); // Add 2 minutes to make sure the API call does not fail 
			
			// fastest of lessChanges. I think lessChanges is the best for elderly
			var optimize = 'lessChanges';
			
			// type of transport
			/*
			 * Bus				X
			 * Metro			X
			 * Car				
			 * Train			X
			 * Tram				X
			 * TER				X
			 * Boat
			 * Trolley
			 * Taxibus
			 * Default			X
			 * TAD
			 * TAD_PMR
			 * Scolaire
			 * Avion
			 * Velobus
			 * Doublage
			 * Minibus			X
			 * Navette_Elec		X
			 * Funiculaire
			 * TGV
			 */
			transitModes ='11011100010000001100';
			
			showLoadingBar("Calcul de l'itinéraire en cours...");
			
			
			
			
			
			$.ajax({
				type : "POST",
				url : "cityway.php",
				data : "startlng=" + startmarker.getPosition().lng() + "&startlat="
				+ startmarker.getPosition().lat() + "&endlng="
				+ endmarker.getPosition().lng() + "&endlat="
				+ endmarker.getPosition().lat()
				+ "&optimize=" + optimize
				+ "&transitModes=" + transitModes
				+ "&date=" + departureTime,
				success : function(data) {
					calcRoute(data);
					hideLoadingBar();
				},
				error : function(data) {
//					hideLoadingBar();
				}
			});
			
		}
		else {
			alert("Geocoder failed due to : " + status);
		}
	});
	

	
	
	
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
		myRivieraShowTrip($("#depart").val() || "Ma position", $("#arrivee").val());
		calcRouteByCityway(result);
	} else {
		calcRouteByGoogle(true);
		//alert("Erreur API Cityway");
	}

	/*refreshRoadMap = true;
	//alert("changing page!");

	
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
				alert("Erreur API Cityway");
				//calcRouteByGoogle(false);
			}
			refreshRoadMap = false;
		}
	});
	if (result.ItineraryObj && result.ItineraryObj.Status.code == "0") {
		myRivieraShowTrip($("#depart").val() || "Ma position", $("#arrivee").val());
	}*/
}

function calcRouteByCityway(result) {

	currentType = null;
	icon = null;
	routes = [];
//	$('#itineraire h3:first').find('.ui-btn-text').html(
//	$('#itineraire h3:first').find('.ui-btn-text').html().replace(
//	/(Feuille de route)( \w+|)/, '$1 Cityway'));


	for ( var i = 0; i < result.ItineraryObj.tripSegments.tripSegment.length; i++) {
		
		tripSegment = result.ItineraryObj.tripSegments.tripSegment[i];
		
		if (tripSegment.type
				&& (currentType == null || currentType != tripSegment.type)) {

			switch (tripSegment.type) {
			case 'WALK':
				titre = "Marche";
				icon = "img/"
					+ tripSegment.type.toLowerCase() + ".png";
				break;
			case 'CONNECTION':
				titre = "Connection";
				icon = "img/info.png";
				break;
			case 'WAIT':
				titre = "Attendre";
				icon = "img/info.png";
				break;
			default:
				titre = tripSegment.transportMode.toLowerCase();
			icon = "img/"
				+ tripSegment.transportMode.toLowerCase() + ".png";
			break;
			}

			if(currentType != tripSegment.type) {
				$('<li data-role="list-divider" role="heading" >' + titre + '</li>').appendTo($('#itineraireContent'));
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
		
		desc = $('<li><img class="ui-li-icon"  alt="no picture" src="' + icon + '" /><p style="padding-left:1em">' + content1 + '<br />' + content2 + '</p></li>');

		desc.appendTo($('#itineraireContent'));


	}
	

	
	// create jquerymobile styled elmts
	$('.ui-page').trigger('create');
	$('#itineraireContent').listview("refresh", true);
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


function needHelp(){

	howmany = $('#howmany').val();

	// Google GeoCoder
	var geocoder = new google.maps.Geocoder();
	var pos = currentPositionMarker.getPosition();
	
	geocoder.geocode({'location' : pos }, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {

			address = results[0].formatted_address;

			for (i=1; i<=howmany; i++){
				
				numero = "#num" + i;
				setTimeout(function(){
									$.ajax({
										type : "POST",
										url : "sendmail.ajax.php",
										data : "email=" +$(numero)[0].innerHTML+ "&username="
										+ $('#username').val() + "&current_street=" 
										+ address + "&current_lat="
										+ currentPositionMarker.getPosition().lat() + "&current_lng="
										+ currentPositionMarker.getPosition().lng(),
										success : function(data) {
											retour = JSON.parse(data);
											//alert(retour.success);
											alert(data);
										},
										error : function(data) {
										}
									});
								}, 5000);
				
			}// for
		}//if
	});//geocoder
		
			
	
}// needHelp

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
	$('#next-step, #prev-step').attr('onclick', 'updateMarkers(1)');
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

						desc = $('<li><img style="margin: 10px;display: inline-block;" alt="no picture" src="' + icon + '" /><a href="#Map" style="width: 100%;display:inline-block;vertical-align: top;margin-top: 5px;" onclick="updateMarkers('+ (i+1)+ ');"><p style="white-space:normal;margin-left:15px;margin-right: 90px;">' + content1 + '<br />' + content2 + '</p></a><br /></li>');
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


var currentPosition = null;
var start = null;
var end = null;
var steps = []; 
var directionsDisplays = [];

function error(error){
	alert("erreur");
	alert(error);
}


function success(position){
	
	//alert("J'ai une position! ");
	
	var address = unescape(getUrlVars()["address"]);
	//alert("address = "+ address);

	var position = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	//alert("position : "+position);
	
	currentPosition = position;
	
	calculer_route(address, position);
	
}





function getCurrentLatLng(position){
	currentPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	//alert("=>" +currentPosition);
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function goingBack(){
	//alert("GoingBack(), getting position...");
	// Get the location
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(success, error);
	} else {
	  error('Geolocation not supported');
	}
}


function calculer_route(address, position){
	
	// Google GeoCoder
	var geocoder = new google.maps.Geocoder();
	
	
	//alert("Calculer_route : address : "+ address + " position : "+ position);
	
	// LatLng of the depart
	start = position;
	
	//alert("current position :" + start);
	
	
	geocoder.geocode({'address' : address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {

			// LatLng of the destination
			end = results[0].geometry.location;
		

			
			
			// date (in the exotic format of citiway API : AAAA-MM-DD_HH-MM, grrrr
			var d = (new Date()).toISOString();
			var date_array = d.split('T');
			var date = date_array[0];
			var time = date_array[1].split(':');
			var departureTime = date + '_' + time[0] + '-' + (parseInt(time[1])+2); // Add 2 minutes to make sure the API call does not fail 
			
			// fastest or lessChanges. I think lessChanges is the best for elderly
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
				data : "startlng=" + start.lng() + "&startlat="
				+ start.lat() + "&endlng="
				+ end.lng() + "&endlat="
				+ end.lat()
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
		calcRouteByCityway(result);
	} else {
		if (result == null){
			alert("resultat null");
		}
		//alert("result :"+result);
		//alert(JSON.stringify(result.ItineraryObj));
		//alert("result.ItineraryObj.Status.code = " +result.ItineraryObj.Status.code );
		alert("Erreur : Impossible de trouver un itineraire! (code erreur API Cityway :"+result.ItineraryObj.Status.code+")");
	}

	refreshRoadMap = true;
	//alert("changing page!");

	
	$("#roadSheet").live("pageshow", function() {
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
				alert("Erreur : Impossible de trouver un itineraire! (code erreur API Cityway :"+result.ItineraryObj.Status.code+")");
				//calcRouteByGoogle(false);
			}
			refreshRoadMap = false;
		}
	});
	/*if (result.ItineraryObj && result.ItineraryObj.Status.code == "0") {
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

function sendEmail(position){
	howmany = $('#howmany').val();

	// Google GeoCoder
	var geocoder = new google.maps.Geocoder();
	var position = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

	
	geocoder.geocode({'location' : position }, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {

			address = results[0].formatted_address;

			for (i=1; i<=howmany; i++){
				
				mail = "#mail" + i;
				email = $(mail)[0].innerHTML;
				setTimeout(function(){
									$.ajax({
										type : "POST",
										url : "sendmail.ajax.php",
										data : "email=" +$(mail)[0].innerHTML+ "&username="
										+ $('#username').val() + "&current_street=" 
										+ address + "&current_lat="
										+ position.lat() + "&current_lng="
										+ position.lng(),
										success : function(data) {
											if (data)
											alert("erreur lors de l'envoi du mail : "+ data);
										},
										error : function(data) {
											alert("erreur lors de l'envoi du mail : "+ data);
										}
									});
								}, 5000);
				
			}// for
			
			alert("E-mail envoyés!");
		}//if
	});//geocoder
	
}
function sendEmailsAlerts(){

	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(sendEmail, error);
	} else {
	  error('Geolocation not supported');
	}
}// needHelp



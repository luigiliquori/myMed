/*
 * Copyright 2012 INRIA 

 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/* --------------------------------------------------------- */
/* Attributes */
/* --------------------------------------------------------- */
//General IDs
var userID;
var accessToken;
var currentApplication;

//Google Map features
var directionsService;
var map;

var currentPos; //user's google.maps.LatLng location 
var currentPlace; //user's String position

var pos; //position in the itinary, should be moved to myRiviera

/**
 * @TODO rename that file map.js? 
 */

/* --------------------------------------------------------- */
/* Setup Lib */
/* --------------------------------------------------------- */
/**
 * @param id,
 *            id of the current user id
 * @param at,
 *            accessToken
 * @param app,
 *            the current application id
 */
function setupDASP(id, at, app) {

	// MEMORIZE CURRENT IDs FOR JS CALL
	userID = id;
	accessToken = at;
	currentApplication = app;

}

/* --------------------------------------------------------- */
/* GeoPosition methods */
/* --------------------------------------------------------- */
/**
 * init the map lib for the map define by mapId
 * 
 * @param mapID,
 *            id of the map
 */
function setupDASPMap(mapID, displayPosition, displayError, watchPosition) {

	if (!map) {

		directionsDisplay = new google.maps.DirectionsRenderer();
		directionsService = new google.maps.DirectionsService();

		// init Map
		map = new google.maps.Map(document.getElementById(mapID), {
			zoom : 16,
			center : new google.maps.LatLng(43.7, 7.27),
			//disableDefaultUI: true,
			mapTypeControl: false,
			panControl: false,
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});

		// geolocaliseUser
		if (navigator.geolocation) {
			if(watchPosition) {
				navigator.geolocation.watchPosition(displayPosition, displayError,
						{ timeout: 60000, maximumAge: 0});
			} else {
				navigator.geolocation.getCurrentPosition(displayPosition, displayError,
						{enableHighAccuracy : true, timeout: 5000, maximumAge: 0});
			}
		} else {
			alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
		}
	}
}

/**
 * Zoom on the position define by the latitude and the longitude
 * 
 * @param latitude
 * @param longitude
 */
function focusOnPosition(latitude, longitude) {

	// memorize the position
	currentLatitude = latitude;
	currentLongitude = longitude;

	// focus on the position
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	map.setCenter(myLatlng);

}

/**
 * Zoom on position
 * 
 * @param google.maps.LatLng
 */
function focusOnLatLng(position) {

	// make sure position not null
	position= position || currentPos || new google.maps.LatLng(43.696036, 7.265592);

	// memorize the position in the cityway itinary // should be moved to riviera specific
	pos = position;

	// focus on the position
	map.setCenter(position);
}

/* --------------------------------------------------------- */
/* Marker methods */
/* --------------------------------------------------------- */


/**
 * Add a marker on the map
 * 
 * @param latitude
 * @param longitude
 * @param icon
 * @param title
 * @param description
 * @returns {google.maps.Marker}
 */
function addMarker(position, icon, title, description, animation, isDraggable, id, Address, Email, Link, IdMedia, Altitude) {

	var marker = new google.maps.Marker({
		draggable : isDraggable,
		position : position,
		animation: animation,
		icon : icon,
		title : title,
		map : map
	});

	if (description){
		var boxText = document.createElement("div");
		boxText.style.cssText = "background-color: white; padding: 5px; border: thin black solid; border-radius: 5px; color: black;";
		boxText.innerHTML = 
			'<h4 style=" margin-top: 2px; margin-bottom: 2px;">' + title + '</h4>';
		if(IdMedia) {
			if (IdMedia.split("://").length > 1) {
				boxText.innerHTML += 
					'<a href="' + $.trim(IdMedia) + '" target="blank"><img alt="' + IdMedia + '" src="' + $.trim(IdMedia) + '" width="270px" /></a><br/>';
			} else {
				medias = IdMedia.split(".jpg");
				if(medias.length > 1) {
					for(var i=0 ; i<medias.length-1 ; i++) {
						boxText.innerHTML += 
							'<a href="img/pois/pictures/' + $.trim(medias[i]) + '.jpg" target="blank"><img alt="' + $.trim(medias[i]) + '" src="img/pois/pictures/' + $.trim(medias[i]) + '.jpg" width="270px" /></a><br/>';
					}
				} 
			}
		}
		boxText.innerHTML += '<p style="text-align: justify; font-size: 12px;margin: 0;">' + description+ '</p>';
		if(Address) {
			boxText.innerHTML += '<p><b>Adresse</b> : ' + Address + '<br />';
			if(Altitude) {
				boxText.innerHTML += '<b>Altitude</b> : ' + Altitude;
			}
			boxText.innerHTML +=  '</p>';
		}
		if(Email || Link) {
			boxText.innerHTML += '<hr />';
		}
		if(Email) {
			boxText.innerHTML +=  '<a href="mailto:' + $.trim(Email) + '">Email</a>';
		}
		if(Link){
			boxText.innerHTML +=  '<a href="http://' + $.trim(Link.replace(/http:\/\//g, '')) + '" target="blank">Plus d\'infos</a>';
		}

		var myOptions = {
			content: boxText
			,disableAutoPan: false
			,maxWidth: 0
			,zIndex: null
			,boxStyle: { 
				opacity: 0.95
				,width: "280px"
			}
			,closeBoxMargin: "2px 2px 2px 2px"
			,closeBoxURL: "img/close.png"
			,infoBoxClearance: new google.maps.Size(1, 1)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: false
		};

		marker.ib = new InfoBox(myOptions);
	}

	return marker;
}

/* --------------------------------------------------------- */
/* Trip layer methods */
/* --------------------------------------------------------- */
/**
 * Print the trip on the current map
 * 
 * @param trip -
 *            jSon trip - Google Based
 */
function showTrip(trip) {
	// TODO
}

/* --------------------------------------------------------- */
/* Other methods */
/* --------------------------------------------------------- */
/**
 * Publish an Ajax DASP request, according to the parameters setup in the from
 * 
 * @param formID,
 *            the form unique id
 */
function publishDASPRequest(formID) {

	// store the current date if needed
	if ($("#getDate") != null) {
		$("#getDate").val(getFormatedDate);
	}

	$.ajax({
		type : 'POST',
		url : "#",
		data : $("#" + formID).serialize(),
		async : true
	});
}

function getPosition(){
	
	//"../../backend/PositionRequestHandler"

	$.getJSON("../../lib/dasp/ajax/Position.php", function(data) {

		if (data.status == 200) {
			currentPos = new google.maps.LatLng(data.dataObject.position.latitude,
					data.dataObject.position.longitude);
			currentPlace = data.dataObject.position.formattedAddress;

			if (focusOnCurrentPosition) {
				focusOnLatLng(currentPos);
				focusOnCurrentPosition = false;
			}
		}

	});
}

function updatePosition(params){	
	$.ajax({
		//url: "../../backend/PositionRequestHandler",
		url: "../../../lib/dasp/ajax/Position.php",
		type: "POST",
		data: params,
		success: function(data){
			//console.log(data);
		}
	});
}
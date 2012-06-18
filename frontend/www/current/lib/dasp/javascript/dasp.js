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

var currentPos; //user's geolocation
var currentPlace; //user's String position
var pos; //position in the itinary

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

	// EXTENDS Array - add contains method
	/*Array.prototype.contains = function(aValue) {
		if (this.toString().match(aValue))
			return true;
	};*/
	// you do array.indexOf(item) > 0

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
		showLoadingBar("chargement de la carte..."); 
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

function displayPosition(position) {

	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
//	var latlng = new google.maps.LatLng(43.774481, 7.49754);  // menton
//	var latlng = new google.maps.LatLng(43.696036, 7.265592); // nice
//	var latlng = new google.maps.LatLng(43.580418, 7.125102); // antibes
//	var latlng = new google.maps.LatLng(43.87793, 7.449154);  // sospel
//	var latlng = new google.maps.LatLng(43.7904171, 7.607139);  // vintimille
//	var latlng = new google.maps.LatLng(43.757808, 7.473754);	// roquerbune
	
//   xhr.open( "GET", "index.php?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude,  true); 
//   xhr.send(null); 
	

	//$.get("#", { latitude: latlng.lat(), longitude: latlng.lng() } );
	
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
	
	//give hand to app
	displayPos(latlng);
}


function displayError(error) {
	//another try
	if (error.code == 3) {
		navigator.geolocation.getCurrentPosition(displayPosition, displayError);
	} else {
		//gives hand to app
		displayErr();
	}
	
}


//google.maps.Map.prototype.markers = new Array();
//
//google.maps.Map.prototype.addMarker = function(marker) {
//    this.markers[this.markers.length] = marker;
//};
//
//google.maps.Map.prototype.getMarkers = function() {
//    return this.markers
//};
//
//google.maps.Map.prototype.clearMarkers = function() {
//    for(var i=0; i<this.markers.length; i++){
//        this.markers[i].setMap(null);
//    }
//    this.markers = new Array();
//};


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

	// memorize the position
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
function addMarker(position, icon, title, description, animation, isDraggable, id) {
	
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
			'<h4 style=" margin-top: 2px; margin-bottom: 2px;">' + title + '</h4>' +
			'<p style="text-align: justify; font-size: 12px;margin: 0;">' + description+ '</p>';

		var myOptions = {
				content: boxText,
				pixelOffset: new google.maps.Size(-200, -10),
				boxStyle: {
					opacity: 0.9,
					width: "180px"
				}
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
	var params = {
		'userID': $("#userID").val(),
		'accessToken': $("#accessToken").val(),
		'code': 1
	};
	
	$.ajax({
		url: "../../backend/PositionRequestHandler",
		data: params,
		dataType: "json",
		success: function(data){
			currentPos = new google.maps.LatLng(
				data.dataObject.position.latitude,
				data.dataObject.position.longitude
			);
			currentPlace = data.dataObject.position.formattedAddress;
		}
	});
}

function updatePosition(data){	
	$.ajax({
		url: "../../backend/PositionRequestHandler",
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data){
			
		}
	});
}
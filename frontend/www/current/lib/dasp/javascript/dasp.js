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
	Array.prototype.contains = function(aValue) {
		if (this.toString().match(aValue))
			return true;
	};

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
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});

		// geolocaliseUser
		if (navigator.geolocation) {
			if(watchPosition) {
				navigator.geolocation.watchPosition(displayPosition, displayError,
						{enableHighAccuracy : true, timeout: 5000, maximumAge: 0});
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

	// memorize the position
	currentPos = position;

	// focus on the position
	map.setCenter(position);
}

/* --------------------------------------------------------- */
/* Marker methods */
/* --------------------------------------------------------- */

/**
 * Get the complete list of Marker around the position according to the radius
 * 
 * @param latitude
 *            latitude in degree
 * @param longitude
 *            longitude in degree
 * @param radius
 *            radius in meter
 */
function getMarkers(latitude, longitude, type, radius) {

	var result = new Array();
	args = "code=1";
	args += "&application=" + $("#applicationName").val();
	args += "&type=" + type;
	args += "&latitude=" + latitude;
	args += "&longitude=" + longitude;
	args += "&radius=" + radius;
	args += "&accessToken=" + $("#accessToken").val();

	var res = $.ajax({
		url : "backend/POIRequestHandler",
		dataType : 'json',
		data : args,
		async : false
	}).responseText;

	if ((resJSON = $.parseJSON(res)) != null) {
		if ((pois = $.parseJSON(resJSON.data.pois)) != null) {
			result = result.concat(pois);
		}
	}
	return result;
}

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
function addMarker(position, icon, title, description, animation, isDraggable) {
	
	if(animation == null){
		animation = google.maps.Animation.DROP;
	}
	var marker = new google.maps.Marker({
		draggable : isDraggable,
		animation : animation,
		position : position,
		icon : icon,
		title : title,
		map : map
	});

	var boxText = document.createElement("div");
	boxText.style.cssText = "background-color: white; padding: 5px; border: thin black solid; border-radius: 5px;";
	boxText.innerHTML = "<h4 style=' margin-top: 2px; margin-bottom: 2px;'>" + title + "</h4><p style='text-align: justify; font-size: 12px;margin: 0;'>" + description+ "</p>";

	var myOptions = {
			content: boxText,
			pixelOffset: new google.maps.Size(-200, -10),
			boxStyle: {
				opacity: 0.9,
				width: "180px"
			}
	};
	marker.ib = new InfoBox(myOptions);

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

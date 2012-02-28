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
var directionsService = new google.maps.DirectionsService();
var map;
var currentLatitude, currentLongitude, accuracy;
var focusOnCurrentPosition = true;
var updatezoom = true;
var marker, circle;

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
function setupDASPMap(mapID) {

	if (!map) {
		directionsDisplay = new google.maps.DirectionsRenderer();

		// init Map
		map = new google.maps.Map(document.getElementById(mapID), {
			zoom : 16,
			center : new google.maps.LatLng(43.7, 7.27),
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});

		// geolocaliseUser
		if (navigator.geolocation) {
			navigator.geolocation.watchPosition(displayPosition, displayError,
					{enableHighAccuracy : true, timeout: 5000, maximumAge: 0});
		} else {
			alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
		}
	}
}

/**
 * Géoloc - ok
 * 
 * @param position
 */
function displayPosition(position) {
	currentLatitude = position.coords.latitude;
	currentLongitude = position.coords.longitude;
	accuracy = position.coords.accuracy;
	$('#departGeo').val(currentLatitude + '&' + currentLongitude);
	$('#depart').attr("placeholder", "Ma position");

	// ADD POSITION Marker
	var latlng = new google.maps.LatLng(currentLatitude, currentLongitude);
	if (marker) {
		marker.setPosition(latlng);
		marker.setAnimation(google.maps.Animation.BOUNCE);
	} else {
		marker = new google.maps.Marker({
			animation : google.maps.Animation.BOUNCE,
			position : latlng,
			icon : 'system/templates/application/myRiviera/img/position.png',
			map : map
		});
	}

	// if the accuracy is good enough, print a circle to show the area
	// is use watchPosition instead of getCurrentPosition don't
	// forget to clear previous circle, using
	// circle.setMap(null)
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
				fillOpacity : (accuracy < 400) ? 0.1 : 0,
						map : map,
						center : latlng,
						radius : accuracy
			});
		}

	}

	// focus on the position on show the POIs around
	if (focusOnCurrentPosition) {
		focusOnPosition(currentLatitude, currentLongitude);
	}
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

/**
 * Zoom on the position define by the latitude and the longitude
 * 
 * @param latitude
 * @param longitude
 */
function focusOnPosition(latitude, longitude) {

	// memorize the current position
	currentLatitude = latitude;
	currentLongitude = longitude;

	// focus on the position
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	map.setCenter(myLatlng);
	window.scrollTo(0, 0);

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
function addMarker(latitude, longitude, icon, title, description) {
	var marker = new google.maps.Marker({
		animation : google.maps.Animation.DROP,
		position : new google.maps.LatLng(latitude, longitude),
		title : title,
		icon : icon,
		map : map
	});
	var contentString = "<div class='poiContent'>"
		+ "<h2 class='poiFirstHeading'>" + title + "</h2>"
		+ "<div class='poiBodyContent'>" + description + "</div>"
		+ "</div>";
	var infowindow = new google.maps.InfoWindow({
		content : contentString
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map, this);
	});
	google.maps.event.addListener(map, 'click', function() {
		infowindow.close();
	});
	return marker;
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

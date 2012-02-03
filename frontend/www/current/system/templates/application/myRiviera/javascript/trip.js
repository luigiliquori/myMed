var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;
var poi;
var poiMem = {};
var poiIterator;

/**
 * Initialize the application
 */
function initialize() {
	directionsDisplay =  new google.maps.DirectionsRenderer();

	// resize the map canvas
	$("#myRivieraMap").height($("body").height() - 45);

	map = new google.maps.Map(document.getElementById("myRivieraMap"), {
		zoom: 13,
		center: new google.maps.LatLng(43.7, 7.27),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay.setMap(map);

	// GEOLOC
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			focusOnPosition(position.coords.latitude, position.coords.longitude);
			}, 
			null, 
			{enableHighAccuracy:true});
	} else {
		alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
	}
}

/**
 * Zoom on a position
 * @param position
 */
function focusOnPosition(latitude, longitude){
	// ZOOM
	map.panTo(new google.maps.LatLng(latitude, longitude));
	
	// ADD POSITION Marker
	myMarkerImage = 'system/templates/application/myRiviera/img/position.png';
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
		icon: myMarkerImage,
		map: map
	});
}

/**
 * Add marker according to the myMed jSon format
 * @param elt
 */
function addMarker(latitude, longitude, icon, title, description){
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	var marker = new google.maps.Marker({
		animation: google.maps.Animation.DROP,
		position: myLatlng,
		title: title,
		icon: icon
	});
	marker.setMap(map);
	
	var contentString = 
		"<div class='poiContent'>" +
			"<h2 class='poiFirstHeading'>" + title + "</h2>"+
			"<div class='poiBodyContent'>" +
				+ description +
			"</div>" +	
		"</div>";
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

/**
 * Print route (itineraire) from Google API
 * In case of cityway errors
 */
function calcRouteFromGoogle(start, end) {
	var request = {
			origin:$("#start").val(),
			destination:$("#end").val(),
			travelMode: google.maps.TravelMode.DRIVING
	};
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(result);
		}
	});
}

/**
 * Print route (itineraire) from CityWay API
 * @param url
 */
function calcRouteFromCityWay(url){
	alert(url);
	var KmlObject = new google.maps.KmlLayer(url);
	KmlObject.setMap(map);
}

/**
 * Load the destination address from a contact
 * @param dest
 */
function changeDestination(dest){
	picture = (document.getElementById("select" + dest).value + "").split("&&")[0];
	address = (document.getElementById("select" + dest).value + "").split("&&")[1];

	if ($("#select"+dest).val()!="") {
		document.getElementById(dest).value = address;
		document.getElementById(dest + "picture").src = picture;
	} else {
		document.getElementById(dest).value = "Ma destination";
		document.getElementById(dest + "picture").src ="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png";
	}
}

/**
 * Set the current time on the date field
 */
function setTime(){
	var d=new Date();
	$("#date").val( d.getHours()+":"+("0" + (d.getMinutes())).slice(-2)+" le "+ d.getDate()+'/'+d.getMonth() +'/'+d.getFullYear());

}

/* ****************** */
/* DEPRECATED METHODs */
/* ****************** */
/**
 * Focus on a Trip Segment (CityWay API)
 * @param id
 * @deprecated
 */
function focusOn(id){
	latitude = document.getElementById(id + "_latitude").value;
	longitude = document.getElementById(id + "_longitude").value;
	poiJson = document.getElementById(id + "_poi").value;
	poi = JSON.parse(poiJson);

	// add marker
	poiIterator = 0;
	for (var i =0; i < poi.length; i++) {
		setTimeout(function() {
			addMarker();
		}, i * 200);
	}

	// FOCUS ON
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	map.setCenter(myLatlng);
	map.setZoom(16);

	// ZOOM IN
	window.scrollTo(0,0);
}

/**
 * Add the marker around the current Trip Segment
 * @deprecated
 */
function addMarker(){
	// POI
	if(!poiMem[poi[poiIterator].id]){

		var myLatlng = new google.maps.LatLng(poi[poiIterator].latitude, poi[poiIterator].longitude);

		var myMarkerImage = "";
		if(poi[poiIterator].category == "4"){
			myMarkerImage = 'system/templates/application/myRiviera/img/velobleu.png';
		} else if (poi[poiIterator].category == "5"){
			myMarkerImage = 'system/templates/application/myRiviera/img/veloparc.png';
		} else if (poi[poiIterator].category == "10"){
			myMarkerImage = 'system/templates/application/myRiviera/img/info.png';
		} else if (poi[poiIterator].category == "1" || poi[poiIterator].category == "2" || poi[poiIterator].category == "3" || poi[poiIterator].category == "8"){
			myMarkerImage = 'system/templates/application/myRiviera/img/lieu.png';
		} else {
			myMarkerImage = 'system/templates/application/myRiviera/img/trip.png';
		}
		var marker = new google.maps.Marker({
			animation: google.maps.Animation.DROP,
			position: myLatlng,
			title:poi[poiIterator].name,
			icon: myMarkerImage
		});
		marker.setMap(map);

		var contentString = 
			"<div class='poiContent'>" +
			"<h2 class='poiFirstHeading'>" + poi[poiIterator].name.toLowerCase() + "</h2>"+
			"<div class='poiBodyContent'>" +
			"<p> type: " + poi[poiIterator].type.toLowerCase() + "<br />" +
			"ville: " + poi[poiIterator].localityName.toLowerCase() +
			"</div>" +	
			"</div>";
		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});

		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});

		poiMem[poi[poiIterator].id] = true;
	}
	poiIterator++;
}


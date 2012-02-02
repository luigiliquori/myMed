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
	if (navigator.geolocation)
		navigator.geolocation.getCurrentPosition(focusOnPosition, null, {enableHighAccuracy:true});
	else
	    alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
}

/**
 * Zoom on a position
 * @param position
 */
function focusOnPosition(position){
	  map.panTo(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
	  myMarkerImage = 'system/templates/application/myRiviera/img/position.png';
	  var marker = new google.maps.Marker({
	    position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
	    icon: myMarkerImage,
	    map: map
	  });
}

/**
 * Print route (itineraire) from Google API
 * In case of cityway errors
 */
function calcRoute() {
	var start = document.getElementById("start").value;
	var end = document.getElementById("end").value;
	var request = {
			origin:start,
			destination:end,
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
function addKMLLayerFromURL(url){
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
	document.getElementById(dest).value = address;
	document.getElementById(dest + "picture").src = picture;
}

/**
 * Add marker according to the myMed jSon format
 * @param elt
 */
function addMarkerFromMymedJsonFormat(elt){
	if((resJSON = $.parseJSON($("#" + elt).val())) != null) {
		$.each(resJSON, function(i, item) {
			var myLatlng = new google.maps.LatLng(item.latitude, item.longitude);
			var marker = new google.maps.Marker({
				position: myLatlng,
				title: item.title,
				icon: item.icon
			});
			marker.setMap(map);
		});
	} else {
		alert("parse error!");
	}
}

/**
 * Add marker according to the CARF jSon format
 * @param elt
 * @deprecated
 */
function addMarkerFromCARFJsonFormat(elt){
	if((resJSON = $.parseJSON($("#" + elt).val())) != null) {
		$.each(resJSON, function(i, item) {
			$.each(item.features, function(j, feature) {
				coord = feature.geometry.coordinates;
				description = feature.properties.ADRESSE;
				var myLatlng = new google.maps.LatLng(coord[1], coord[0]);
				var marker = new google.maps.Marker({
					position: myLatlng,
					title:description,
				});
				marker.setMap(map);
			});
		});
	} else {
		alert("parse error!");
	}
}

/**
 * Focus on a Trip Segment (CityWay API)
 * @param id
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


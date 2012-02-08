var filter = new Array();

function addFilter(name) {
	filter[name] = true;
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
 * Get the complete list of POI around the position according to the radius
 * @param latitude
 * 		latitude in degree
 * @param longitude
 * 		longitude in degree	
 * @param radius
 * 		radius in meter
 */
function getPOIs(latitude, longitude, radius) {
	
	args = "code=1";
	args += "&application=" + $("#applicationName").val();
	args += "&type=mymed";	// TODO use the filter array
	args += "&latitude=" + latitude;
	args += "&longitude=" + longitude;
	args += "&radius=" + radius;
	args += "&accessToken=" + $("#accessToken").val();
	
	alert(args);
	
	var res = $.ajax({
		url : "backend/POIRequestHandler",
		dataType : 'json',
		data : args,
		async : false
	}).responseText;

	alert(res);
	if((resJSON = $.parseJSON(res)) != null) {
		alert("latitude: " + latitude);
		alert("longitude: " + longitude);
	}
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
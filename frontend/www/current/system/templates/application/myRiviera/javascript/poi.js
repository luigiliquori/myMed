var filterArray = new Array();
var markerArray = new Array();

Array.prototype.contains = function(aValue){
	if( this.toString().match(aValue)) return true;
};

function updateFilter(){
	filterArray = new Array();
	$.each($("#select-filter").val(), function(i, item) {
		filterArray[i] = item;
	});
}

/**
 * Zoom on a position
 * @param position
 */
function focusOnPosition(latitude, longitude, accuracy){
//	latitude = 43.774309;
//	longitude = 7.49246;

	// ADD POSITION Marker
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	myMarkerImage = 'system/templates/application/myRiviera/img/position.png';
	var marker = new google.maps.Marker({
		position: myLatlng,
		icon: myMarkerImage,
		map: map
	});

	// if the accuracy is good enough, print a circle to show the area
	if (accuracy && accuracy<1500){
		var circle = new google.maps.Circle({
			strokeColor: "#0000ff",
			strokeOpacity: 0.2,
			strokeWeight: 2,
			fillColor: "#0000ff",
			fillOpacity: 0.1,
			map: map,
			center: myLatlng,
			radius: accuracy
		});
	}
	
	// Clear the markerArray
	clearPOIs();

	// ADD THE POI AROUND THE POSITION
	if((pois = getPOIs(latitude, longitude, 500)).length != 0){
		$.each(pois, function(i, poi) {
			value = $.parseJSON(poi.value);
			addPOI(value.latitude,  value.longitude, value.icon, value.title, value.title);
		});
	}

	// ZOOM IN
	map.setCenter(myLatlng);
	map.setZoom(16);
	window.scrollTo(0,0);
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

	var result = new Array();
	$.each(filterArray, function(i, type) {
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

		if((resJSON = $.parseJSON(res)) != null) {
			if((pois = $.parseJSON(resJSON.data.pois)) != null) {
				result = result.concat(pois);
			}
		}
	});
	return result;
}

/**
 * Add marker according to the myMed jSon format
 * @param elt
 */
function addPOI(latitude, longitude, icon, title, description){
	var myLatlng = new google.maps.LatLng(latitude, longitude);
	var marker = new google.maps.Marker({
		animation: google.maps.Animation.DROP,
		position: myLatlng,
		title: title,
		icon: icon
	});
	markerArray.push(marker);
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

function clearPOIs(){
    for (var i = 0; i < markerArray.length; i++ ) {
    	markerArray[i].setMap(null);
    }
    markerArray = new Array();
}

/* ****************** */
/* DEPRECATED METHODs */
/* ****************** */
/**
 * Focus on a Trip Segment (CityWay API)
 * @param id
 * @deprecated
 */
function focusOn(id, latitude, longitude){
	// new Method
	focusOnPosition(latitude, longitude);

	// add marker only for cityway
	// TODO Remove this part
	if (filterArray.contains("cityway")) {
		poiJson = $("#" + id + "_poi").val();
		poi = JSON.parse(poiJson);
		var poiIterator = 0;
		for (var i =0; i < poi.length; i++) {
			setTimeout(function() {
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
			}, i * 200);
		}
	}
}


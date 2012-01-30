var map;
var poi;
var poiMem = {};
var poiIterator;

function changeDestination(dest){
	picture = (document.getElementById("select" + dest).value + "").split("&&")[0];
	address = (document.getElementById("select" + dest).value + "").split("&&")[1];
	document.getElementById(dest).value = address;
	document.getElementById(dest + "picture").src = picture;
}

function showMap(url, lat, long) {
	var myLatlng = new google.maps.LatLng(lat, long);
	var myOptions = {
			zoom: 16,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

function addKMLLayerFromURL(url){
	var KmlObject = new google.maps.KmlLayer(url);
	KmlObject.setMap(map);
}

function addMarkerFromJson(elt){
	if((resJSON = $.parseJSON($("#" + elt).val())) != null) {
		$.each(resJSON, function(i, item) {
			var myLatlng = new google.maps.LatLng(item.latitude, item.longitude);
			var marker = new google.maps.Marker({
				position: myLatlng,
				title:item.title,
				icon: item.icon
			});
			marker.setMap(map);
		});
	} else {
		alert("parse error!");
	}
}

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

/**
 * Automatically sets the date and time inputs
 */
$(function() {
	// Handler for .ready() called.
	var d=new Date();
	
	$("#date").val(d.getDate()+'/'+(d.getMonth() + 1)+'/'+d.getFullYear());
	//English format $("#date").val(d.getFullYear()+"-"+("0" + (d.getMonth() + 1)).slice(-2)+"-"+("0" + (d.getDate())).slice(-2));
	
	// 24H or 12H format:
	$("#time").val(("0" + (d.getHours())).slice(-2)+":"+("0" + (d.getMinutes())).slice(-2));
	//$("#time").val(d.getHours()>12?d.getHours()-12+":"+("0" + (d.getMinutes())).slice(-2)+" PM":d.getHours()+":"+("0" + (d.getMinutes())).slice(-2)+" AM");
});


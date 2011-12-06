var map;

function initialize() {
}

function changeDestination(dest){
	picture = (document.getElementById("select" + dest).value + "").split("&&")[0];
	address = (document.getElementById("select" + dest).value + "").split("&&")[1];
	document.getElementById(dest).value = address;
	document.getElementById(dest + "2").value = address;
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
	
	// TRIP
	alert(url);
	var ctaLayer = new google.maps.KmlLayer(url);
	ctaLayer.setMap(map);
}

function focusOn(lat, long){
	// POI
//	var poiLayer = new google.maps.KmlLayer("http://mymed2.sophia.inria.fr/mobile/system/templates/application/myRiviera/kml/Cinema.kml");
//	poiLayer.setMap(map);
	
	// FOCUS ON
	var myLatlng = new google.maps.LatLng(lat, long);
	map.setCenter(myLatlng);
	map.setZoom(19);
	
	// ZOOM IN
	window.scrollTo(0,0);
}

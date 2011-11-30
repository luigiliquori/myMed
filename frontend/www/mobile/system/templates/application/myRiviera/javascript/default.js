var maCarte;
var geoXml;

function initialize() {
}

function changeDestination(dest){
	picture = (document.getElementById("select" + dest).value + "").split("&&")[0];
	address = (document.getElementById("select" + dest).value + "").split("&&")[1];
	document.getElementById(dest).value = address;
	document.getElementById(dest + "2").value = address;
	document.getElementById(dest + "picture").src = picture;
}

function updateMapWithKML(url, lat, long) {
	  if (GBrowserIsCompatible()) {         
		  geoXml = new GGeoXml(url);         
		  map = new GMap2(document.getElementById("map_canvas"));        
		  map.setCenter(new GLatLng(lat, long), 16);         
		  map.setUIToDefault();        
		  map.addOverlay(geoXml);       
	}else{
		alert('Désolé, mais votre navigateur n\'est pas compatible avec Google Maps');
	}
}


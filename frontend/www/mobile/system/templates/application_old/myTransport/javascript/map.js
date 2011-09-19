function initialize() {
	map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 13,
        center: new google.maps.LatLng(43.7, 7.27),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });
    setTimeout("geoloc()", 1500);
}

function geoloc(){
	if (navigator.geolocation)
		var watchId = navigator.geolocation.watchPosition(successCallback, null, {enableHighAccuracy:true});
	else
	    alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
}
	 
function successCallback(position){
	  map.panTo(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
	  var marker = new google.maps.Marker({
	    position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
	    map: map
	  });
}
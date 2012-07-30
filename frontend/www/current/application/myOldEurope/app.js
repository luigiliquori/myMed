
var map, marker, infowindow;

$(function() {  

	/*map = new google.maps.Map(document.getElementById("map_canvas"), {
		zoom: 11,
		center: new google.maps.LatLng(43.6, 7.11),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	
	marker = new google.maps.Marker({
        position: new google.maps.LatLng(43.6, 7.11),
        map: map,
        draggable: true
    });
	
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(displayPosition, displayError,
				{enableHighAccuracy : true, timeout: 5000, maximumAge: 0});
	}
	
	$("#Map").live("pageshow", function() {
		google.maps.event.trigger(map, 'resize');
		$.get('position.php', function(data) {
			var res = JSON.parse(data);
			var latlng = new google.maps.LatLng(res.dataObject.position.latitude, res.dataObject.position.longitude);
			var content = user.name+"<img src="+(user.profilePicture || "http://graph.facebook.com//picture?type=large")+" width='60' style='float:right;' />";
			if (!infowindow){
				infowindow = new google.maps.InfoWindow({
				    content: content
				});
				infowindow.open(map,marker);
			}else{
				infowindow.setContent(content);
			}
			
			map.setCenter(latlng);
			marker.setPosition(latlng);
		});
	});*/
});

function displayPosition(position) {

	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	
	// Store the position into cassandra
	$.get("position.php", { latitude: latlng.lat(), longitude: latlng.lng(), formatted_address: "Here" } );

	marker.setPosition(latlng);
	map.setCenter(latlng);
	if (position.coords.accuracy) {
	}

}
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


function showComment() {
	$('#CommentButton').fadeOut('slow');
    $('#Comments').fadeIn('slow');
    $('#Commenter').fadeIn('slow');
}
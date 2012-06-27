
var map, marker, infowindow;

var isSub = 3;

var application="myEurope", predicate="";

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

$("#Search").live("pageshow", function() {
	var queryString = decodeURIComponent(location.search.substring(1));
	var predicateString = decodeURIComponent(location.search.substring(1)).toLowerCase(); 
	var key = queryString.split('&')[0].split('=');
	var keypred = predicateString.split('&')[0].split('=');
	var tags=[];
	if (key.length > 1){ //q should be the only query key
	    tags = keypred[1].split(/\+/g);
	    $('#searchBar').val(key[1].replace(/\+/g, " "));
	    tags = array_unique(tags).sort();
	}
	isSub = 3;
	$.get('../../lib/dasp/ajax/Subscribe', { 
		code: 1, 
		application: application ,
		predicate: tags.join("") 
	}, function(data){
		var res = JSON.parse(data);
		if (res.sub)
			isSub = 0;
		$('#flip-a').val(isSub).slider('refresh');
		console.log("__ "+isSub+" "+tags);
	});
	//console.log("__ "+isSub+" "+tags);
});


$('#buying_slider_min').live("change", function() {
	var min = parseInt($(this).val());
	var max = parseInt($('#buying_slider_max').val());
	if (min > max) {
		$(this).val(max);
		$(this).slider('refresh');
	}
});

$('#buying_slider_max').live("change", function() {
	var min = parseInt($('#buying_slider_min').val());
	var max = parseInt($(this).val());

	if (min > max) {
		$(this).val(min);
		$(this).slider('refresh');
	}
});

$('#tagSearch').live("keyup", function(event) {
	if (event.keyCode == 13) {
		$('#searchForm').submit();
	}
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
	$('#CommentButton').fadeOut('fast');
    $('#Comments').fadeIn('slow');
    $('#Commenter').fadeIn('slow');
}

function array_unique (inputArr) {
	//credits php.js
	
    var key = '',
        tmp_arr2 = [],
        val = '';

    var __array_search = function (needle, haystack) {
        var fkey = '';
        for (fkey in haystack) {
            if (haystack.hasOwnProperty(fkey)) {
                if ((haystack[fkey] + '') === (needle + '')) {
                    return fkey;
                }
            }
        }
        return false;
    };

    for (key in inputArr) {
        if (inputArr.hasOwnProperty(key)) {
            val = inputArr[key];
            if (false === __array_search(val, tmp_arr2) && val!="") {
                tmp_arr2[key] = val;
            }
        }
    }

    return tmp_arr2;
}


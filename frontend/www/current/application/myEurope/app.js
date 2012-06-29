
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

	var params = queryString.split('&');
	
	var tags=[];
	for (i in params){
		if (params[i].split('=')[1] == "on"){
			tags.push(params[i].split('=')[0]);
		}	
	}
	tags.sort();
	
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

$("#Update").live("pagecreate", function() {
	$('#updateForm').ajaxForm( { beforeSubmit: validate } ); 
});

function validate(formData, jqForm, options) { 
    // jqForm is a jQuery object which wraps the form DOM element 
    // 
    // To validate, we can access the DOM elements directly and return true 
    // only if the values of both the username and password fields evaluate 
    // to true 
 
    var form = jqForm[0]; 
    if (!form.email.value || !form.password.value) { 
        alert('Veuillez remplir votre email et mot de passe'); 
        return false; 
    }
    if ( form.oldPassword.value !== form.password.value) { 
        alert('mot de passe non identiques'); 
        return false; 
    } 
}


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


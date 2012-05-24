
var results; // current resultList stored

var details; // current details stored, (necessary for delete)

var user; //user connected

var map, marker, infowindow;

//ToDo set a keyspace for these vars, for no possible conflicts

$(function() {  
	// check if a session is already opened
	session();
	
	map = new google.maps.Map(document.getElementById("map_canvas"), {
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
	});
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

/*function getResults(){
	var values = {};
	$.each($('#findForm').serializeArray(), function(i, field) {
	    values[field.name] = field.value;
	});
	$.ajax({
		url: 'find.php',
		data: values,
		success: function(data) {
			location.replace("#Results"+location.href.substr(location.href.indexOf('?')));
			var res = JSON.parse(data);
			results = res.dataObject.results;
			refreshResults();
		},
		error: function(data) {
			alert('error');
			var res = JSON.parse(data);
		}
	});
}*/

function refreshResults(){
	$('#Results [data-role=listview]').html('');
	for (var i in results){
		var item = results[i], row = '';
		row+= '<div class="row"><img src="';
		row+= (item.publisherProfilePicture)?item.publisherProfilePicture:'http://graph.facebook.com//picture?type=large';
		row+= '" width="60" height="60" /></div><div class="row" style="padding-left: 10px;">';
		row+= item.publisherName+' ';
		if (item.end)
			row+= ' le '+item.end;
		if (item.data)
			row+= ' à '+item.data;

		$('#Results [data-role=listview]').append('<li><a href="#Detail"'+item.id+' onclick="getDetail('+i/*item.id*/+');">'+row+'</div></a></li>');
	}
	$('#Results [data-role=listview]').listview('refresh');
		
	
}

function updateUser(){
	$('.homeButton').each(function(i, v) {
		if ($(v).find('.ui-btn-text').length)
			$(v).find('.ui-btn-text').html('<u>'+user.name+'</u>'); //button already loaded by jqm
		else
			$(v).html('<u>'+user.name+'</u>'); //button not loaded yet by jqm
	});
	$('.homeButton').attr('href', '#Profile');
	$('#Profile form').children().not('a').remove();
	$('#Profile form').prepend("<p>Reputation: "+(user.reputation||30)+"</p>");
	$('#Profile form').prepend("<img src="+(user.profilePicture || "http://graph.facebook.com//picture?type=large")+" width='300'/><br />");
	$('#Profile form').prepend("<h2>"+user.name + "</h2>");
}

/*function getDetail(index){
	var item = results[index];
	$.ajax({
		url: 'getDetail.php',
		data: {
			'user' : item.publisherID,
            'predicate' : item.predicate,
            'application' : 'myTemplate'
		},
		success: function(data) {
			var res = JSON.parse(data), content='', text='...';
			details = res.dataObject.details;
			for (var i in details){
				var pred = details[i];
				if (pred.key == 'text') {
					text = pred.value;
				}
			}
			details['index'] = index;
			content+= '<img src="';
			content+= (item.publisherProfilePicture)?item.publisherProfilePicture:'http://graph.facebook.com//picture?type=large';
			content+= '" width="180" style="float:right;" />',
			content+= '<b>Nom</b>: <span style="left-margin:5px; color:DarkBlue; font-size:160%;">'+item.publisherName+'</span>';
			content+= '<br />';
			content+= '<b>Prédicat</b>: '+item.predicate;
			content+= '<br /><br />';
			content+= '<b>Texte</b>:';
			content+= '<div id="detailstext">'+text+'</div>';
			
			$('#Detail [data-role=content] div').html('');
			$('#Detail [data-role=content] div').append(content);
		},
		error: function(data) {
			alert('error');
			var res = JSON.parse(data);
		}		
	});
}*/

function connect(){
	var params = {};
	$.each($('#loginForm').serializeArray(), function(i, field) {
		params[field.name] = field.value;
	});
	$.ajax({
		url: 'authentication.php',
		data: params,
		success: function(data) {
			var res = JSON.parse(data);
			if (res.status==200){
				user= res.dataObject.user;
				updateUser();
			}else {
				alert('error: '+res.description);
			}
		}
	});
}

function register(){
	var params = {};
	$.each($('#registerForm').serializeArray(), function(i, field) {
		params[field.name] = field.value;
	});
	$.ajax({
		url: 'registration.php',
		data: params,
		success: function(data) {
			var res = JSON.parse(data);
			if (res.status==200){
				alert('Veuillez valider votre compte par mail');
			}else {
				alert('error: '+res.description);
			}
		}
	});
}
function updateProfile(){
	$('#textinputu1').val(user.firstName);
	$('#textinputu2').val(user.lastName);
	$('#textinputu3').val(user.email);
	$('#textinputu6').val(user.birthday);
	$('#textinputu7').val(user.profilePicture);
}
function update(){
	var params = {};
	$.each($('#updateForm').serializeArray(), function(i, field) {
		params[field.name] = field.value;
	});
	$.ajax({
		url: 'update.php',
		type: 'post',
		data: params,
		success: function(data) {
			var res = JSON.parse(data);
			if (res.status==200){
        		user= res.dataObject.profile;
        		updateUser();
			}else{
				alert('error: '+res.description);
			}
		}
	});
}

function session(){
	$.ajax({
		url: 'session.php',
		success: function(data) {
			var res = JSON.parse(data);
        	if (res.status){
        		user= res.dataObject.user;
        		updateUser();
			}else if (res.status != 200){
        		//
			}
		}
	});
}

function disconnect(){
	$.ajax({
		url: 'deconnect.php',
		success: function(data) {
			location.href="#";
			location.reload(0);
		}
	});
}

function _delete(){
	// add additional params to the form, (ontologyID's)
	var params = {};
	$.each($('#deleteForm').serializeArray(), function(i, field) {
		params[field.name] = field.value;
	});
	
	$.ajax({
		url: 'delete.php',
		type: 'post',
		data: params,
		success: function(data) {
			//results.splice(index, 1);
			//refreshResults();
			var res = JSON.parse(data);
			if (res.status==200){
				history.back();
			}else{
				alert('error: '+res.description);
			}
		}
	});
}

function comment(){
	// add additional params to the form, (ontologyID's)
	var params = {};
	$.each($('#commentForm').serializeArray(), function(i, field) {
		params[field.name] = field.value;
	});
	var d=new Date(), me = this;
	params['end'] = d.toJSON();
	
	$.ajax({
		url: 'publish.php',
		type: 'post',
		data: params,
		success: function(data) {
			//results.splice(index, 1);
			//refreshResults();
			$("#commentButton span span").text("envoyé");
			$("#commentButton").addClass('ui-disabled');
			var res = JSON.parse(data);
			if (res.status==200){
				$(this).addClass('ui-disabled');
				alert('commented: '+params.data+'\n'+res.description);
			}else{
				alert('error: '+res.description);
			}
		}
	});
}

function __delete(){ //comment
	// add additional params to the form, (ontologyID's)
	var params = {};
	$.each($('#deleteCommentForm').serializeArray(), function(i, field) {
		params[field.name] = field.value;
	});
	
	$.ajax({
		url: 'delete.php',
		type: 'post',
		data: params,
		success: function(data) {
			//results.splice(index, 1);
			//refreshResults();
			var res = JSON.parse(data);
			if (res.status==200){
				location.reload();
			}else{
				alert('error: '+res.description);
			}
		}
	});
}

function ___delete(i){ //subscription
	var params = {};
	$.each($('#deleteSubscriptionForm'+i).serializeArray(), function(i, field) {
		params[field.name] = field.value;
	});
	
	$.ajax({
		url: 'unsubscribe.php',
		type: 'post',
		data: params,
		success: function(data) {
			//results.splice(index, 1);
			//refreshResults();
			var res = JSON.parse(data);
			if (res.status==200){
				console.log(data);
				//location.reload();
			}else{
				alert('error: '+res.description);
			}
		}
	});
}

function interaction(consumer, producer, value){
	$('#interaction').fadeOut("slow", function() { 
		$(this).children().remove();
		if (consumer == producer){
			$(this).html("<span style='color: red;'>Vous ne pouvez pas voter pour vous-même</span>").fadeIn("slow"); 
		}
		else{
			$(this).html("<span style='color: green;'>"+(value?"+1":"-1")+"</span>").fadeIn("slow");
			//here send an ajax interaction
		}
		
	});
}
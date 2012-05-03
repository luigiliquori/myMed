
var results; // current resultList stored

var details = {}; // current details stored, (necessary for delete)

var user; //user connected

$(function() {  
	// check if a session is already opened
	session();
	
});  

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
	var values = {};
	$.each($('#loginForm').serializeArray(), function(i, field) {
	    values[field.name] = field.value;
	});
	$.ajax({
		url: 'authentication.php',
		data: values,
		success: function(data) {
			var res = JSON.parse(data);
			if (res.dataObject.user){
				user= res.dataObject.user;
				$('.homeButton').each(function(i, v) {
        			if ($(v).find('.ui-btn-text').length)
        				$(v).find('.ui-btn-text').html('<u>'+user.name+'</u>'); //button already loaded by jqm
        			else
        				$(v).html('<u>'+user.name+'</u>'); //button not loaded yet by jqm
        		});
				$('.homeButton').attr('href', '#Profile');
			}
		},
		error: function(data) {
			alert('error');
			var res = JSON.parse(data);
		}
	});
}

function register(){
	var values = {};
	$.each($('#registerForm').serializeArray(), function(i, field) {
	    values[field.name] = field.value;
	});
	$.ajax({
		url: 'registration.php',
		data: values,
		success: function(data) {
			var res = JSON.parse(data);
			alert('Validez votre compte par mail');
		},
		error: function(data) {
			alert('error');
			var res = JSON.parse(data);
		}
	});
}

function session(){
	$.ajax({
		url: 'session.php',
		success: function(data) {
			var res = JSON.parse(data);
        	if (res.dataObject){
        		user= res.dataObject.user;
        		$('.homeButton').each(function(i, v) {
        			if ($(v).find('.ui-btn-text').length)
        				$(v).find('.ui-btn-text').html('<u>'+user.name+'</u>'); //button already loaded by jqm
        			else
        				$(v).html('<u>'+user.name+'</u>'); //button not loaded yet by jqm
        		});
				$('.homeButton').attr('href', '#Profile');
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

function _delete(index){
	// add additional params to the form, (ontologyID's)
	details['application'] = 'myTemplate';
	details['_keyword'] = 0;
	details['_data'] = 1;
	details['_enum'] = 2;
	details['_end'] = 3;

	console.log(results[index].publisherID);
	console.log(user.id);
	if (results[index].publisherID != user.id){
		details['user'] = results[index].publisherID;
	}
	
	$.ajax({
		url: 'delete.php',
		type: 'POST',
		data: details,
		success: function(data) {
			//results.splice(index, 1);
			//refreshResults();
			history.back();
		},
		error: function(data) {
			alert('error');
			var res = JSON.parse(data);
		}
	});
}
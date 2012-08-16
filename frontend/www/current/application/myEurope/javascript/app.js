
var isSub = 3;

var application="myEurope", predicate="";

/*$('label').click(function(e){
    e.stopPropagation()
});/*

/*$("#Search").live("pageshow", function() {
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
});*/

/*$("#Home").live("pagecreate", function() {

});
*/

$("#search").live("pagecreate", function() {
	$('#checkbox-all').click(function() {
		if ($(this).is(':checked')){
			$('#themecheckboxes input[type=checkbox]').attr('checked', 'checked').checkboxradio();
			$('#themecheckboxes input[type=checkbox]').checkboxradio('refresh');
		} else {
			$('#themecheckboxes input[type=checkbox]').removeAttr('checked').checkboxradio();
			$('#themecheckboxes input[type=checkbox]').checkboxradio('refresh');
		}
	});
});

function sortBy( i ){
	
	var lis = $('#matchinglist li');
	switch(i){
	case "partner":
		lis.sort(function(a, b){
		    return $(a).attr('data-id') > $(b).attr('data-id') ? 1 : -1;
		});
		break;
	case "date":
		lis.sort(function(a, b){
		    return $(a).attr('data-time') < $(b).attr('data-time') ? 1 : -1;
		});
		break;
	}
	$('#matchinglist').html(lis);
	$('#matchinglist').listview('refresh');
}

function subscribe(application, namespace, id, index) {
	$.get('../../lib/dasp/ajax/Subscribe', {
		code : 0,
		application : application,
		namespace : namespace,
		id : id,
		index : decodeURIComponent(index)
	}, function(data) {
		console.log(data);
		var response = JSON.parse(data);
		$('#notification-success h3').text(response.description);
		$('#notification-success').show();
	});
}

function rate(feedback, id, user) {
	
	if (user == undefined)
		user = id;
	
	$.get('../../lib/dasp/ajax/Interaction', {
		application : application,
		predicate : id,
		producer : user,
		feedback : feedback
	}, function(data) {
		console.log(data);
		var response = JSON.parse(data);
		$('#notification-success h3').text(response.description);
		$('#notification-success').show();
		//location.reload(0);
		
	});
}

$("#home").live("pagecreate", function() {

	var switchTo5x=false;
	$.getScript("http://w.sharethis.com/button/buttons.js", function(){
		stLight.options({publisher: "5d7eff17-98e8-4621-83ba-6cb27a46dd05", onhover:false});
	});

	$("#shareThis").bind({
		popupafterclose: function(event, ui) {
			setTimeout(function(){$("#shareThisButton").blur();},100)
		}
	});
});


function updateProfile(k, v) {
	
	var data = {};
	data[k] = v;
	
	$.get('../../lib/dasp/ajax/Profile.php', data, function(res){
		location.reload(0);
		//console.log(res);
		//var response = JSON.parse(res);
	});
}

$('#tagSearch').live("keyup", function(event) {
	if (event.keyCode == 13) {
		$('#searchForm').submit();
	}
});


function showComment() {
	$('#CommentButton').fadeOut('fast');
    $('#Comments').fadeIn('slow');
    $('#Commenter').fadeIn('slow');
}


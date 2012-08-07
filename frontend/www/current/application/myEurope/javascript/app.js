
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

function toggleSub(code, application, namespace, id, index) {
	$.get('../../lib/dasp/ajax/Subscribe', {
		code : code,
		application : application,
		namespace : namespace,
		id : id,
		index : decodeURIComponent(index)
	}, function(data) {
		console.log(data);
	});
}

function rate(feedback, id, user) {
	$.get('../../lib/dasp/ajax/Interaction', {
		application : application,
		predicate : id,
		producer : user,
		feedback : feedback
	}, function(data) {
		console.log(data);
	});
}

$("#share").live("pagecreate", function() {
	
	$.getScript("http://w.sharethis.com/gallery/shareegg/shareegg.js", function(){
		$.getScript("http://w.sharethis.com/button/buttons.js", function(){
			stlib.shareEgg.createEgg('shareThisShareEgg', ['googleplus','sharethis','facebook','twitter','linkedin','email'], {title:'myEurope',url:'http://www.mymed.fr/myEurope',theme:'shareegg'});
			stLight.options({publisher: "5d7eff17-98e8-4621-83ba-6cb27a46dd05", onhover:false});
		});
	});

});

function updateProfile(k, v) {
	
	var data = {};
	data[k] = v;
	
	$.get('../../lib/dasp/ajax/Profile.php', data, function(res){
		console.log(res);
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


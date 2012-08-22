
var isSub = 3;

var application="myEurope", predicate="";

var commenters = {};

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

function subscribe(application, mailTemplate, index) {
	$.get('../../lib/dasp/ajax/Subscribe', {
		code : 0,
		application : application,
		mailTemplate: mailTemplate,
		predicates : decodeURIComponent(index)
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

$("#BlogTesters").live("pagecreate", function() {
	$(this).find('a.comments').live('click', function(){

		var me = $(this).closest('div');
		if (me.is('.root') || me.attr('data-reply')==me.prev().attr('id'))
			return;
			
    	var father = $('#'+me.attr('data-reply'));
		var clone = father.clone();
		
		me.before(clone);
		me.animate({'margin-left':'20px'}, 200);
	
	});
});

function indent(el){
	var me = '#'+el.closest('div').attr('id');
	var father = '#'+el.closest('div').attr('data-reply');
	console.log(me+":"+$(me).css('margin-left')+" "+father+":"+$(father).css('margin-left'));
	
	/**if (parseInt($(father).css('margin-left'), 10)>=0 && parseInt($(me).css('margin-left'))- parseInt($(father).css('margin-left'))<=20){	
		$(me).css('margin-left', '+=20px');
	
		$(father).css('margin-left', '-=20px');
	}
	
	else if (parseInt($(father).css('margin-left'), 10)<0 ){	
		$(me).css('margin-left', '+=20px');
		$(father).css('margin-left', '+=20px');
	}*/
	
	if (parseInt($(me).css('margin-left'))- parseInt($(father).css('margin-left'))<20){	
		$(me).css('margin-left', '+=20px');

	}

	console.log(me+":"+$(me).css('margin-left')+" "+father+":"+$(father).css('margin-left'));
	console.log("..");
		
	$(father).append($(me));
}

function show(el){
	var me = el.closest('li');
	if ( me.attr('replyTo')==me.prev().attr('id')){
		me.animate({'margin-left':'50px'}, 200);
		return;
	}
		
		
	var father = $('#'+me.attr('replyTo'));
	var clone = father.clone();
	
	me.before(clone);
	me.animate({'margin-left':'50px'}, 200);
	me.closest('ul').listview('refresh');
}



function setCommentForm(comment, post, votesUp, votesDown){
	$('#deleteField').val(comment);
	$('#deleteRm').val(post);
	$('#commentPopup a:eq(1) .ui-btn-text').text(votesUp);
	$('#commentPopup a:eq(2) .ui-btn-text').text(votesDown);
}
function setReplyForm(){
	
	if (!$('#'+$('#deleteField').val()).next().is('form')){
		var clone=$('#commentForm'+$('#deleteRm').val()).clone();
		clone.find('#replyTo').val($('#deleteField').val());
		clone.css({"margin-left":"15px", "margin-right":"15px"});
		$('#'+$('#deleteField').val()).next().show();
		clone.focusout(function(){
			setTimeout(function(){clone.hide()}, 500);
		});
		
	}else{
		$('#'+$('#deleteField').val()).next().show();
		return;
	}
	
	if ($('#deleteRm').val() == '')
		$('#comments'+$('#deleteField').val()).closest('div[data-role=collapsible]').trigger('expand');
	else
		$('#'+$('#deleteField').val()).after(clone);
	//$('#commentForm'+$('#deleteRm').val()+' textarea').focus();
}

$("#Blog").live("pagecreate", function() {	
	$('.comment').each(function(index, value) { 
		var usr = $(value).attr('user');
		console.log(usr);
		if (!commenters.hasOwnProperty(usr))
			commenters[usr] = 'http://lorempixel.com/30/30/';
		
		$(value).find('img').attr('src', commenters[usr]);
	});
});

$("#Blog").live("pageshow", function() {
	$('div[data-role=collapsible]:first').trigger('expand');
});

function showComment() {
	$('#CommentButton').fadeOut('fast');
    $('#Comments').fadeIn('slow');
    $('#Commenter').fadeIn('slow');
}


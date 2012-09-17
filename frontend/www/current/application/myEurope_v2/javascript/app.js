
var isSub = 3;

var application="myEurope", predicate="";

var isCLEpost = false, isCLEblog = false;

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

$("#post").live("pagecreate", function() {
	if(!isCLEpost){
		$.getScript("../../lib/jquery/CLEeditor/jquery.cleditor.js", function(){
			console.log("CLE loaded");
			$("#CLEeditor").cleditor({useCSS:true})[0].focus();
		});
	}
	isCLEpost = true;
});

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

$("#home").live("pageshow", function() {
	$('#searchButton').addClass('ui-btn-active');
	$('#postButton').addClass('ui-btn-active');
});

function sortBy( i ){
	
	var lis = $('#matchinglist li');
	switch(i){
	case "partner":
		lis.sort(function(a, b){
		    return $(a).attr('data-partner') > $(b).attr('data-partner') ? 1 : -1;
		});
		break;
	case "date":
		lis.sort(function(a, b){
		    return $(a).attr('data-time') < $(b).attr('data-time') ? 1 : -1;
		});
		break;
	case "title":
		lis.sort(function(a, b){
		    return $(a).attr('data-title') < $(b).attr('data-title') ? 1 : -1;
		});
		break;
	}
	$('#matchinglist').html(lis);
	$('#matchinglist').listview('refresh');
}

function subscribe(el, application, mailTemplate, predicates) {
	var page = el.parents('[data-role=page]').attr('id');
	$.post('../../lib/dasp/ajax/Subscribe', {
		//code : 0,
		application : application,
		mailTemplate: mailTemplate,
		predicates : predicates
	}, function(res) {
		console.log(res);
		var response = JSON.parse(res);
		$('#'+page+' #notification-success h3').text(response.description);
		$('#'+page+' #notification-success').show();
	});
}

$("#Blog").live("pageshow", function() {
	isCLEblog = false;
});

$(".loadCLE").live("expand", function(e) {
	if(!isCLEblog){
		$.getScript("../../lib/jquery/CLEeditor/jquery.cleditor.js", function(){
			console.log("CLE loaded");
			$("#CLEeditor").cleditor({useCSS:true})[0].focus();
		});
	}
	isCLEblog = true;
});

function rate(el, id, usr, feedback) {
	var page = el.parents('[data-role=page]').attr('id');
	var data = {
			application : id,
			producer : usr,
			feedback : feedback
		};
	
	console.log(data);
	$.get('../../lib/dasp/ajax/Interaction', data, function(res) {
		console.log(res);
		var response = JSON.parse(res);
		$('#'+page+' #notification-success h3').text(response.description);
		$('#'+page+' #notification-success').show();
		//location.reload(0);
		
	});
}

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


// ---------- blogs ----------

var blog="", field="", rm="";

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

var blog="", field="", rm="";

function setIds(el){
	var ids = el.parents('li');
	field = $(ids[0]);
	rm = ids.length >1? $(ids[1]): "";
}

function reply(el){
	setIds(el);
	var form = $('#comment'+field.attr('id'));
	if (rm=="")
		form.parent().trigger("expand");
	else
		form.show();
}

function profile(user){
	$.get('../../lib/dasp/ajax/ExtendedProfile', {
		application: application,
		id : user,
	}, function(res) {
		//console.log(res);
		$('#popupInfo').html(res);
		$('#popupInfo').trigger('create');
		$('#popupInfo').popup('open');
	});
}

function commentAdd(el){

	setIds(el);
	var replyTo = rm==""?"":field.attr('id');
	
	var text = el.prev('textarea').val();
	var post = rm==""?field:rm;

	console.log(replyTo+" "+text);
	var userCommented = replyTo!=""?field.find('.user-sig').text():"";
	
	$.post('../../lib/dasp/ajax/PublishComment', {
		application: application+":blogs",
		id: blog+"comments"+post.attr('id'),
		replyTo: replyTo,
		userCommented: userCommented,
		text: text
	}, function(li) {
		console.log(li);
		if (li != null){//insert the comment
			var ul = rm == ""?field.find('ul'):field.closest('ul');
			if (replyTo != "")
				field.after(li);
			else
				ul.prepend(li);
			
			$(li).find('.comment').trigger('create');
			ul.listview('refresh');
		}
	});
}

function commentRm(){
	if (rm==""){
		$.mobile.changePage('?action=Blog&blog='+blog+'&field='+field.attr("id"));
		return;
	}
		
	$.get('../../lib/dasp/ajax/Delete', {
		application: application+":blogs",
		id : blog+"comments"+rm.attr('id'),
		field: field.attr('id')
	}, function(res) {
		console.log(res);
		var response = JSON.parse(res);
		if (response != null){//insert the comment

			field.remove();
			field.closest('ul').listview('refresh');
			$('#deletePopup').popup( "close" );
			
		}
	});
}

function prettyprintUser(id){
	return id.split(/(MYMED_|@)/)[2].replace('.', ' ');
}


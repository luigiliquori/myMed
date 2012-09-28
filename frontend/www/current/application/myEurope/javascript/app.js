
var isSub = 3;

var application="myEurope", predicate="";

var CLEloaded = false;

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

$("#Blog, #post").live("pagecreate", function() {
	CLEloaded = false;
		
	$.getScript("../../lib/jquery/CLEeditor/jquery.cleditor.js", function(){
		//console.log("CLE loaded");
		$("#CLEeditor").cleditor({useCSS:true})[0].focus();
	});
	
});


$(".loadCLE").live("expand", function(e) {
	if(!CLEloaded){
		var me = this;
		$.getScript("../../lib/jquery/CLEeditor/jquery.cleditor.js", function(){
			//console.log("CLE loaded");
			$("#CLEeditor").cleditor({useCSS:true})[0].focus();
			$(me).trigger('expand');
			CLEloaded = true
		});
	}
	
});

$("#search").live("pagecreate", function() {
	/*$("#searchForm").submit(function() {
		return validate(this);		
	});*/
	
	$('#checkbox-all, #checkbox-all2, #checkbox-all3').click(function() {
		var parent =$(this).parent().parent(); //must do better later
		if ($(this).is(':checked')){
			parent.find('input[type=checkbox]').attr('checked', 'checked').checkboxradio();
			parent.find('input[type=checkbox]').checkboxradio('refresh');
		} else {
			parent.find('input[type=checkbox]').removeAttr('checked').checkboxradio();
			parent.find('input[type=checkbox]').checkboxradio('refresh');
		}
	});
});


/*function validate(elt){
	var t=[];
	$('input[data-t]', elt).each(function(i, v) {
		if ($(v).is(':checked')){
			t.push($(v).data('t'));
		}
	});
	$('.formThemes').val(t.join('-'));
	var pf=[];
	$('input[data-pf]', elt).each(function(i, v) {
		if ($(v).is(':checked')){
			pf.push($(v).data('pf'));
		}
	});
	$('.formFrance').val(pf.join('-'));
	var pi=[];
	$('input[data-pi]', elt).each(function(i, v) {
		if ($(v).is(':checked')){
			pi.push($(v).data('pi'));
		}
	});
	$('.formItaly').val(pi.join('-'));
	var po=[];
	$('input[data-t]', elt).each(function(i, v) {
		if ($(v).is(':checked')){
			po.push($(v).data('po'));
		}
	});
	$('.formOther').val(po.join('-'));
	var r=[];
	$('input[data-r]', elt).each(function(i, v) {
		if ($(v).is(':checked')){
			t.push($(v).data('r'));
		}
	});
	$('.formRoles').val(r.join('-'));
}*/

function sortBy( i ){
	
	var lis = $('#matchinglist li');
	switch(i){
	case "partner":
		lis.sort(function(a, b){
		    return $(a).attr('data-partner').toLowerCase() > $(b).attr('data-partner').toLowerCase() ? 1 : -1;
		});
		break;
	case "date":
		lis.sort(function(a, b){
		    return $(a).attr('data-time') < $(b).attr('data-time') ? 1 : -1;
		});
		break;
	case "title":
		lis.sort(function(a, b){
		    return $(a).attr('data-title').toLowerCase() > $(b).attr('data-title').toLowerCase() ? 1 : -1;
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
		predicates : JSON.stringify(predicates)
	}, function(res) {
		//console.log(res);
		var response = JSON.parse(res);
		$('#'+page+' #notification-success h3').text(response.description);
		$('#'+page+' #notification-success').show();
	});
}


function rate(el, id, usr, feedback) {
	var succ = el.parents('[data-role=page]').find('#notification-success');  //should work on that
	var data = {
			application : id,
			producer : usr,
			feedback : feedback
		};
	
	//console.log(data);
	$.get('../../lib/dasp/ajax/Interaction', data, function(res) {
		//console.log(res);
		var response = JSON.parse(res);
		succ.find('h3').text(response.description);
		succ.show();
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
	if ( me.data('replyto')==me.prev().attr('id')){
		me.animate({'margin-left':'50px'}, 200);
		return;
	}
	
	
	var father = $('#'+me.data('replyto'));
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

	//console.log(replyTo+" "+text);
	var userCommented = replyTo!=""?field.find('.user-sig').text():"";
	
	$.post('../../lib/dasp/ajax/PublishComment', {
		application: application+":blogs",
		id: blog+"comments"+post.attr('id'),
		replyTo: replyTo,
		userCommented: userCommented,
		text: text,
	}, function(li) {
		//console.log(li);
		if (li != null){//insert the comment
			var ul = rm == ""?field.find('ul'):field.closest('ul');
			if (replyTo != "")
				field.after(li);
			else
				ul.prepend(li);
			
			if (rm!=""){
				$(li).css('margin-left', '50px');
			}
			$('#Blog').trigger('pagecreate');
			ul.listview('refresh');
			field.find('.comment').fadeOut('fast');
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
		//console.log(res);
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

$("[data-role=page]").live("pagecreate", function() {
	if (isiPhoneoriPad()){
		$(this).find('.toptab').hide();
		$(this).find('.iostab').show();
	}
});

function isiPhoneoriPad(){
	var deviceStr = (navigator.userAgent+navigator.platform).toLowerCase();
	return true; //deviceStr.match(/(iphone|ipod|ipad)/);
}


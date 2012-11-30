var isSub = 3;
var application="myEurope", predicate="";
var CLEloaded = false;
var inputStr = '<input name="k[]" list="keywords" class="tagInput" style="width: 100px; margin-left: 10px; margin-bottom: 5px;"/>';

$('.tagInput').live('keyup', function(e){
	var ctId = '#'+ this.parentNode.id;
	if (e.keyCode == 32){
		this.value = this.value.trim();
		$(ctId).append(inputStr);
		$(ctId + ' .tagInput').last().textinput();
		$(ctId + ' .tagInput').last().focus();		
	} else if (e.keyCode == 8 && this.value == '' && this.id != 'textinput1'){
		if ($(ctId + ' .tagInput').length > 1){
			$(this).remove();
			$(ctId + ' .tagInput').last().focus();
		}
	}
});

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

function sortBy( i ){
	
	var lis = $('#matchinglist li');
	switch(i){
	case "partner":
		lis.sort(function(a, b){
		    return $(a).data('partner').toLowerCase() > $(b).data('partner').toLowerCase() ? 1 : -1;
		});
		break;
	case "date":
		lis.sort(function(a, b){
		    return $(a).data('time') < $(b).data('time') ? 1 : -1;
		});
		break;
	case "title":
		lis.sort(function(a, b){
		    return $(a).data('title').toLowerCase() > $(b).data('title').toLowerCase() ? 1 : -1;
		});
		break;
	}
	$('#matchinglist').html(lis);
	$('#matchinglist').listview('refresh');
}

function subscribe(el, application, mailTemplate, predicates, id) {
	var page = el.parents('[data-role=page]').attr('id');
	var data = {
		//code : 0,
			application : application,
			mailTemplate: mailTemplate
	};
	if (predicates != null){
		data.predicates = JSON.stringify(predicates);
	} else {
		data.id = id;
	}
	$.post('../../lib/dasp/ajax/Subscribe', data, function(res) {
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
		id : user
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
		text: text
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
		$.mobile.changePage('?action=Blog&method=delete&id='+blog+'&field='+field.attr("id"));
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
		$(this).find('.iostab').show();
	} else{
		$(this).find('.toptab').show();
	}
});

function isiPhoneoriPad(){
	var deviceStr = (navigator.userAgent+navigator.platform).toLowerCase();
	return true; //deviceStr.match(/(iphone|ipod|ipad)/);
}

$("#login").live("swipeleft", function() {
  $.mobile.changePage("#register", {transition : "slide"});
});

$("#register").live("swipeleft", function() {
  $.mobile.changePage("#about", {transition : "slide"});
}).live("swiperight", function() {
  $.mobile.changePage("#login"/*, {transition : "slide",reversed : true}*/);
});

$("#about").live("swiperight", function() {
  $.mobile.changePage("#register"/*, {transition : "slide",reversed : true}*/);
});


$("#home").live("swipeleft", function() {
  $.mobile.changePage("#blogs", {transition : "slide"});
});

$("#blogs").live("swipeleft", function() {
  $.mobile.changePage("#infos", {transition : "slide"});
}).live("swiperight", function() {
  $.mobile.changePage("#home"/*, {transition : "slide",reversed : true}*/);
});

$("#infos").live("swiperight", function() {
  $.mobile.changePage("#blogs"/*, {transition : "slide",reversed : true}*/);
});

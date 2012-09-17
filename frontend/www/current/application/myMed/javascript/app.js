var cpt = 1;
$(document).ready(function() {
		
	// Handle "close" buttons
	$('[data-action="close"]').click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});
	
	showLoadingBar("chargement en cours...");
	
});

function printDialog(elt, title) {
	$('<div>').simpledialog2({
		method: 'open',
	    mode: 'blank',
	    headerText: title,
	    headerClose: true,
	    blankContent : $("#" + elt)
	  })
}

function hideLoadingBar() {
	$.mobile.hidePageLoadingMsg();
}
function showLoadingBar(text) {
	$.mobile.showPageLoadingMsg("d", text);
	setTimeout(hideLoadingBar, 10000);
}

function SetApplicationStatus(application, status) {
	$(location).attr('href', "?action=store&applicationStore=" + application + "&status=" + status);
}
function toggleStatus(app, status){
	$.mobile.changePage("?action=store&applicationStore="+app+"&status="+status);
}

function nextScreenshot() {
	$("#screenshot" + cpt).hide();
	if (cpt==3) {
		cpt=1;
	} else {
		cpt++;
	}
	$("#screenshot" + cpt).fadeIn();
}

function prevScreenshot() {
	$("#screenshot" + cpt).hide();
	if (cpt==1) {
		cpt=3;
	} else {
		cpt--;
	}
	$("#screenshot" + cpt).fadeIn();
}
function updateProfile(k, v) {
	
	var data = {};
	data[k] = v;
	
	$.get('../../lib/dasp/ajax/Profile.php', data, function(res){
		//location.reload(0);
		//console.log(res);
		//var response = JSON.parse(res);
	});
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


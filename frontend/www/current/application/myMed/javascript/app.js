var cpt = 1;
$(document).ready(function() {
		
	// Handle "close" buttons
	$('[data-action="close"]').click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});

	//showLoadingBar("chargement en cours...");
	
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

function SetCookie (name, value, days) {
	
	var argv=SetCookie.arguments;
	var argc=SetCookie.arguments.length;
	var expires = new Date();
	expires.setTime(expires.getTime()+(days*24*60*60*1000));
	var path=(argc > 3) ? argv[3] : null;
	var domain=(argc > 4) ? argv[4] : null;
	var secure=(argc > 5) ? argv[5] : false;
	
	document.cookie=name+"="+escape(value)+
		((expires==null) ? "" : ("; expires="+expires.toGMTString()))+
		((path==null) ? "" : ("; path="+path))+
		((domain==null) ? "" : ("; domain="+domain))+
		((secure==true) ? "; secure" : "");
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
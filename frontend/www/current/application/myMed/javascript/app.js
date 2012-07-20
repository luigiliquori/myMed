
$(document).ready(function() {
		
	// Handle "close" buttons
	$('[data-action="close"]').click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});

	showLoadingBar("chargement en cours...");
	
});

function printShareDialog() {
	$('<div>').simpledialog2({
	    mode: 'blank',
	    headerText: 'Message',
	    headerClose: true,
	    blankContent : $("#hidden-sharethis")
	  })
}

function hideLoadingBar() {
	$.mobile.hidePageLoadingMsg();
}
function showLoadingBar(text) {
	$.mobile.showPageLoadingMsg("d", text);
	setTimeout(hideLoadingBar, 10000);
}

function SetCookie (name, value) {
	
	var argv=SetCookie.arguments;
	var argc=SetCookie.arguments.length;
	var expires=(argc > 2) ? argv[2] : null;
	var path=(argc > 3) ? argv[3] : null;
	var domain=(argc > 4) ? argv[4] : null;
	var secure=(argc > 5) ? argv[5] : false;
	document.cookie=name+"="+escape(value)+
		((expires==null) ? "" : ("; expires="+expires.toGMTString()))+
		((path==null) ? "" : ("; path="+path))+
		((domain==null) ? "" : ("; domain="+domain))+
		((secure==true) ? "; secure" : "");
}
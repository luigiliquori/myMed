
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

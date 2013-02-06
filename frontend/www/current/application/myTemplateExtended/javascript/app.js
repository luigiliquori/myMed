$(document).ready(function() {
		
	// Handle "close" buttons
	$('[data-action="close"]').click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});

});

function hideLoadingBar() {
	$.mobile.hidePageLoadingMsg();
}

function showLoadingBar(text) {
	$.mobile.showPageLoadingMsg("d", text);
	setTimeout(hideLoadingBar, 10000);
}


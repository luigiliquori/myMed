
$(document).ready(function() {
		
	// Handle "close" buttons
	$('[data-action="close"]').click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});

});

function printShareDialog() {
	$('<div>').simpledialog2({
	    mode: 'blank',
	    headerText: 'Message',
	    headerClose: true,
	    blankContent : $("#hidden-sharethis")
	  })
}



$(document).ready(function() {
	
	// --------------------------------------------
	// Copy error and info boxes to all headers
	// --------------------------------------------
	
	// Error box
	var errorBox = $("#mm-error-box");
	if (errorBox.length == 1) {
		$('[data-role="header"]').each(function() {
			$(this).prepend(errorBox.clone().trigger("create"));
		})
		errorBox.hide();
	}
	
	// Success box
	var successBox = $("#mm-success-box");
	if (successBox.length == 1) {
		$('[data-role="header"]').each(function() {
			$(this).prepend(successBox.clone().trigger("create"));
			
		})
		successBox.hide();
	}
	
	// Handle "close" buttons
	$('[data-action="close"]').click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});

});


<?php

/**
 * Display a notification pop up for errors or success
 * 
 */

function print_notification($message) {

	if($message != "") {
		echo '<div data-role="popup" id="notificationPopup" data-transition="flip" data-theme="e" class="ui-content"><p>' . $message . ' </p></div>';
		echo '
		<script type="text/javascript">
		
			$(document).on("pageshow", function() {
			  	// Go back in the history has problem in Chrome. 
			  	// Do not return back in the history when the pop up disappear.
			  	$( "#notificationPopup" ).popup({ history: false });
	    		$( "#notificationPopup" ).popup("open");
	     	});
		
		 </script>
		 ';
	}  
}

?>
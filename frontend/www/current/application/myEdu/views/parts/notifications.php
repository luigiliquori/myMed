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
		
		// Go back in the history has problem in Chrome. Do not return back in the history when the pop up disappear.
		$( "#notificationPopup" ).popup({ history: false });
		
		$(document).ready(function() {
			$( "#notificationPopup" ).popup("open");
		 });
		 </script>
		 ';
	}  
}

?>
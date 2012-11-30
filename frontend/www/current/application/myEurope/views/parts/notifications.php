 <?php
/**
 * Display a notification bar for errors or success.
 * Now with close button and fadeOut animation
 */

function print_notification($message) {

	if($message != "") {
		echo '<div data-role="popup" id="notificationPopup" data-transition="flip" data-theme="e" class="ui-content"><p>' . $this->error . ' </p></div>';
		echo '
		<script type="text/javascript">
		$(document).ready(function() {
			$( "#notificationPopup" ).popup( "open" );
		 });
		 </script>
		 ';
	}  
}

?>
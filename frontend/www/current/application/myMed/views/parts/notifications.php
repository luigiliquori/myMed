<?
/**
 * Display a notification bar for errors or success.
 * Now with close button and fadeOut animation
 */
?>
<!-- 
<div class="ui-bar ui-bar-e ui-header-fixed" onclick="$(this).fadeOut('fast');" id="notification-error" style="width: initial;display:<?= empty($this->error)?'none':'block' ?>">
	<img alt="Warning: " src="/system/img/warning-icon.png" class="ui-li-icon" />
	<h3 style="font-weight:lighter;"><?= $this->error ?></h3>
	<a href="#" style="float: right;" data-role="button" data-icon="delete" data-inline="true" data-iconpos="notext" onclick="$(this).parent().fadeOut('fast');">Button</a>
</div>


<div class="ui-bar ui-bar-e ui-header-fixed" onclick="$(this).fadeOut('fast');" id="notification-success" style="width: initial;display:<?= empty($this->success)?'none':'block' ?>">
	<h3 style="font-weight:lighter;"><?= $this->success ?></h3>
	<a href="#" style="float: right;" data-role="button" data-icon="delete" data-inline="true" data-iconpos="notext" onclick="$(this).parent().fadeOut('fast');">Button</a>
</div>

-->
<?
function print_notification($message) {

	if($message != "") {
		echo '<div data-role="popup" id="notificationPopup" data-transition="flip" data-theme="e" class="ui-content"><p>' . $message . ' </p></div>';
		echo '
		<script type="text/javascript">
			var first_pageshow = true;
			$(document).on("pageshow", function() {
				if(first_pageshow) {
				  	// Go back in the history has problem in Chrome. 
				  	// Do not return back in the history when the pop up disappear.
				  	$( "#notificationPopup" ).popup({ history: false });
		    		$( "#notificationPopup" ).popup("open");
		    		first_pageshow = false;
	    		}
	     	});
		
		 </script>
		 ';
	}  
}

?>

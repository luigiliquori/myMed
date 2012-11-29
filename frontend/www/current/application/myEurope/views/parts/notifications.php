 <?
/**
 * Display a notification bar for errors or success.
 * Now with close button and fadeOut animation
 */
?>

<? if (!empty($this->error)) { ?>
	<div data-role="popup" id="popupError" data-transition="flip" data-theme="e" class="ui-content">
		<p><?= $this->error ?></p>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$( "#popupError" ).popup( "open" );
	 });
	 </script>
<? } else if(!empty($this->success)) {?>
	<div data-role="popup" id="popupSuccess" data-transition="flip" data-theme="e">
		<p><?= $this->success ?></p>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$( "#popupSuccess" ).popup( "open" );
		 });
	 </script>
<? } ?>

<?php include_once 'help.php' ?>
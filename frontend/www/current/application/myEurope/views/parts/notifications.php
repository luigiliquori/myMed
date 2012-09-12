<?
/**
 * Display a notification bar for errors or success.
 * Now with close button and fadeOut animation
 */
?>
<div class="ui-bar ui-bar-e" id="notification-error" style="display:<?= empty($this->error)?'none':'block' ?>">
	<h3 style="font-weight:lighter;"><?= $this->error ?></h3>
	<a href="#" style="float:right;" data-role="button" data-icon="delete" data-inline="true" data-iconpos="notext" onclick="$(this).parent().fadeOut('fast');">Button</a>
</div>


<div class="ui-bar ui-bar-e" id="notification-success" style="display:<?= empty($this->success)?'none':'block' ?>">
	<h3 style="font-weight:lighter;"><?= $this->success ?></h3>
	<a href="#" style="float:right;" data-role="button" data-icon="delete" data-inline="true" data-iconpos="notext" onclick="$(this).parent().fadeOut('fast');">Button</a>
</div>

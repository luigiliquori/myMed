<? if (!empty($this->error)): ?>
<div class="ui-bar ui-bar-e" id="notification-error">
	<h3><?= $this->error ?></h3>
	<div style="float:right; margin-top:4px;">
		<a href="#" data-role="button" data-icon="delete" data-iconpos="notext" onclick="$('#notification-error').slideUp('fast');">Button</a>
	</div>
</div>
<? endif ?>

<? if (!empty($this->success)): ?>
<div class="ui-bar ui-bar-e" id="notification-success">
	<h3><?= $this->success ?></h3>
	<div style="float:right; margin-top:4px;">
		<a href="#" data-role="button" data-icon="delete" data-iconpos="notext" onclick="$('#notification-success').slideUp('fast');">Button</a>
	</div>
</div>
<? endif ?>
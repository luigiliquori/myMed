<? if (!empty($this->error)): ?>
	<div  
		data-role="navbar" 
		data-theme="e" 
		class="ui-bar-e error-box" >
	
		<h3><?= _("Error") ?></h3>
		<p><?= $this->error ?></p>
		<a href=".error-box" data-icon="delete" data-action="close" class="ui-btn-right"><?= _("Close") ?></a>
	</div>
<? endif ?>

<? if (!empty($this->success)): ?>
	<div 
		data-role="navbar"
		data-theme="g"
		class="success-box" >
		
		<h3><?= _("Message") ?></h3>
		<p><?= $this->success ?></p>
		<a href=".success-box" data-icon="delete" data-data-action="close"><?= _("Close") ?></a>
	</div>
<? endif ?>
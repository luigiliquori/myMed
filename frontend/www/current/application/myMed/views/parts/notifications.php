<? if (!empty($this->error)): ?>
	<div  
		data-role="navbar" 
		data-theme="e"
		class="ui-bar ui-bar-e error-box" >
	
		<h3><?= _("Error") ?></h3>
		<p><?= $this->error ?></p>
		
		<div style="position: relative; float:right; margin-top:4px;">
			<a href=".error-box" data-role="button" data-icon="delete" data-iconpos="notext" data-action="close" >Fermer</a>
		</div>
	</div>
<? endif ?>

<? if (!empty($this->success)): ?>
	<div 
		data-role="navbar"
		data-theme="g"
		class="ui-bar ui-bar-g success-box" >
		
		<h3><?= _("Message") ?></h3>
		<p><?= $this->success ?></p>
		
		<div style="float:right; margin-top:4px;">
			<a href=".error-box" data-role="button" data-icon="delete" data-iconpos="notext" data-action="close" >Button</a>
		</div>
		
	</div>
<? endif ?>
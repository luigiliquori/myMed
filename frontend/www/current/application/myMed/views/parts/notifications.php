<? if (!empty($this->error)): ?>
	<div  
		data-role="navbar" 
		data-theme="e"
		class="ui-bar ui-bar-e error-box" >
	
		<p>
		<img alt="Warning: " src="<?= APP_ROOT ?>/img/warning-icon.png" class="ui-li-icon" />
		<span Style="position: relative; top: -10px;"><?= $this->error ?></span>
		<a href=".error-box" data-action="close" data-role="button" >ok</a>
		</p>
		
	</div>
<? endif ?>

<? if (!empty($this->success)): ?>
	<div 
		data-role="navbar"
		data-theme="e"
		class="ui-bar ui-bar-e error-box" >
		
		<p>
		<span Style="position: relative; top: -10px;"><?= $this->success ?></span>
		<a href=".error-box" data-action="close" data-role="button" >ok</a>
		</p>
			
	</div>
<? endif ?>
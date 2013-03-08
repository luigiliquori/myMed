<? if (!empty($this->error)): ?>
	
	<div data-role="navbar" data-theme="e" class="ui-bar ui-bar-e error-box" >
		<p style="position: relative; top: -10px;"><?= $this->error ?></p>
	</div>
<? endif ?>

<? if (!empty($this->success)): ?>
	
	<div data-role="navbar" data-theme="e" class="ui-bar ui-bar-e error-box" >	
		<p>
			<span Style="position: relative; top: -10px;"><?= $this->success ?></span>
			<a href="." data-action="close" data-role="button" data-ajax="false">ok</a>
		</p>	
	</div>
<? endif ?>
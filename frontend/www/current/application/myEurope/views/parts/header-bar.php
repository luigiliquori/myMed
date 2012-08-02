<div data-role="header" data-theme="c" style="max-height: 38px;">

	<a href="?action=logout" rel="external" data-role="button" data-theme="r">Quit</a>
	
	<h1><?= APPLICATION_NAME ?></h1>
	
	<? if (!empty($this->user)) : ?>
		<a href="?action=extendedProfile"><?= $this->user->name ?></a>
	<? endif ?>
	
	<? include("notifications.php")?>
	
</div>



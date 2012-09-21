<div data-role="header">

	<? if (!empty($this->user)) : ?>
		<a href="#profile"><?= $this->user->name ?></a>
	<? endif ?>
	
	<h1><?= APPLICATION_NAME ?></h1>
	
	<a href="?action=logout" rel="external" data-role="button" data-theme="a">Quit</a>
	
	<? include("notifications.php")?>
	
</div>



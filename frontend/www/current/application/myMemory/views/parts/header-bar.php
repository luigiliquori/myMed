<div data-role="header">

	<? if (!empty($this->user)) : ?>
		<a href="#profile"><?= $this->user->name ?></a>
	<? endif ?>
	
	<h1><?= APPLICATION_NAME ?></h1>
	

</div>

<? include("notifications.php")?>

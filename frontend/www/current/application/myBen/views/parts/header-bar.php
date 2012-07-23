<div data-role="header">

	<a href="javascript: history.go(-1)" data-role="button" data-icon="back">Retour</a>
	
	<h1><a href="?"><?= "MyBénévolat" ?></a></h1>
	
	<? if (isset($this->user)) : ?>
	<a href="<?= url("editProfile") ?>" rel="external" data-role="button" data-theme="g" data-icon="gear"><?= $this->user->name ?></a>
	<? endif ?>
	
	<? include("notifications.php")?>
	
</div>
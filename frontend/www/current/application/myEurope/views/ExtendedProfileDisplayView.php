<? include("header.php"); ?>

<div data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_2empty(prettyprintId($this->id), "profile") ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content" >
	
		<?= printProfile($this->profile) ?>
		<? if (isset($_GET['link'])) :?>
		<br />
		<div style="text-align: center;">
		<a href="?action=ExtendedProfile&link=<?= $_GET['id'] ?>" rel="external" data-role="button" data-inline="true" data-icon="check"><?= _('Register in myEurope with this profile') ?></a>
		<br />
		<a href="?action=Main#profiles" rel="external" data-role="button" data-inline="true" data-theme="e" data-icon="back"><?= _('Back to profiles list') ?></a>
		</div>
		<? endif ?>
		
		<? if (isset($_GET['admin'])) :/*should check permission*/?>
		<br />
		<div style="text-align: center;">
		<a href="?action=ExtendedProfile&rmUser=<?= $_GET['id'] ?>" rel="external" data-role="button" data-inline="true" data-icon="check"><?= _('Delete user') ?></a>
		<br />
		<a href="?action=ExtendedProfile&rmProfile=<?= $_GET['id'] ?>" rel="external" data-role="button" data-inline="true" data-icon="check"><?= _('Delete profile') ?></a>
		</div>
		<? endif ?>
	</div>
</div>
<? include("footer.php"); ?>
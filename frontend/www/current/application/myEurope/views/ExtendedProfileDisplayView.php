<? include("header.php"); ?>

<div data-role="page">

	<? tabs_simple(array($this->profile->details['name'])); ?>
	<? include("notifications.php"); ?>
	
	<div data-role="content" >
	
		<br />
		<? $this->profile->renderProfile(); ?>
		<? if (isset($_GET['link'])) :?>
		<br />
		<div style="text-align: center;">
		<a href="?action=ExtendedProfile&link=<?= $_GET['id'] ?>" rel="external" data-role="button" data-theme="e" data-inline="true" data-icon="pushpin"><?= _('Register in myEurope with this profile') ?></a>
		<br />
		<a href="?action=Main#profiles" rel="external" data-role="button" data-inline="true" data-icon="back"><?= _('Back to profiles list') ?></a>
		</div>
		<? endif ?>
		
		<? if (isset($_GET['admin'])) :/*should check permission*/?>
		<br />
		<div style="text-align: center;">
		<a href="?action=ExtendedProfile&rmUser=<?= $_GET['id'] ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete user') ?></a>
		<br />
		<a href="?action=ExtendedProfile&rmProfile=<?= $_GET['id'] ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete profile') ?></a>
		</div>
		<? endif ?>
	</div>
</div>
<? include("footer.php"); ?>
<? include("header.php"); ?>

<div data-role="page">

	<? tabs_simple(array($this->profile->details['name'])); ?>
	<? include("notifications.php"); ?>
	
	<div data-role="content">
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
		
		<div style="text-align: center;">
			<? if (isset($_GET['admin']) && $_SESSION['myEurope']->permission > 1 ) :/*should check permission*/?>
			<br />
			
			<a href="?action=ExtendedProfile&rmUser=<?= $this->profile->details->id ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete user') ?></a>
			<br />
			<a href="?action=ExtendedProfile&rmProfile=<?= $this->profile->details->id ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete profile') ?></a>
			
			<? endif ?>
			
			<? if ($_GET['user'] == $_SESSION['user']->id && isset($_SESSION['myEurope'])): ?>
			<br />
			<a type="button" href="?action=ExtendedProfile&edit=false"  data-theme="d" data-icon="edit" data-inline="true"><?= _('Edit my profile') ?></a>
			<? endif; ?>
		</div>
	</div>
</div>
<? include("footer.php"); ?>
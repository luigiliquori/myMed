<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page">

	<? print_header_bar(true, "defaultHelpPopup"); ?>
	
	<div data-role="content">
		<br><br>
		<div style="text-align: center;">
		<?php $text = $_SESSION['myEurope']->permission >=2 ? _('You are a full-powered Admin, have fun!') : _('users list'); ?>
			<a href="?action=Admin" data-inline="true" data-role="button" data-icon="gear" title="<?= $text ?>"><?= $text ?></a>
		</div>
		<br>
		<? $this->profile->renderProfile($_SESSION['user']); ?>
		<div data-role="popup" id="updatePicPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right">Close</a>
			<div style="display: inline-block;">
				<input type="text" id="picUrl" placeholder="Picture's url" value="http://cdn.walyou.com/wp-content/uploads//2010/12/facebook-profile-picture-no-pic-avatar.jpg" data-inline="true" />
			</div>
			<a onclick="$('#updatePicPopup').popup('close');updateProfile('profilePicture', $('#picUrl').val());" data-role="button" data-theme="d" data-mini="true" data-icon="ok" data-inline="true"><?= _("Update") ?></a>
		</div>
		<? if (isset($_GET['link'])) :?>
		<br />
		<div style="text-align: center;">
		<a href="?action=ExtendedProfile&link=<?= $_GET['id'] ?>" rel="external" data-role="button" data-theme="e" data-inline="true" data-icon="pushpin"><?= _('Register in myEurope with this profile') ?></a>
		<br />
		<a data-rel="back" data-role="button" data-inline="true" data-icon="back"><?= _('Back to profiles list') ?></a>
		</div>
		<? endif ?>
		
		<div style="text-align: center;">
			<? if (isset($_GET['admin']) ): ?>
			<br />
			<a href="?action=ExtendedProfile&method=delete&rmUser=<?= $_GET['user'] ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete user') ?></a>
			<br />
			<a href="?action=ExtendedProfile&method=delete&rmProfile=<?= $this->profile->id ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete profile') ?></a>
			
			<? endif ?>
			
			<? if ($_GET['user'] == $_SESSION['user']->id && isset($_SESSION['myEurope'])): ?>
			<br />
			<a type="button" href="?action=ExtendedProfile&edit=false"  data-theme="d" data-icon="edit" data-inline="true"><?= _('Edit my profile') ?></a>
			<a type="button" href="?action=ExtendedProfile&delete=true"  data-theme="d" data-icon="delete" data-inline="true"><?= _('Delete my profile') ?></a>
			<? endif; ?>
			
			<!-- List user's project button -->
			<br />
			<? if ($_GET['user'] == $_SESSION['user']->id && isset($_SESSION['myEurope'])): ?>
			<a type="button" href="?action=ListUserProjects" data-ajax="false" data-theme="d" data-icon="grid" data-inline="true" ><?= _('List my projects') ?></a>
			<? endif; ?>

		</div>
	</div>
</div>

<? include("footer.php"); ?>
<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page">
  <? $title = _("Profile");
	 print_header_bar(true, "defaultHelpPopup", $title); ?>
	
	<div data-role="content">
		<br><br>
		
		<? if ($_SESSION['myEurope']->permission >=2) : ?>
			<div style="text-align: center;">
				<a href="?action=Admin" data-inline="true" data-role="button" data-icon="gear" data-theme="e" title=" <?= _('users list') ?> "> <?= _('users list') ?> </a>
			</div>
			<br>
		<? endif ?>
			
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
		<!--<? if (isset($_GET['admin']) ): ?>
			<br />
			<a href="?action=ExtendedProfile&method=delete&rmUser=<?= $_GET['user'] ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete user') ?></a>
			<br />
			<a href="?action=ExtendedProfile&method=delete&rmProfile=<?= $this->profile->id ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete profile') ?></a>
			<br />
			<? endif; ?>
		-->	
			<? if ($_GET['user'] == $_SESSION['user']->id && isset($_SESSION['myEurope'])): ?>
			<br />
			<a type="button" href="?action=ExtendedProfile&edit=false"  data-theme="d" data-icon="edit" data-inline="true"><?= _('Edit my profile') ?></a>
			<a type="button" href="?action=ExtendedProfile&delete=true"  data-theme="d" data-icon="delete" data-inline="true"><?= _('Delete my profile') ?></a>
			<a href="?action=ExtendedProfile&method=delete&rmPublications=<?= $_SESSION['user']->id ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete publications') ?></a>
			
			<? endif; ?>
			
			<!-- List user's project button -->
			<br />
			<? if ($_GET['user'] == $_SESSION['user']->id && isset($_SESSION['myEurope'])): ?>
			<a type="button" href="?action=ListUserProjects" data-ajax="false" data-theme="d" data-icon="grid" data-inline="true" ><?= _('List my projects') ?></a>
			<? endif; ?>

		</div>
	</div>
	
	<!-- ----------------- -->
	<!-- DEFAULT HELP POPUP -->
	<!-- ----------------- -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit Profile and Projects") ?></h3>
		<p> <?= _("Here you can modify your profile and list your active projects.") ?></p>
		
	</div>
	
</div>

<? include("footer.php"); ?>
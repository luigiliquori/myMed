<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page">

 	<?php 	
 		$title = _("Profile");
	   	// Handle different referer
	 	if(strpos($_SERVER['HTTP_REFERER'],"?action=details")) {
	 		print_header_bar($_SERVER['HTTP_REFERER'], false, $title);
		} elseif(strpos($_SERVER['HTTP_REFERER'],"?action=Admin") &&
				 $_GET['user'] != $_SESSION['user']->id) {
			print_header_bar("?action=Admin", false, $title);
		} elseif(strpos($_SERVER['HTTP_REFERER'],"?action=profile")) {
			print_header_bar("back", false, $title);
		} else {
			print_header_bar("?action=main", false, $title);
		}
	?>
	
	<div data-role="content">
		<br>
		
		<? if ($_SESSION['myEurope']->permission >=2 && $_GET['user'] == $_SESSION['user']->id) : ?>
			<div style="text-align: center;">
				<br>
				<a href="?action=Admin" data-inline="true" data-role="button" data-icon="gear" data-theme="e"> <?= _("Users list") ?> </a>
			</div>
		<? elseif ($_SESSION['myEurope']->permission ==0): ?>
			<div data-role="header" data-theme="e">
				<h1 style="white-space: normal;"><?= _("You have to wait the administrator validation to update/delete your profile.") ?></h1>
			</div> 
		<? endif ?>
		<br>
		<? 
		$fromDetailView;
		if($_GET['user'] != $_SESSION['user']->id) $fromDetailView = true;
		else $fromDetailView=false;
		$this->profile->renderProfile($_SESSION['user'], $fromDetailView); ?>
<!--		<div data-role="popup" id="updatePicPopup" class="ui-content" data-overlay-theme="e" data-theme="d">
			<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="remove" data-iconpos="notext" class="ui-btn-right"><? _("Close")?></a>
			<div style="display: inline-block;">
				<input type="text" id="picUrl" placeholder="Picture's url" value="http://cdn.walyou.com/wp-content/uploads//2010/12/facebook-profile-picture-no-pic-avatar.jpg" data-inline="true" />
			</div>
			<a onclick="$('#updatePicPopup').popup('close');updateProfile('profilePicture', $('#picUrl').val());" data-role="button" data-theme="d" data-mini="true" data-icon="ok" data-inline="true"><?= _("Update") ?></a>
		</div>
 -->		
		<div style="text-align: center;">
		<!--<? if (isset($_GET['admin']) ): ?>
			<br />
			<a href="?action=ExtendedProfile&method=delete&rmUser=<?= $_GET['user'] ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete user') ?></a>
			<br />
			<a href="?action=ExtendedProfile&method=delete&rmProfile=<?= $this->profile->id ?>" rel="external" data-role="button" data-inline="true" data-icon="remove"><?= _('Delete profile') ?></a>
			<br />
			<? endif; ?>
		-->	
		 <? if ($_GET['user'] == $_SESSION['user']->id && isset($_SESSION['myEurope']) && $_SESSION['myEurope']->permission >0): ?>
				<br />
				<a type="button" href="?action=ExtendedProfile&edit=false"  data-theme="d" data-icon="edit" data-inline="true"><?= _('Edit my profile') ?></a>
				
				<!-- Upgrade profile from facebook/google+ to myMed account. Impossible from twitter (no email) -->
			 <? if(isset($_SESSION['userFromExternalAuth']) && (!isset($_SESSION['user']->login)) && $_SESSION['userFromExternalAuth']->socialNetworkName!="Twitter-OAuth"): ?>
					<a type="button" href="?action=UpgradeAccount&method=migrate"  data-theme="g" data-icon="pencil" data-inline="true"><?= _('Create a myMed profile') ?></a>
			 <? endif; ?>
		 
				<a type="button" href="#popupDeleteProfile" data-theme="d" data-rel="popup" data-icon="delete" data-inline="true"><?= _('Delete my profile') ?></a>
				
				<div data-role="popup" id="popupDeleteProfile" class="ui-content" Style="text-align: center; width: 18em;">
					<?= _("Are you sure you want to delete your profile ?") ?><br /><br />
					<a type="button" href="?action=ExtendedProfile&delete=true"  data-theme="g" data-icon="ok" data-inline="true"><?= _('Yes') ?></a>
					<a href="#" data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>
				</div>
			
			<? endif; ?>
			
			<!-- List user's project button -->
			<br />
			<? if ($_GET['user'] == $_SESSION['user']->id && isset($_SESSION['myEurope'])): ?>
			<a type="button" href="?action=ListUserProjects" data-ajax="false" data-theme="d" data-icon="grid" data-inline="true" ><?= _("List my projects") ?></a>
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
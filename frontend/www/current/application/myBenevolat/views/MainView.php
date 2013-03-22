<?php
/*
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
 */
?>
<!-- ------------------ -->
<!-- App Main View      -->
<!-- ------------------ -->


<!-- Page view -->
<div data-role="page" id="mainView" >


	<!-- Header bar -->
	<? print_header_bar("logout", "mainViewHelpPopup", false); ?>

	 
	<!-- Page content --> 
	<div data-role="content">
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
			
		<!-- App description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Welcome") ?></h3>
			<p><?= _("Main capsule text") ?></p>	
		</div>
		
		<!-- App Main menu -->
	 <? if ($_SESSION['user']->is_guest) { ?>
			<!-- User not authenticated - Sign in -->
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=extendedProfile" data-icon="signin" data-role="button" data-ajax="false"><?=_("Connection")?></a><br />
	 <? } ?>
  <?php if (!$_SESSION['user']->is_guest && !isset($_SESSION['myBenevolat'])) { ?>
			<p Style="text-align: center; font-style:italic;"><?= _("You have to create your extended profile to access other options") ?></p>
  <?php } ?>
  	 <? if(isset($_SESSION['myBenevolat']) && (($_SESSION['myBenevolat']->details['type'] == 'association') && $_SESSION['myBenevolat']->permission == '0')): ?>
			<p Style="text-align: center; font-style:italic;"><?= _("You have to wait the validation of your association to access all the menu options") ?></p>
  <?php endif; ?>
  	
	 <? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->details['type'] == 'volunteer')):?>
		<a href="?action=mySubscription&subscriptions=true" data-icon="star" data-role="button" data-ajax="false" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My subscriptions") ?></a><br />
	 <? endif;?>
	
	 <? if(isset($_SESSION['myBenevolat']) && (($_SESSION['myBenevolat']->details['type'] == 'association') || ($_SESSION['myBenevolat']->details['type'] == 'admin'))): ?>
			<!-- Associations and Admins links -->
			<a href="index.php?action=publish&method=show_user_announcements" data-icon="pencil" data-role="button" data-ajax="false"  <?= ($_SESSION['myBenevolat']->permission == '0') ? " class='ui-disabled'" : "" ?>><?= _("My announcements") ?></a><br />
	 <? endif; ?>
		
		
		<!-- Find view -->
		<a href="?action=Find&search=true" data-role="button" data-icon="search"><?= _("Search announcement") ?></a><br />
		
		
	<?  if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->details['type'] == 'volunteer')): ?>
			<!-- Volunteer candidatures -->
			<a href="?action=Candidature&method=show_candidatures" data-role="button" data-icon="pencil"><?= _("My candidatures") ?></a><br />
	<?  endif; ?>
		
		<!-- Profile view -->
		<a href="?action=extendedProfile&method=show_user_profile&user=<?= $_SESSION['user']->id ?>" data-icon="user" rel="external" data-role="button" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My profile") ?></a><br />

		
	<? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->permission == '2')): ?>
			<!-- Admin links -->
			<h3><?= _("ADMINISTRATION")?>:</h3>
	 		<a href="?action=Volunteer&method=show_all_volunteers" data-role="button" data-icon="pencil"><?= _("Volunteers list") ?></a><br />
			<a href="?action=Candidature&method=show_all_candidatures" data-role="button" data-icon="pencil" <?= ($_SESSION['user']->is_guest) ? " class='ui-disabled'" : "" ?>><?= _("Manage candidatures") ?></a><br />
			<a href="?action=Validation&method=show_all_validations" data-role="button" data-icon="pencil" <?= ($_SESSION['user']->is_guest) ? " class='ui-disabled'" : "" ?>><?= _("Manage validations") ?></a><br />
			<a href="?action=admin" data-role="button" data-icon="pencil" ><?= _("Manage associations") ?></a><br />
	<? endif;?>
		
		<!-- About dialog -->
		<a href="index.php#aboutView" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
		
		<!-- endif -->
		
		<!-- MainView HELP POPUP -->
		<div data-role="popup" id="mainViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<? if ($_SESSION['user']->is_guest): ?>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("Connect") ?></strong></p>
							<p><?= _("Connect text help") ?></p>
					</li>
				<? endif; ?>
				<? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->details['type'] == 'volunteer')):?>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("My subscriptions") ?></strong></p>
							<p><?= _("My subscriptions text help") ?></p>
					</li>
				<? endif; ?>
				<? if(isset($_SESSION['myBenevolat']) && (($_SESSION['myBenevolat']->details['type'] == 'association') || ($_SESSION['myBenevolat']->details['type'] == 'admin'))): ?>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("My announcements") ?></strong></p>
							<p><?= _("My announcements text help") ?></p>
					</li>
				<? endif; ?>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Search announcement") ?></strong></p>
						<p><?= _("Search announcement text help") ?></p>
				</li>
				<? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->details['type'] == 'volunteer')):?>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("My candidatures") ?></strong></p>
							<p><?= _("My candidatures text help") ?></p>
					</li>
				<? endif; ?>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My profile") ?></strong></p>
						<p><?= _("My profile text help") ?></p>
				</li>
				<? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->permission == '2')): ?>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("Volunteers list") ?></strong></p>
							<p><?= _("Volunteers list text help") ?></p>
					</li>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("Manage candidatures") ?></strong></p>
							<p><?= _("Manage candidatures text help") ?></p>
					</li>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("Manage validations") ?></strong></p>
							<p><?= _("Manage validations text help") ?></p>
					</li>
					<li>
						<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
							<p><strong><?= _("Manage associations") ?></strong></p>
							<p><?= _("Manage associations text help") ?></p>
					</li>
				<? endif; ?>
			</ul>
		</div>
	</div>	
	<!-- End page content -->
	
</div>

<? include_once 'AboutView.php'; ?>

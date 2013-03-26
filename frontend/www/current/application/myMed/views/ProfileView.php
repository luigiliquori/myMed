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
<?php

require_once("header.php");

?>

<div id="profile" data-role="page" data-dom-cache="true">

	<? if ($_SESSION['user']->is_guest): ?>
		<? tab_bar_main("?action=profile", 4); ?>
	<? else: ?>
		<? tab_bar_main("?action=profile"); ?>
	<? endif; ?>

	<? if (!$_SESSION['user']->is_guest): ?>
	<!-- 
	<div data-role="header" data-theme="none" data-position="fixed">
		<a href="?action=profile&method=update" rel="external" class="ui-btn-right" data-theme="e" data-mini="true" data-icon="pencil"><?= _("Edit") ?></a>
	</div>
	-->
	<? endif; ?>
	<? include 'notifications.php'; 
	   print_notification($this->error);?>

	<div data-role="content">

		<ul data-role="listview" data-mini="true">
			<li data-role="list-divider"><?= _("About you") ?></li>
			<li data-icon="pencil"><a href="?action=profile&method=update"><?php if($_SESSION['user']->profilePicture != "") { ?>
					<img class="ui-li-mymed" alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="60" height="60">
				<?php } else { ?>
					<img class="ui-li-mymed" alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="60" height="60">
				<?php } ?>
				<?= $_SESSION['user']->name ?></a>
			</li>

			<li data-icon="pencil"><a href="?action=profile&method=update"><img class="ui-li-mymed" alt="eMail: " src="<?= APP_ROOT ?>/img/email_icon.png" width="50"
				Style="margin-left: 5px; top: 5px;" /> <?= _("E-mail")?>
				<p>
					<?= $_SESSION['user']->email ?>
				</p>
				</a>
			</li>
			<li data-icon="pencil"><a href="?action=profile&method=update"><img class="ui-li-mymed" alt="Date de naissance: " src="<?= APP_ROOT ?>/img/birthday_icon.png" width="50"
				Style="margin-left: 5px; top: 5px;" /> <?= _("Date of birth")?>
				<p>
					<?= $_SESSION['user']->birthday ?>
				</p>
				</a>
			</li>
			<li data-icon="pencil"><a href="?action=profile&method=update"><img class="ui-li-mymed" alt="Langue: " src="<?= APP_ROOT ?>/img/<?= $_SESSION['user']->lang ?>_flag.png" width="50"
				Style="margin-left: 5px; top: 5px;" /> <?= _("Language")?>
				<p>
					<?= $_SESSION['user']->lang ?>
				</p>
				</a>
			</li>

			<li>
				<p style="text-align: right">
				<!-- Upgrade profile from facebook/google+ to myMed account. Impossible from twitter (no email) -->
				<? if(isset($_SESSION['userFromExternalAuth']) && (!isset($_SESSION['user']->login)) && $_SESSION['userFromExternalAuth']->socialNetworkName!="Twitter-OAuth"): ?>
					<a type="button" href="?action=UpgradeAccount&method=migrate" data-icon="pencil" data-inline="true" data-theme="g"><?= _('Create a myMed profile') ?></a>
			 	<? endif; ?>
				 	<a type="button" href="#popupDeleteProfile" data-rel="popup" data-theme="r" data-inline="true">
						<img style="position:relative; margin-right: 3px;" class="ui-li-mymed" alt="Delete profile" align="left" src="<?= APP_ROOT ?>/img/remove_user.png" width="25px"/><?= _("Delete my profile") ?>
					</a>
				</p>
			</li>

			<li data-role="list-divider"><?= _("Profile in your applications") ?></li>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) { ?>
			<?php if ($status == "on") { ?>
			<li><a href="<?= APP_ROOT ?>/../<?= $applicationName ?>/index.php?action=extendedProfile&user=<?= $_SESSION['user']->id?>" rel="external"> <img class="ui-li-mymed"
					alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" width="50" Style="margin-left: 5px; top: 5px;" /> <?= $applicationName ?>
					<div Style="position: relative; left: 0px;">
						<?php for($i=1 ; $i <= 5 ; $i++) { ?>
						<?php if($i*20-20 < $_SESSION['reputation'][STORE_PREFIX . $applicationName] ) { ?>
						<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="10" Style="left: <?= $i ?>0px;" />
						<?php } else { ?>
						<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="10" Style="left: <?= $i ?>0px;"/>
						<?php } ?>
						<? } ?>
					</div>
			</a>
			</li>
			<?php } 
		    } ?>
	    </ul>
		
		<br /><br />
	</div>
	
	<!-- Pop up delete -->	
	<div data-role="popup" id="popupDeleteProfile" class="ui-content" Style="text-align: center; width: 18em;">
			<?php echo _("Are you sure you want to delete your myMed Profile ?"); ?> 
			<br /><br />
			<fieldset class="ui-grid-a; center-wrapper">
				<div class="ui-block-a; center-wrapper">
					<form action="?action=profile&method=delete" method="POST" data-ajax="false">
						<?php if(isset($_SESSION['user']->login)): ?> 
							<label for="password"> <?= _("Password:") ?></label>
							<input type="password" name="password" id="password" value="" type="text">
						<? endif; ?>
     					
						<input data-role="button" type="submit" data-theme="g" data-icon="ok" data-inline="true" value="<?= _('Yes') ?>" />
						<a href="#" data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>
					</form>
				</div>
			</fieldset>
	</div>
	
</div>

<? include_once 'footer.php'; ?>


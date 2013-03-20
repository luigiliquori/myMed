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
<!-- ----------------------------------------------- -->
<!-- ExtendedProfileDisplay View                     -->
<!-- Displays the details of user's extended profile -->
<!-- ----------------------------------------------- -->


<!-- Header bar functions -->
<? require_once('header-bar.php'); ?>


<!-- Notifications pop up -->
<? require_once('notifications.php'); ?>


<!-- Page view -->
<div data-role="page" id="extendedprofiledisplayview">

	<!-- Header bar -->	
<?  if($_GET['user'] != $_SESSION['user']->id) 
  		$title = _("Profile");
  	else
  		$title = _("My profile"); 	
	// Check the previous usr for the back button, if it is a publication details
  	if(strpos($_SERVER['HTTP_REFERER'],"?action=details&id") || strpos($_SERVER['HTTP_REFERER'],"?action=admin") 
			|| strpos($_SERVER['HTTP_REFERER'],"?action=Candidature&method=show_all_candidatures") || strpos($_SERVER['HTTP_REFERER'],"?action=Volunteer&method=show_all_volunteers")) {
		print_header_bar('back', "defaultHelpPopup", $title);
  	} elseif(strpos($_SERVER['HTTP_REFERER'],"?action=profile")) {
			print_header_bar("back", false, $title);
	} else {
		print_header_bar("?action=main", "defaultHelpPopup", $title);
	}
	
	
?>
	
	<!-- Page content -->
	<div data-role="content">

	 <? if (isset($_SESSION['myBenevolat']) && $_GET['user'] == $_SESSION['user']->id ): ?>
			<!-- Print profile type -->
			<div data-role="header" data-theme="e">
				<h1 style="white-space: normal;">
				<?  if($_SESSION['myBenevolat']->details['type']=='association'){
						if($_SESSION['myBenevolat']->permission == 0) {
							echo _("Your association has not been validated yet");
						} elseif($_SESSION['myBenevolat']->permission == 1) {
							echo _("Validated association");
						} elseif($_SESSION['myBenevolat']->permission == 2) {
							echo _("Administrator");
						}
					}else if($_SESSION['myBenevolat']->details['type']=='volunteer'){
						echo _("Volunteer");
					}
					?>
				</h1>
			</div>
	 <? endif; ?>
		<br>
		
		<!-- Show user profile -->
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d" style="margin-top: 2px;">
			
			<!-- User details -->
			<li data-role="list-divider"><?= _("User details") ?></li>	
			<li>
				<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
					<? if($_GET['user'] != $_SESSION['user']->id){ ?>
						<div class="ui-block-a" style="width: 110px;">
							<img src="<?= $this->basicProfile['profilePicture'] ?>" style="width: 80px; vertical-align: middle; padding-right: 10px;"/>
						</div>
						<div class="ui-block-b">
							<p><strong><?= $this->basicProfile['name'] ?></strong></p>
							<p><?= $this->basicProfile['birthday'] ?> </p>
							<p>
							<?  $lang="";
								if($this->basicProfile['lang']){
							   		if($this->basicProfile['lang']=="en") $lang=_("English");
									else if($this->basicProfile['lang']=="it") $lang=_("Italian");
									else $lang=_("French");
								}echo $lang;
							?>
							</p>
							<p><a href="mailto:<?= prettyprintId($this->basicProfile['email']) ?>"><?= prettyprintId($this->basicProfile['email']) ?></a></p>
						</div>
				 <? }else{ ?>
				 		<div class="ui-block-a" style="width: 110px;">
							<img src="<?= $_SESSION['user']->profilePicture ?>" style="width: 80px; vertical-align: middle; padding-right: 10px;"/>
						</div>
						<div class="ui-block-b">
							<p><strong><?= $_SESSION['user']->name ?></strong></p>
							<p><?= $_SESSION['user']->birthday ?> </p>
							<p><?
								$lang= _("Langage not defined");
								if($_SESSION['user']->lang){
									if($_SESSION['user']->lang=="en") $lang=_("English");
									else if($_SESSION['user']->lang=="it") $lang=_("Italian");
									else $lang=_("French");
								}echo $lang;
							?></p>
							<p><a href="mailto:<?= prettyprintId($_SESSION['user']->id) ?>"><?= prettyprintId($_SESSION['user']->id) ?></a></p>
						</div>
				 <? } ?>
				</div>
			</li>
			
			<!-- myBenevolatExtended profile details -->
			<li data-role="list-divider"><?= _($this->profile->details['type']." profile details") ?></li>	
			<li>
				<p>
					<!-- Role <b><?= _("Profile type") ?></b>: <strong style="color:#444;"><?= _($this->profile->details['type']) ?></strong><br/> -->
					<?= (empty($this->profile->details['associationname']) ? " " : "<b>"._("Association name").": </b>".$this->profile->details['associationname']."<br/><br/>") ?>
				</p>
				<p>
					<?=					
					//(empty($this->profile->details['email'])? " " : "<b>"._("email").":</b> <a href='mailto:".$this->profile->details['email']."' >".$this->profile->details['email']."</a><br/>").
					(empty($this->profile->details['phone'])? " " : "<b>"._("phone").":</b> <a href='tel:".$this->profile->details['phone']."' >".$this->profile->details['phone']."</a><br/>").
					(empty($this->profile->details['address'])?" ": "<b>"._("address").":</b>"."<span>".$this->profile->details['address']."</span><br/>")
					?>
				</p>
				<!-- Role's fields -->
				<?php 
					// Render role's fields
					switch($this->profile->details['type']) {
						
						case 'volunteer':
							echo empty($this->profile->details['sex']) ? " " : "<p><b>". _("Sex").": </b>"."<span>"._($this->profile->details['sex'])."</span></p>";
							echo empty($this->profile->details['work']) ? " " : "<p><b>". _("Working status").": </b>"."<span>"._($this->profile->details['work'])."</span></p>";
							echo "<br/>";
							
							echo "<p><b>". _("Disponibility").":</b><br/><p style='margin-left:20px'>";
							$tokens = explode(" ", $this->profile->details['disponibilite']);
							array_pop($tokens);
							foreach($tokens as $token) {
								echo Categories::$disponibilites[$token]."<br/>";
							}
							echo "</p></p><br/>";
							echo "<p><b>". _("Skills").":</b><br/><p style='margin-left:20px'>";
							$tokens = explode(" ", $this->profile->details['competences']);
							array_pop($tokens);
							foreach($tokens as $token) {
								echo Categories::$competences[$token]."<br/>";
							}
							echo "</p></p><br/>";
							echo "<p><b>". _("Missions").":</b><br/><p style='margin-left:20px'>";
							$tokens = explode(" ", $this->profile->details['missions']);
							array_pop($tokens);
							foreach($tokens as $token) {
								echo Categories::$missions[$token]."<br/>";
							}
							echo "</p></p><br/>";
							echo "<p><b>". _("Districts").":</b><br/><p style='margin-left:20px'>";
							$tokens = explode(" ", $this->profile->details['mobilite']);
							array_pop($tokens);
							foreach($tokens as $token) {
								echo Categories::$mobilite[$token]."<br/>";
							}
							echo "</p></p><br/>";
							
							break;
						
						case 'admin':
						case 'association' :
							echo empty($this->profile->details['siret']) ? " " : "<p><b>". _("SIRET").": </b>"."<span>".$this->profile->details['siret']."</span></p>";
							echo empty($this->profile->details['website']) ? " " : "<p><b>". _("Website").": </b>"."<span><a href='".$this->profile->details['website']."'>".$this->profile->details['website']."</a></span></p>";
							echo "<br/>";
							echo "<p><b>". _("Skills needed").":</b><br/><p style='margin-left:20px'>";
							$tokens = explode(" ", $this->profile->details['competences']);
							array_pop($tokens);
							foreach($tokens as $token) {
								echo Categories::$competences[$token]."<br/>";
							}
							echo "</p></p><br/>";
							echo "<p><b>". _("Missions").":</b><br/><p style='margin-left:20px'>";
							$tokens = explode(" ", $this->profile->details['missions']);
							array_pop($tokens);
							foreach($tokens as $token) {
								echo Categories::$missions[$token]."<br/>";
							}
							
							break;
							
					}
				?>
				<br/>

				<!-- Reputation -->
				<p class="ui-li-aside">
					<?= _("reputation")?>: <?= $this->profile->reputation ?>% (<?= $this->nbrates ?> <?= _("rates")?>)
				</p>
				<br />
				
					
			</li>	
		</ul> <!-- END show user profile -->	
		
		
		<!-- Edit profile, Delete and Show publications buttons -->
		<? if (isset($_SESSION['myBenevolat']) && $_GET['user'] == $_SESSION['user']->id ): ?>
		<div style="text-align: center;">
			<br />
			<!-- Edit profile-->
			<a type="button" href="?action=ExtendedProfile&method=edit"  data-theme="d" data-icon="edit" data-inline="true" data-ajax="false"><?= _('Edit my profile') ?></a>
			
			<!-- Upgrade profile from facebook/google+ to myMed account. Impossible from twitter (no email) -->
		 <? if(isset($_SESSION['userFromExternalAuth']) && (!isset($_SESSION['user']->login)) && $_SESSION['userFromExternalAuth']->socialNetworkName!="Twitter-OAuth"): ?>
				<a type="button" href="?action=UpgradeAccount&method=migrate"  data-theme="g" data-icon="pencil" data-inline="true"><?= _('Create a myMed profile') ?></a>
		 <? endif; ?>
			
			<!-- Delete profile-->
			<a type="button" href="#popupDeleteProfile" data-theme="d" data-rel="popup" data-icon="delete" data-inline="true"><?= _('Delete my profile') ?></a>
			<!-- Pop up delete profile -->	
			<div data-role="popup" id="popupDeleteProfile" class="ui-content" Style="text-align: center; width: 18em;">
				<?= _("Are you sure you want to delete your profile ?") ?><br /><br />
				<a type="button" href="?action=ExtendedProfile&method=delete"  data-theme="g" data-icon="ok" data-inline="true"><?= _('Yes') ?></a>
				<a href="#" data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>
			</div>
			<!-- List of user subscriptions -->
			<br />
		 <? if (isset($_SESSION['myBenevolat'])): ?>
				<a type="button" href="?action=mySubscriptionManagement" data-theme="d" data-icon="grid" data-inline="true" data-ajax="false" <?= (($_SESSION['myBenevolat']->details['type']=='association' && $_SESSION['myBenevolat']->permission == '0')) ? " class='ui-disabled'" : "" ?>><?= _("Manage subscriptions") ?></a>
		 <? endif; ?>
		</div> <!-- END Edit profile, Delete and Show publications buttons -->
	<? endif; ?>
	
	</div> <!-- END Page content -->
	
	
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Display profile help title") ?></h3>
		<p> <?= _("Display profile help text") ?></p>
	</div>
	
	
</div>
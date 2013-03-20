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
<!-- ----------------------------- -->
<!-- ExtendedProfileDisplay View   -->
<!-- ----------------------------- -->


<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="extendedprofiledisplayview">

	<!-- Header bar -->
<?  if($_GET['user'] != $_SESSION['user']->id) {
  		$title = _("Profile");
	} else {
  		$title = _("My profile");
	}
	// Check the previous usr for the back button, if it is a publication details
  	if(strpos($_SERVER['HTTP_REFERER'],"?action=details&predicate")) {
  	   	print_header_bar('back', "helpPopup", $title); 
	} elseif(strpos($_SERVER['HTTP_REFERER'],"?action=profile")) {
  		print_header_bar("back", false, $title);
  	} else {
		print_header_bar("?action=main", "helpPopup", $title);
  	}
  	   ?>
	
	<!-- Page content -->
	<div data-role="content">
		
		<br><br>
		
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
							   		if($_SESSION['user']->lang=="en") $lang=_("English");
									else if($_SESSION['user']->lang=="it") $lang=_("Italian");
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
			
			<!-- MyEduExtended profile details -->
			<li data-role="list-divider"><?= _("MyEdu extended profile details") ?></li>	
			<li>
				<p>
					<!-- Role -->
					<?= _("Role") ?>: <strong style="color:#444;"><?= _($this->profile->details['role']) ?></strong><br/>
				</p>
				<p>
					<?=
					//(empty($this->profile->details['email'])?" ": _("email").": <a href='mailto:".$this->profile->details['email']."' >".$this->profile->details['email']."</a><br/>").
					(empty($this->profile->details['phone'])?" ":_("phone").": <a href='tel:".$this->profile->details['phone']."' >".$this->profile->details['phone']."</a><br/>")
					//(empty($this->profile->details['address'])?" ":_("address").": "."<span>".$this->profile->details['address']."</span><br/>")
					?>
				</p>
				<!-- Role's fields -->
				<?php 
					// Render role's fields
					switch($this->profile->details['role']) {
						
						case 'student':
							echo empty($this->profile->details['studentnumber']) ? " " : "<p>". _("Student number").": "."<span>".$this->profile->details['studentnumber']."</span></p>";
							echo empty($this->profile->details['faculty']) ? " " : "<p>". _("Faculty").": "."<span>".$this->profile->details['faculty']."</span></p>";							
							break;
						
						case 'professor':
							echo empty($this->profile->details['university']) ? " " : "<p>". _("University").": "."<span>".$this->profile->details['university']."</span></p>";
							echo empty($this->profile->details['courses']) ? " " : "<p>". _("Courses").": "."<span>".$this->profile->details['courses']."</span></p>";
							break;
						
						case 'company':
							echo empty($this->profile->details['companytype']) ? " " : "<p>". _("Company Type").": "."<span>".$this->profile->details['companytype']."</span></p>";
							echo empty($this->profile->details['siret']) ? " " : "<p>". _("Siret").": "."<span>".$this->profile->details['siret']."</span></p>";
							break;
								
					}
				?>
				<br/>
				<p>
					<?= _("Description")?>: <p style="margin-left:30px"><?= empty($this->profile->details['desc'])?" ":$this->profile->details['desc'] ?></p>
				</p>
				
				
				<br />
				<? if(($this->profile->details['role']!='professor')): ?>
				<p class="ui-li-aside">
					<?= _("reputation")?>: <?= $this->profile->reputation ?>% (<?= $this->nbrates ?> <?= _("rates")?>)
				</p>
				<br />
				<?php endif; ?>
					
			</li>	
		</ul> <!-- END show user profile -->	
		
		
		<!-- Edit profile, Delete and Show publications buttons -->
		<? if (isset($_SESSION['myEdu']) && $_GET['user'] == $_SESSION['user']->id ): ?>
		<div style="text-align: center;">
			<br />
			<!-- Edit profile-->
			<a type="button" href="?action=ExtendedProfile&method=edit"  data-theme="d" data-icon="pencil" data-inline="true"><?= _('Edit my profile') ?></a>
			
			<!-- Upgrade profile from facebook/google+ to myMed account. Impossible from twitter (no email) -->
		 <? if(isset($_SESSION['userFromExternalAuth']) && (!isset($_SESSION['user']->login)) && $_SESSION['userFromExternalAuth']->socialNetworkName!="Twitter-OAuth"): ?>
				<a type="button" href="?action=UpgradeAccount&method=migrate"  data-theme="g" data-icon="pencil" data-inline="true"><?= _('Create a myMed profile') ?></a>
		 <? endif; ?>
		 
			<!-- Delete profile-->
			<a type="button" href="#popupDeleteProfile" data-theme="d" data-rel="popup" data-icon="delete" data-inline="true"><?= _('Cancel my profile') ?></a>
			<!-- Pop up delete profile -->	
			<div data-role="popup" id="popupDeleteProfile" class="ui-content" Style="text-align: center; width: 18em;">
				<?= _("Are you sure you want to delete your profile ?") ?><br /><br />
				<a type="button" href="?action=ExtendedProfile&method=delete"  data-theme="g" data-icon="ok" data-inline="true"><?= _('Yes') ?></a>
				<a href="#" data-role="button" data-icon="delete" data-inline="true" data-theme="r" data-rel="back" data-direction="reverse"><?= _('No') ?></a>
			</div>
			<!-- List of user subscriptions -->
			<br />
			<a type="button" href="?action=myOpportunityManagement" data-theme="d" data-icon="grid" data-inline="true" data-ajax="false"><?= _("Manage subscriptions") ?></a>
		</div> <!-- END Edit profile, Delete and Show publications buttons -->
		<? endif; ?>
	
	</div> <!-- END Page content -->
	
	
	<!-- Help popup -->
	<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("You can customize your profile with e.g. language, photo, web page, work, telephone, affiliation by click on the \"Edit my profile\" button.<br>You can cancel your myEdu profile by clicking on the \"Cancel my profile\" button.") ?></p>
	</div>
	
	
</div>
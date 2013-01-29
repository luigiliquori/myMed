<!-- ----------------------------- -->
<!-- ExtendedProfileDisplay View   -->
<!-- ----------------------------- -->


<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="extendedprofiledisplayview">

	<!-- Header bar -->
  	<? 	$title = _("Profile");
  	  	// Check the previous usr for the back button, if it is a publication details
  	  	if(strpos($_SERVER['HTTP_REFERER'],"?action=details&predicate"))
  	   		print_header_bar($_SERVER['HTTP_REFERER'], "defaultHelpPopup", $title, 'back to Publication'); 
  	  	else
  	   		print_header_bar("?action=main", "defaultHelpPopup", $title, 'myEdu Home');
  	   ?>
	
	<!-- Page content -->
	<div data-role="content">
		
		<br><br>
		<?php	
	   		// Select language
			$lang="";
			if($_SESSION['user']->lang=="en") $lang=_("English");
			else if($_SESSION['user']->lang=="it") $lang=_("Italian");
			else $lang=_("French");
		?>
		
		<!-- Show user profile -->
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d" style="margin-top: 2px;">
			
			<!-- User details -->
			<li data-role="list-divider"><?= _("User details") ?></li>	
			<li>
				<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
					<div class="ui-block-a" style="width: 110px;">
						<a title="<?= $this->profile->details['name'] ?>"><img src="<?= $this->profile->details['picture'] ?>"style="width: 80px; vertical-align: middle; padding-right: 10px;"></a>
					</div>
					<div class="ui-block-b">
						<p><strong><?= $this->profile->details['firstname']." ".$this->profile->details['lastname'] ?></strong></p>
						<p><?= $this->profile->details['birthday'] ?> </p>
						<p><?= $lang?></p>
						<p><a href="mailto:<?= prettyprintId($this->profile->details['email']) ?>"><?= prettyprintId($this->profile->details['email']) ?></a></p>
					</div>
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
					<img src="./img/email_icon.png" style="height: 22px;vertical-align: bottom;"/>
					<?=
					(empty($this->profile->details['email'])?" ": _("email").": <a href='mailto:".$this->profile->details['email']."' >".$this->profile->details['email']."</a><br/>").
					(empty($this->profile->details['phone'])?" ":_("phone").": <a href='tel:".$this->profile->details['phone']."' >".$this->profile->details['phone']."</a><br/>").
					(empty($this->profile->details['address'])?" ":_("address").": "."<span>".$this->profile->details['address']."</span><br/>")
					?>
				</p>
				<!-- Role's fields -->
				<?php 
					// Render role's fields
					switch($this->profile->details['role']) {
						
						case 'student':
							echo empty($this->profile->details['studentnumber']) ? " " : "<p>". _("Student Number").": "."<span>".$this->profile->details['studentnumber']."</span></p>";
							echo empty($this->profile->details['faculty']) ? " " : "<p>". _("Faculty").": "."<span>".$this->profile->details['faculty']."</span></p>";							break;
						
						case 'professor':
							echo empty($this->profile->details['university']) ? " " : "<p>". _("University").": "."<span>".$this->profile->details['university']."</span></p>";
							echo empty($this->profile->details['courses']) ? " " : "<p>". _("Courses").": "."<span>".$this->profile->details['Courses']."</span></p>";
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
				
				<p class="ui-li-aside">
					<?= _("reputation")?>: <a href="#reppopup" style="font-size: 16px;" title="<?= $this->reputation['up'] ?> votes +, <?= $this->reputation['down'] ?> votes -"><?= $this->reputation['up'] - $this->reputation['down'] ?></a>
				</p>
				<br />
					
			</li>	
		</ul> <!-- END show user profile -->	
		
		
		<!-- Edit profile, Delete and Show publications buttons -->
		<? if (isset($_SESSION['myEdu']) && $_GET['user'] == $_SESSION['user']->id ): ?>
		<div style="text-align: center;">
			<br />
			<!-- Edit profile-->
			<a type="button" href="?action=ExtendedProfile&method=edit"  data-theme="d" data-icon="edit" data-inline="true"><?= _('Edit my profile') ?></a>
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
				<? if (isset($_SESSION['myEdu'])): ?>
				<a type="button" href="?action=" data-theme="d" data-icon="grid" data-inline="true" data-ajax="false"><?= _("My opportunities") ?></a>
				<? endif; ?>
		</div> <!-- END Edit profile, Delete and Show publications buttons -->
		<? endif; ?>
		
	
	</div> <!-- END Page content -->
	
	
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit Profile and Subscripions") ?></h3>
		<p> <?= _("Here you can modify your profile and list your active subscriptions.") ?></p>
	</div>
	
	
</div>
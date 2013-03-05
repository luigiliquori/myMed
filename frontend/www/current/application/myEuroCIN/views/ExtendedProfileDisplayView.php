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
  	<? 	$title = _("Profile");
  	  	// Check the previous usr for the back button, if it is a publication details
  	  	if(strpos($_SERVER['HTTP_REFERER'],"?action=details&predicate"))
  	   		//print_header_bar($_SERVER['HTTP_REFERER'], "defaultHelpPopup", $title); 
  	   		print_header_bar('back', "defaultHelpPopup", $title); 

  	  	else
  	   		print_header_bar("?action=main", "defaultHelpPopup", $title);
  	   ?>
	
	<!-- Print profile type -->
<?  if($_SESSION['myEuroCIN']->permission == 2) { ?>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;">
				<? echo _("Administrator"); ?>
			</h1>
		</div>
<? } ?>
	
	<!-- Page content -->
	<div data-role="content">
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
							<p><strong><?= $this->basicProfile['firstName']." ".$this->basicProfile['lastName'] ?></strong></p>
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
							<p><strong><?= $_SESSION['user']->firstName." ".$_SESSION['user']->lastName ?></strong></p>
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
			
			<!-- myEuroCINExtended profile details -->
			<li data-role="list-divider"><?= _("myEuroCIN extended profile details") ?></li>	
			<li>
				<p>
					<?=
					//(empty($this->profile->details['email'])?" ": _("email").": <a href='mailto:".$this->profile->details['email']."' >".$this->profile->details['email']."</a><br/>").
					(empty($this->profile->details['phone'])?" ":_("phone").": <a href='tel:".$this->profile->details['phone']."' >".$this->profile->details['phone']."</a><br/>").
					(empty($this->profile->details['address'])?" ":_("address").": "."<span>".$this->profile->details['address']."</span><br/>")
					?>
				</p>
				<!-- Role's fields -->
				<?php 
					/* Render role's fields
					switch($this->profile->details['role']) {
						
						case 'Role_1':
							echo empty($this->profile->details['role1field1']) ? " " : "<p>". _("Role 1 field 1").": "."<span>".$this->profile->details['role1field1']."</span></p>";
							echo empty($this->profile->details['role1field2']) ? " " : "<p>". _("Role 1 field 2").": "."<span>".$this->profile->details['role1field2']."</span></p>";							break;
						
						case 'Role_2':
							echo empty($this->profile->details['role2field1']) ? " " : "<p>". _("Role 2 field 1").": "."<span>".$this->profile->details['role2field1']."</span></p>";
							echo empty($this->profile->details['role2field2']) ? " " : "<p>". _("Role 2 field 2").": "."<span>".$this->profile->details['role2field2']."</span></p>";
							break;

								
					}*/
				?>
				<br/>
				<p>
					<?= _("Description")?>: <p style="margin-left:30px"><?= empty($this->profile->details['desc'])?" ":$this->profile->details['desc'] ?></p>
				</p>

				<br />	
				<p class="ui-li-aside">
					<?= _("reputation")?>: <?= $this->profile->reputation ?>% (<?= $this->nbrates ?> rates)
				</p>
				<br />
					
			</li>	
		</ul> <!-- END show user profile -->	
		
		
		<!-- Edit profile, Delete and Show publications buttons -->
		<? if (isset($_SESSION['myEuroCIN']) && $_GET['user'] == $_SESSION['user']->id ): ?>
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
		</div> <!-- END Edit profile, Delete and Show publications buttons -->
		<? endif; ?>
	
	</div> <!-- END Page content -->
	
	
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit Profile and Subscripions") ?></h3>
		<p> <?= _("Here you can modify your profile.") ?></p>
	</div>
	
	
</div>
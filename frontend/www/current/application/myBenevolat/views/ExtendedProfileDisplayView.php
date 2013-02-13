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
  	<? 	$title = _("Your Profile");
  	  	// Check the previous usr for the back button, if it is a publication details
  	  	if(strpos($_SERVER['HTTP_REFERER'],"?action=details&predicate"))
  	   		//print_header_bar($_SERVER['HTTP_REFERER'], "defaultHelpPopup", $title); 
  	   		print_header_bar('back', "defaultHelpPopup", $title); 

  	  	else
  	   		print_header_bar("?action=main", "defaultHelpPopup", $title);
  	   ?>
	
	<!-- Print profile type -->
	<div data-role="header" data-theme="e">
		<br/>
		<h1 style="white-space: normal;">
			<?php if($_SESSION['myBenevolat']->details['type']=='association') :?>
				<?php if($_SESSION['myBenevolat']->permission == 0) {
					echo _("Your association has not been validated yet");
				} elseif($_SESSION['myBenevolat']->permission == 1) {
					echo _("Validated association");
				} elseif($_SESSION['myBenevolat']->permission == 2) {
					echo _("Administrator");
				} ?>
			<?php elseif($_SESSION['myBenevolat']->details['type']=='Volunteer'):?>
				<?= _("Volunteer")?>
			<? endif;?>
		</h1>
		<br/>
	</div>
	
	<!-- Page content -->
	<div data-role="content">
		
		<br><br>
		<?php	
	   		// Select language
			$lang="No defined";
	   		if($_SESSION['user']->lang){
				if($_SESSION['user']->lang=="en") $lang=_("English");
				else if($_SESSION['user']->lang=="it") $lang=_("Italian");
				else $lang=_("French");
			}
		?>
		
		<!-- Show user profile -->
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d" style="margin-top: 2px;">
			
			<!-- User details -->
			<li data-role="list-divider"><?= _("User details") ?></li>	
			<li>
				<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
					<div class="ui-block-a" style="width: 110px;">
						<img src="<?= $this->profile->details['picture'] ?>"style="width: 80px; vertical-align: middle; padding-right: 10px;"/>
					</div>
					<div class="ui-block-b">
						<p><strong><?= $this->profile->details['firstName']." ".$this->profile->details['lastName'] ?></strong></p>
						<p><?= $this->profile->details['birthday'] ?> </p>
						<p><?= $lang?></p>
						<p><a href="mailto:<?= prettyprintId($this->profile->details['email']) ?>"><?= prettyprintId($this->profile->details['email']) ?></a></p>
					</div>
				</div>
			</li>
			
			<!-- myBenevolatExtended profile details -->
			<li data-role="list-divider"><?= _($this->profile->details['type']." profile details") ?></li>	
			<li>
				<p>
					<!-- Role -->
					<b><?= _("Profile type") ?></b>: <strong style="color:#444;"><?= _($this->profile->details['type']) ?></strong><br/>
				</p>
				<p>
					<img src="./img/mail-send.png" style="height: 22px;vertical-align: bottom;"/>
					<?=
					(empty($this->profile->details['email'])? " " : "<b>"._("email").":</b> <a href='mailto:".$this->profile->details['email']."' >".$this->profile->details['email']."</a><br/>").
					(empty($this->profile->details['phone'])? " " : "<b>"._("phone").":</b> <a href='tel:".$this->profile->details['phone']."' >".$this->profile->details['phone']."</a><br/>").
					(empty($this->profile->details['address'])?" ":_("address").": "."<span>".$this->profile->details['address']."</span><br/>")
					?>
				</p>
				<!-- Role's fields -->
				<?php 
					// Render role's fields
					switch($this->profile->details['type']) {
						
						case 'volunteer':
							echo empty($this->profile->details['sex']) ? " " : "<p><b>". _("Sex").": </b>"."<span>".$this->profile->details['sex']."</span></p>";
							echo empty($this->profile->details['work']) ? " " : "<p><b>". _("Working status").": </b>"."<span>".$this->profile->details['work']."</span></p>";
							echo "<br/>";
							echo "<p><b>". _("Competences").":</b><br/><p style='margin-left:20px'>";
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
							echo "<p><b>". _("Mobilite").":</b><br/><p style='margin-left:20px'>";
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
							echo empty($this->profile->details['website']) ? " " : "<p><b>". _("Website").": </b>"."<span>".$this->profile->details['website']."</span></p>";
							echo "<br/>";
							echo "<p><b>". _("Competences needed").":</b><br/><p style='margin-left:20px'>";
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
					<?= _("reputation")?>: <?= $this->profile->reputation ?>% (<?= $this->nbrates ?> rates)
				</p>
				<br />
				
					
			</li>	
		</ul> <!-- END show user profile -->	
		
		
		<!-- Edit profile, Delete and Show publications buttons -->
		<? if (isset($_SESSION['myBenevolat']) && $_GET['user'] == $_SESSION['user']->id ): ?>
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
				<? if (isset($_SESSION['myBenevolat'])): ?>
				<a type="button" href="?action=mySubscription&subscriptions=true" data-theme="d" data-icon="grid" data-inline="true" data-ajax="false"><?= _("My subscriptions") ?></a>
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
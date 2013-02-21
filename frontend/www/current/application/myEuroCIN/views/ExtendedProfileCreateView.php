<!-- --------------------------------------------------- -->
<!-- ExtendedProfileCreate View                          -->
<!-- Here you can create an extended profile in this app -->
<!-- You can specify different roles for users and 		 -->
<!-- profile attributes specifics to those roles         -->
<!-- --------------------------------------------------- -->


<!-- Header bars functions -->
<? require_once('header-bar.php'); ?>

<!-- Page view -->
<div data-role="page" id="extendedprofilecreateview" >
	
	
	<? require_once('Categories.class.php'); ?>  
  	
  	 
	<!-- Page header bar -->
	<? $title = _("Create profile");
	   print_header_bar("?action=main", "defaultHelpPopup", $title); ?>
	
	   
	<!-- Notification pop up -->
	<? include_once 'notifications.php'; ?>
	<? print_notification($this->success.$this->error); ?>
	
	   
	<!-- Page content -->
	<div data-role="content">
	
		<br>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time in myEuroCIN! Please create your extended profile.") ?></h1>
		</div>
		<br />

		
		<!-- Create extended profile form -->
		<form action="?action=ExtendedProfile&method=create" method="post" id="ExtendedProfileForm" data-ajax="false">
			
			<!-- These hidden fields are from the myMed profile and are also saved in the extended profile -->
			<input type="hidden" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			<input type="hidden" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
			<input type="hidden" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			<input type="hidden" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			<input type="hidden" id="picture" name="picture" value="<?= $_SESSION['user']->profilePicture ?>" />
			
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value='' type="tel" />
			</div>
			<!-- Description -->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description / <br/> Notes') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires" style="height:120px;"></textarea>
			</div>
			<br/>
			<!-- Accept terms and conditions -->
			<input id="service-term" type="checkbox" name="checkCondition" style="display: inline-block; top: 8px;"/>
			<span style="display:inline-block;margin-left: 40px;">
				<?= _("I accept the ")?>
				<a href="../myMed/doc/CGU_fr.pdf" rel="external"><?= _("general terms and conditions")?></a>
			</span>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="e" data-role="button" data-icon="gear" value="<?= _('Create this profile') ?>"/>
			</div>
		</form>
	</div>
	
	
	<!-- Help Pop Up -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Extended Profile Help") ?></h3>
		<p> <?= _("Choose your role and fill in all the fields for your application extended profile.") ?></p>
		
	</div>
	
</div>

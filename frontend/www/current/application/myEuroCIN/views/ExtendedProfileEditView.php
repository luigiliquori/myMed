<!-- ------------------------------------ -->
<!-- ExtendedProfileEdit View             -->
<!-- Edit a user extended profile details -->
<!-- ------------------------------------ -->


<!-- Header bar functions -->
<? require_once('header-bar.php'); ?>


<!-- Notification pop up -->
<? require_once('notifications.php'); ?>


<!-- Page view -->
<div data-role="page" id="extendedprofileeditview">

	<!-- Page header -->
	<? $title = _("Edit Profile");

	   print_header_bar(
	   		'index.php?action=extendedProfile&method=show_user_profile&user='
	   		.$_SESSION['user']->id.'', "defaultHelpPopup", $title); ?>

	   
	<!-- Page content -->
	<div data-role="content">
	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Extended profile edit form -->
		<form action="?action=ExtendedProfile&method=update" method="POST" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" id="role" name="role" value="<?= $_SESSION['myEuroCIN']->details['role'] ?>" />
			<input type="hidden" name="id" value="<?= $_SESSION['myEuroCIN']->profile ?>" />
			<input type="hidden" name="form" value="edit" />


			<!-- User profile details -->
			
			<!-- First Name -->
			<div data-role="fieldcontain">
				<label for="firstName" style="text-align:right"><?= _("First Name") ?> : </label>
				<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			</div>
			<!-- Last Name -->
			<div data-role="fieldcontain">
				<label for="lastName" style="text-align:right"><?= _("Last Name") ?> : </label>
				<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			</div>
			<!-- Birthday -->
			<div data-role="fieldcontain">
				<label for="birthday" style="text-align:right"><?= _("Birthday") ?> (jj/mm/aaaa) : </label>
				<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			</div>
			<!-- Profile picture -->
			<div data-role="fieldcontain">
				<label for="profilePicture" style="text-align:right"><?= _("Profile picture") ?> (url) : </label>
				<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			</div>
			<!-- User language -->
			<div data-role="fieldcontain">
				<label for="lang" style="text-align:right"><?= _("Language") ?>	: </label>
				<select id="lang" name="lang">
					<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
					<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
					<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
				</select>
			</div>
			<!--
			<p><strong> myEuroCIN Profile details </strong></p>
			 myEuroCIN Extended Profile details -->
			
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?><b>*</b> : </label>
				<input id="textinputu6" name="phone" placeholder="00 00 00 00 00" value="<?= $_SESSION['myEuroCIN']->details['phone'] ?>"  type="tel" />
			</div>
			<!-- Description -->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description') ?><b>*</b> : </label>
				<textarea id="desc" style="height: 120px;" name="desc"><?= $_SESSION['myEuroCIN']->details['desc'] ?></textarea>
			</div>
			<br/>
			<div data-role="fieldcontain">
				<label for="password" style="text-align:right"><?= _("Password") ?>:</label>
				<input type="password" id="password" name="password" />
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-theme="g" data-icon="ok" value="<?= _('Update') ?>"/>
			</div>
		</form>
		
	</div> <!-- END page-->
	
	
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit your Profile") ?></h3>
		<p> <?= _("Here you can update your organization profile.") ?></p>
	</div>
	
</div>
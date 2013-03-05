<?php

require_once("header.php");

?>

<div id="updateProfile" data-role="page">

	<?php tab_bar_main("?action=profile", 2); ?>
	<?php include 'notifications.php'; ?>
	
	<div data-role="content">
		<form action="?action=profile" id="updateProfileForm" method="post" data-ajax="false">
		
			<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>" />
			<input type="hidden" name="email" value="<?= $_SESSION['user']->email ?>" />
			
			<label for="firstName"><?= _("First name") ?> : </label>
			<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			
			<label for="lastName"><?= _("Last name") ?> : </label>
			<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			
			<!--<label for="email" ><?= _("E-mail")?> : </label>
			<input type="text" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />-->
			
			<label for="birthday" ><?= _("Date of birth") ?> : </label>
			<input type="text" id="birthday" name="birthday" placeholder="jj/mm/aaaa" value="<?= $_SESSION['user']->birthday ?>" />
			
			<label for="profilePicture" ><?= _("Profile picture") ?> (url): </label>
			<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			

			<label for="lang" ><?= _("Language") ?>	: </label>
			<select id="lang" name="lang">
				<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
				<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
				<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
			</select>

			<br>
			<div data-role="fieldcontain">
				<label for="password" ><?= _("Password") ?> : </label>
				<input type="password" id="password" name="password" />
			</div>


			<? if (!isset($_SESSION['user']->email)): /*oauthed user have no password for the moment*/ ?>
			<div data-role="fieldcontain">
				<label for="passwordConfirm" ><?= _("Password Confirmation") ?> : </label>
				<input type="password" id="passwordConfirm" name="passwordConfirm" />
			</div>
			<? endif; ?>

			<div style="text-align: center;">
				<input type="submit" data-role="button" data-inline="true" data-theme="b" value="<?= _("Update") ?>" data-icon="refresh"/>
			</div>
			
			
		</form>
	</div>
		
</div>

<? include_once 'footer.php'; ?>
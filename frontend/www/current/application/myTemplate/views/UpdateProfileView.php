<div id="updateProfile" data-role="page">

	<? print_header_bar(true, false); ?>

	<div data-role="content">
		
		<form action="?action=profile" id="updateProfileForm" method="post" data-ajax="false">
		
			<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>" />
			
			<label for="firstName"><?= _("First Name") ?> : </label>
			<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			
			<label for="lastName"><?= _("Last Name") ?> : </label>
			<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			
			<label for="email" >eMail : </label>
			<input type="text" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
			
			<label for="birthday" ><?= _("Birthday") ?> : </label>
			<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			
			<label for="profilePicture" ><?= _("Profile picture") ?> (url): </label>
			<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			

			<label for="lang" ><?= _("Language") ?>	: </label>
			<select id="lang" name="lang">
				<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>>Français</option>
				<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>>Italien</option>
				<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>>Anglais</option>
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
		
	<? print_footer_bar_main("#profile"); ?>
		
</div>

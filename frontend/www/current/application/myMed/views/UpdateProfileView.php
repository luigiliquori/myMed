<?php

require_once("header.php");
require_once("header-bar.php");
require_once("footer-bar.php");

?>

<div id="updateProfile" data-role="page">

	<?php print_header_bar(false, true) ?>
	<?php include 'notifications.php'; ?>
	
	<div data-role="content" class="content" Style="text-align: left;">
		<form action="?action=profile" id="updateProfileForm" method="post" data-ajax="false">
		
			<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>" />
			
			<label for="firstName">Prénom / Activité commerciale : </label>
			<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			
			<label for="lastName">Nom : </label>
			<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			
			<label for="email" >eMail : </label>
			<input type="text" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
			
			<label for="birthday" >Date de naissance : </label>
			<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			
			<label for="profilePicture" >Photo du profil (url): </label>
			<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			

			<label for="lang" >Langue	: </label>
			<select id="lang" name="lang">
				<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>>Francais</option>
				<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>>Italien</option>
				<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>>Anglais</option>
			</select>

			<label for="password" ><?= _("Password") ?> : </label>
			<input type="password" id="password" name="password" />

			<center>
				<div data-role="controlgroup" data-type="horizontal">
					<input type="submit" data-role="button" data-inline="true" data-theme="b" value="Mise à jour" data-icon="refresh"/>
					<a href="#profile" data-inline="true" rel="external" data-role="button" data-theme="d" data-icon="delete" data-iconpos="right">Annuler</a>
				</div>
			</center>
			
		</form>
	</div>
		
	<? print_footer_bar_main("?action=profile"); ?>
		
</div>

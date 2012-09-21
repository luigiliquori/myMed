<div id="updateProfile" data-role="page">

	<? tab_bar_main("?action=main#profile"); ?>
	<? include("notifications.php"); ?>
	
	<div data-role="content" class="content" Style="text-align: left;">
		<form action="?action=updateProfile" method="post" data-ajax="false">
		
			<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>" />
			
			<label for="firstName">Prénom / Activité commerciale : </label>
			<input type="text" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			<br />
			
			<label for="lastName">Nom : </label>
			<input type="text" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			<br />
			
			<label for="email" >eMail : </label>
			<input type="text" name="email" value="<?= $_SESSION['user']->email ?>" />
			<br />
			
<!-- 			<label for="oldPassword" >Ancien mot de passe : </label> -->
<!-- 			<input type="password" name="oldPassword" /> -->
<!-- 			<br /> -->
			
<!-- 			<label for="password" >Nouveau mot de passe : </label> -->
<!-- 			<input type="password" name="password" /> -->
<!-- 			<br /> -->
			
<!-- 			<label for="password" >Confirmation : </label> -->
<!-- 			<input type="password" name="confirm" /> -->
<!-- 			<br /> -->
			
			<label for="birthday" >Date de naissance : </label>
			<input type="text" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			<br />
			
			<label for="profilePicture" >Photo du profil (url): </label>
			<input type="text" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			<br />
			
			<label for="thumbnail" >Langue	: </label>
			<select name="lang">
				<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>>Francais</option>
				<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>>Italien</option>
				<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>>Anglais</option>
			</select>
			<br />
			
			<center>
				<div data-role="controlgroup" data-type="horizontal">
					<input type="submit" data-role="button" data-inline="true" data-theme="b" value="Mise à jour" />
					<a href="#profile" data-inline="true" rel="external" data-role="button" data-theme="d">Annuler</a>
				</div>
			</center>
			
		</form>
	</div>
		
</div>

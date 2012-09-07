<div id="updateProfile" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content" class="content" Style="text-align: left;">
		<form action="?action=updateProfile" method="post" data-ajax="false">
		
			<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>" />
			
			<label for="prenom">Prénom / Activité commerciale : </label>
			<input type="text" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			<br />
			
			<label for="nom">Nom : </label>
			<input type="text" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			<br />
			
			<label for="email" >eMail : </label>
			<input type="text" name="email" value="<?= $_SESSION['user']->email ?>" />
			<br />
			
			<label for="oldPassword" >Ancien mot de passe : </label>
			<input type="password" name="oldPassword" />
			<br />
			
			<label for="password" >Nouveau mot de passe : </label>
			<input type="password" name="password" />
			<br />
			
			<label for="password" >Confirmation : </label>
			<input type="password" name="confirm" />
			<br />
			
			<label for="birthday" >Date de naissance : </label>
			<input type="text" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			<br />
			
			<label for="thumbnail" >Photo du profil (url): </label>
			<input type="text" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			<br />
			
			<center>
				<div data-role="controlgroup" data-type="horizontal">
					<input type="submit" data-role="button" data-inline="true" data-theme="b" value="Mise à jour" />
					<a href="#profile" data-inline="true" rel="external" data-role="button" data-theme="d">Annuler</a>
				</div>
			</center>
			
		</form>
	</div>
		
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user"  class="ui-btn-active ui-state-persist">Profil</a></li>
				<li><a href="#store" data-transition="none" data-icon="star">Store</a></li>
			</ul>
		</div>
	</div>
		
</div>

</div>

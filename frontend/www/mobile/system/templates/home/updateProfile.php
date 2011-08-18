<div id="inscription" data-role="page">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b" >
		<h2>Mise à jour de votre profile</h2>
	</div>

	<!-- CONTENT -->
	<div id="content" data-role="content" id="one" data-theme="b">
		<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
			<input type="hidden" name="update" value="1" />
			<span>Prénom : </span><input type="text" name="prenom" value="<?= $_SESSION['user']->firstName ?>" /><br />
			<span>Nom : </span><input type="text" name="nom" value="<?= $_SESSION['user']->lastName ?>" /><br />
			<span>eMail : </span><input type="text" name="email" value="<?= $_SESSION['user']->email ?>" /><br />
			<span>Password : </span><input type="password" name="password" /><br />
			<span>Confirm : </span><input type="password" name="confirm" /><br />
			<span>Date de naissance : </span><input type="text" name="birthday" value="<?= $_SESSION['user']->birthday ?>" /><br />
			<span>Photo du profil : </span><input type="text" name="thumbnail" value="<?= $_SESSION['user']->profilePicture ?>" /><br />
			<a href="#" data-role="button" onclick="document.inscriptionForm.submit()">Mise à jour</a>
		</form>
	</div>
</div>
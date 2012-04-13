<div id="inscription" data-role="page" data-theme="d">
	<!-- HEADER -->
	<div data-role="header" data-theme="b" >
		<a href="#Option" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		<h2>Profil</h2>
	</div>

	<!-- CONTENT -->
	<div data-theme="c" style="text-align: left; padding: 10px;">
		<br />
		<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
			<input type="hidden" name="update" value="1" />
			<span>Prénom : </span><input type="text" name="prenom" value="<?= $_SESSION['user']->firstName ?>" /><br />
			<span>Nom : </span><input type="text" name="nom" value="<?= $_SESSION['user']->lastName ?>" /><br />
			<span>eMail : </span><input type="text" name="email" value="<?= $_SESSION['user']->email ?>" /><br />
			<span>Ancien Mot de passe : </span><input type="password" name="oldPassword" /><br />
			<span>Nouveau Mot de passe : </span><input type="password" name="password" /><br />
			<span>Confirmation : </span><input type="password" name="confirm" /><br />
			<span>Date de naissance : </span><input name="birthday" id="birthday" type="date" value="<?= $_SESSION['user']->birthday ?>" data-role="datebox"  data-options='{"mode": "slidebox"}' readonly="readonly">
			<br />
			
			<span>Photo du profil ( lien url ): </span><input type="text" name="thumbnail" value="<?= $_SESSION['user']->profilePicture ?>" /><br />
			<center>
			<a data-role="button" onclick="document.inscriptionForm.submit()" data-theme="b" data-inline="true">Mise à jour</a>
			</center>
		</form>
	</div>
</div>
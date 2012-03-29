<div id="inscription" data-role="page">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b" >
		<h2>Création d'un compte</h2>
	</div>

	<!-- CONTENT -->
	<div data-role="content" id="one" data-theme="b">
		<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
			<input type="hidden" name="inscription" value="1" />
			<span>Prénom : </span><br /><input type="text" name="prenom" /><br />
			<span>Nom : </span><br /><input type="text" name="nom" /><br />
			<span>eMail : </span><br /><input type="text" name="email" /><br />
			<span>Mot de passe : </span><br /><input type="password" name="password" /><br />
			<span>Confirmation : </span><br /><input type="password" name="confirm" /><br />
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">J'accepte les <a href="#condition">conditions d'utilisation</a></span><br />
			<a id="create" href="#" data-role="button" onclick="document.inscriptionForm.submit()">Création</a>
		</form>
	</div>
</div>

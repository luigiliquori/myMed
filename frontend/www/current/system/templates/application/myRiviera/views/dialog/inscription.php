<div id="inscription" data-role="page" data-theme="d">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b">
		<a href="#loginView" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		<h2>Profile</h2>
	</div>

	<!-- CONTENT -->
	<div data-theme="c" style="text-align: left; padding: 10px;">
		<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
			<input type="hidden" name="inscription" value="1" />
			<span>Prénom : </span><input type="text" name="prenom" value="" /><br />
			<span>Nom : </span><input type="text" name="nom" value="" /><br />
			<span>eMail : </span><input type="text" name="email" value="" /><br />
			<span>Mot de passe : </span><input type="password" name="password" /><br />
			<span>Confirmation : </span><input type="password" name="confirm" /><br />
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">J'accepte les <a href="#condition">conditions d'utilisation</a></span><br />
			<center>
			<a data-role="button" onclick="document.inscriptionForm.submit()" data-theme="b" data-inline="true">Valider</a>
			</center>
		</form>
	</div>
</div>

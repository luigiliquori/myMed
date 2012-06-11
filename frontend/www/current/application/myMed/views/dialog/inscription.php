<div id="inscription" data-role="page">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b" >
		<h2>Création d'un compte</h2>
	</div>

	<!-- CONTENT -->
	<div  id="one" data-theme="b" style="text-align: left; padding: 10px;">
		<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
			<input type="hidden" name="inscription" value="1" />
			<span>Prénom / Activité commerciale : </span><input type="text" name="prenom" value="" /><br />
			<span>Nom </span><input type="text" name="nom" value="" /><br />
			<span>eMail : </span><input type="text" name="email" value="" /><br />
			<span>Mot de passe : </span><input type="password" name="password" /><br />
			<span>Confirmation : </span><input type="password" name="confirm" /><br />
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a> / 
				I accept 
				<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a>
			</span><br />
			<center>
			<a data-role="button" onclick="document.inscriptionForm.submit()" data-theme="b" data-inline="true">Valider</a>
			</center>
		</form>
	</div>
</div>

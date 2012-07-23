
<? require_once("header.php"); ?>

<div data-role="page" id="register" >	

	<div data-role="header" data-theme="b">
		<h1>Création d'un compte</h1>
		<? include("notifications.php"); ?>
	</div>

	<div data-role="content">
	
		<!--  Register form -->
		<form action="index.php?action=register" method="post" data-ajax="false">
		
				<label for="prenom">Prénom / Activité commerciale : </label>
				<input type="text" name="prenom" value="" />
				<br />
				
				<label for="nom">Nom : </label>
				<input type="text" name="nom" value="" />
				<br />
				
				<label for="email" >eMail : </label>
				<input type="text" name="email" value="" />
				<br />
				
				<label for="password" >Mot de passe : </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" >Confirmation : </label>
				<input type="password" name="confirm" />
				<br />
				
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
				<span style="position: relative; left: 50px;">
					J'accepte les 
					<a href="<?= MYMED_ROOT ?>/application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a> / 
					I accept 
					<a href="<?= MYMED_ROOT ?>/application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a>
				</span><br />
				
				<center>
					<input type="submit" data-role="button" data-theme="b" data-inline="true" value="Valider" />
				</center>
		
		</form>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="b">
		<div data-role="navbar">
			<ul>
				<li><a href="#login" data-transition="none" data-back="true" data-icon="home">Connexion</a></li>
				<li><a href="#register" data-transition="none" data-back="true" data-icon="grid"  class="ui-btn-active ui-state-persist">Inscription</a></li>
				<li><a href="#about" data-transition="none" data-icon="info">A propos</a></li>
			</ul>
		</div>
	</div>
	
</div>

<? require_once("LoginView.php"); ?>

<? require_once("footer.php"); ?>

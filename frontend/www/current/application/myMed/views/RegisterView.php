
<? require_once("header.php"); ?>

<div data-role="page" id="register" >	

	<? include_once("header-bar-light.php"); ?>
	<? tabs_main("register", array(
			"about" => array(_('A propos'), "info"),
			"login" => array(_('Connexion'), "home"),
			"register" => array(_('Inscription'), "grid"),
	)) ?>
	<? include("notifications.php"); ?>

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
					<a href="application/myMed/doc/CGU_fr.pdf" rel="external">conditions d'utilisation</a> / 
					I accept 
					<a href="application/myMed/doc/CGU_en.pdf" rel="external">the general terms and conditions</a>
				</span><br />
				
				<center>
					<input type="submit" data-role="button" data-theme="b" data-inline="true" value="Valider" />
				</center>
		
		</form>
	</div>

	
</div>

<? require_once("LoginView.php"); ?>

<? require_once("footer.php"); ?>

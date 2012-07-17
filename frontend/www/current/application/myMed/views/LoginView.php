<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="login">

	<div data-role="header" data-theme="b">
		
		<h1><?= APPLICATION_NAME ?></h1>
		<? include("notifications.php")?>
		
	</div>
	
	<div data-role="content"  class="content">
	
		<form action="#" method="post" name="signinForm" id="signinForm">
			<input type="hidden" name="signin" value="1" />
		    <input type="text" name="login" id="login" placeholder="email"  data-theme="c"/><br />
		    <input type="password" name="password" id="password" placeholder="Mot de passe"  data-theme="c"/><br />
			<a href="http://www-sop.inria.fr/lognet/MYMED/" target="blank">A propos</a><br />
 		    <a href="#" onclick="document.signinForm.submit()" data-role="button" data-inline="true" data-theme="b" rel="external">Connexion</a>
 		    <a href="#register" data-role="button" data-inline="true" style="top:2px;">inscription</a>
		</form>
		
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
	</div>
	
</div>
	
<div data-role="page" id="register" >	

	<div data-role="header" data-theme="b">
		<h1>Création d'un compte</h1>
		<a href="#login" data-role="button" data-inline="true">Retour</a>
		<? include("notifications.php")?>
	</div>

	<div data-role="content">
	
		<!--  Register form -->
		<form  data-role="content" action="index.php?action=register" method="post" data-ajax="false">
		
				<label for="prenom"><?= _("Prénom") ?></label>
				<input id="prenom" type="text" name="prenom" value="" />
				<br/>
				
				<label for="nom"><?= _("Nom") ?></label>
				<input id="nom" type="text" name="nom" value="" />
				<br/>
				
				<label for="email" ><?= _("eMail") ?></label>
				<input type="text" id="email" name="email" value="" />
				<br/>
				
				<label for="password" ><?= _("Mot de passe") ?></label>
				<input type="password" name="password" />
				<br/>
				
				<label for="password" ><?= _("Mot de passe (confirmation)") ?></label>
				<input type="password" name="confirm" id="confirm" />
				<br/>
							
				<fieldset data-role="controlgroup">
	    			Lire les <a href="http://" rel="external" ><?= _("Conditions d'utilisation") ?></a>
	    			<input id="service-term" type="checkbox" name="checkCondition" />
					<label for="service-term"><?= _("J'accepte") ?></label>
				</fieldset>	 		
				<br/>
				
				<input type="submit" value="<?= _("Register") ?>" data-theme="b" />
		
		</form>
	</div>
	
</div>
<? include("footer.php"); ?>
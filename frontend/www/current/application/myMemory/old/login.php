<?php
/**
 * Page de login.
 * Je l'ai séparé des autres pages et ne fais pas appel a header.php afin de pouvoir faire une
 * redirection systématique vers cette page si l'utilisateur n'est pas connecté.
 * (si header est appellé ici, on crée une boucle infinie)
 * 
 * David
 */
	
	ob_start("ob_gzhandler");

	require_once dirname(__FILE__).'/TemplateManager.class.php';
	
	$template = new TemplateManager();
	$template->getHeader();
		
?>
<div data-role="page" id="Connect">
	<div data-role="header" data-position="fullscreen">
		<a href="index.php" class="homeButton ui-btn-left" data-transition="slide" data-direction="reverse">Back</a>
		<a href="#Quit" class="homeButton ui-btn-right" data-transition="pop" data-icon="delete">Quit</a>
		<h3>myMemory</h3>
	</div>
	<div data-role="content">
		<form action="authentification.php" method="post" id="loginForm" data-transition="pop" data-direction="reverse">
			<div data-role="fieldcontain">
					<label for="textinputl1"> Login: </label> <input id="textinputl1"  name="login" placeholder="" value="" type="text" />
			</div>
			<div data-role="fieldcontain">
					<label for="textinputl2"> Password: </label> <input id="textinputl2"  name="password" placeholder="" value="" type="password" />
			</div>
			<input type="submit"  value="Connecter" />
			<a href="registration" type="button" data-transition="flip">Créer un compte</a>
		</form>
	</div>
</div>
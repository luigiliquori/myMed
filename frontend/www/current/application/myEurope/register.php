<!DOCTYPE html>
<html>

<?php

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	
?>

	<head>
		<?= $template->head(); ?>
	</head>
	<body>
		<div data-role="page" id="Register">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="search" data-icon="back" data-transition="flip" data-direction="reverse"> Retour </a>
					<h3>myEurope - insertion</h3>
				</div>
				<div data-role="content">
					<?= isset($_GET['ok'])?"<div style='color:lightGreen;text-align:center;'>Compte créé, validez-le par mail</div>":"" ?>
					<form action="controller" method="post" id="registerForm">
						<input name="application" value="myEurope" type="hidden" />
						<input name="method" value="register" type="hidden" />
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputr1"> Prénom: </label> <input id="textinputr1"  name="prenom" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputr2"> Nom: </label> <input id="textinputr2"  name="nom" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputr3"> eMail: </label> <input id="textinputr3"  name="email" placeholder="" value="" type="email" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputr4"> Password: </label> <input id="textinputr4"  name="password" placeholder="" value="" type="password" />
							</fieldset>
						</div>
						<a href="" type="submit" onclick="$('#registerForm').submit();">Créer</a>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
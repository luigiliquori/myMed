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
		<div data-role="page" id="Authenticate">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="search" data-icon="back"> Retour </a>
					<h3>myEurope - login</h3>
				</div>
				<div data-role="content">
					<?= ($responseObject->status==200)?"<div style='color:lightGreen;text-align:center;'>Contenu publié</div>":"" ?>
					<form action="search" method="post" id="loginForm" data-ajax=false>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputl1"> Login: </label> <input id="textinputl1"  name="login" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputl2"> Password: </label> <input id="textinputl2"  name="password" placeholder="" value="" type="password" />
							</fieldset>
						</div>
						<a href="" type="submit" onclick="$('#loginForm').submit();">Connecter</a>
						<a href="register" type="button" data-transition="flip">Créer un compte</a>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
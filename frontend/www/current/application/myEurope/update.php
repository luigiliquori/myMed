<!DOCTYPE html>
<html>

<?php

	/*

	 *  
	 * what it does:
	 *  update your profile, by sending the form back to option
	 *  
	 */

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();

	// DEBUG
	//require_once('PhpConsole.php');
	//PhpConsole::start();

	session_start();
?>

	<head>
		<?= $template->head(); ?>
		<script type="text/javascript">
	    	$(".ui-slider-handle .ui-btn-inner").live("mouseup", function() {
		        // we can't update reputation from the profile
		        $("#slider-0").val(25).slider("refresh");
		    });
		</script>
	</head>


	<body>
		<div data-role="page" id="Update">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="option" class="ui-btn-left" data-icon="back" data-transition="flip" data-direction="reverse"> Retour</a>
					<h3>myEurope - MaJProfil</h3>
				</div>
				<div data-role="content">
					<form action="option" method="post" id="updateForm">
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu1"> Prénom: </label> <input id="textinputu1"  name="prenom" placeholder="" value="<?= $_SESSION['user']->firstName ?>" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu2"> Nom: </label> <input id="textinputu2"  name="nom" placeholder="" value="<?= $_SESSION['user']->lastName ?>" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu3"> eMail: </label> <input id="textinputu3"  name="email" placeholder="" value="<?= $_SESSION['user']->email ?>" type="email" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu4"> Ancien Password: </label> <input id="textinputu4"  name="oldPassword" placeholder="" value="" type="password" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu5"> Nouveau Password: </label> <input id="textinputu5"  name="password" placeholder="" value="" type="password" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu6"> Date de naissance: </label> <input id="textinputu6"  name="birthday" placeholder="" value="<?= $_SESSION['user']->birthday ?>" type="date" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu7"> Photo du profil (lien url): </label> <input id="textinputu7"  name="thumbnail" placeholder="" value="<?= $_SESSION['user']->profilePicture ?>" type="text" />
							</fieldset>
						</div>
						<a href="" type="button" data-icon="gear" onclick="$('#updateForm').submit();" style="width:90px; margin-right: auto; margin-left: auto;">Ok</a>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>

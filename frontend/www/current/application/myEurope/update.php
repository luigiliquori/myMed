<?php

	/*
	 *  
	 * what it does:
	 *  update your profile
	 *  
	 */

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	$template->checkSession();
?>

<!DOCTYPE html>
<html>
	<head>
		<?= $template->head(); ?>
	</head>


	<body>
		<div data-role="page" id="Update">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="option" class="ui-btn-left" data-icon="back" data-transition="flip" data-direction="reverse"> Retour</a>
					<h3>myEurope - Profil</h3>
				</div>
				<div data-role="content">
				
					<form action="option" method="post" id="updateForm">
					
						<div style='color:lightGreen;text-align:center;'><?= isset($_GET['extended'])?"Veuillez compléter votre profil, pour l'utilisation de myEurope":""?> </div>	
	
						<div data-role="fieldcontain">
							<fieldset id="test" data-role="controlgroup" data-type="horizontal" data-mini="true"  >
						     	<legend>Type d'institution:<?= isset($_GET['extended'])?" *":"" ?></legend>
						     	<input type="radio" name="type" id="radio-view-a" value="Assoc/Entrp" checked="checked"/>
						     	<label for="radio-view-a">Assoc/Entrp</label>
						     	<input type="radio" name="type" id="radio-view-b" value="Mairie" />
						     	<label for="radio-view-b">Mairie</label>
						     	<input type="radio" name="type" id="radio-view-c" value="Com Urb" />
						     	<label for="radio-view-c">Com Urb</label>
						     	<input type="radio" name="type" id="radio-view-d" value="Etat" />
						     	<label for="radio-view-d">Etat</label>
						     	<input type="radio" name="type" id="radio-view-e" value="Région" />
						     	<label for="radio-view-e">Région</label>
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu1"> Prénom: </label> <input id="textinputu1"  name="prenom" placeholder="" value='<?= $_SESSION['user']->firstName ?>' type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu2"> Nom: </label> <input id="textinputu2"  name="nom" placeholder="" value='<?= $_SESSION['user']->lastName ?>' type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu3"> eMail: </label> <input id="textinputu3"  name="email" placeholder="" value='<?= $_SESSION['user']->email ?>' type="email" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu4"> Ancien Mot de passe: </label> <input id="textinputu4"  name="oldPassword" placeholder="" value="" type="password" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu5"> Nouveau Mot de passe: </label> <input id="textinputu5"  name="password" placeholder="" value="" type="password" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu6"> Date de naissance: </label> <input id="textinputu6"  name="birthday" placeholder="" value='<?= $_SESSION['user']->birthday ?>' type="date" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputu7"> Photo du profil (lien url): </label> <input id="textinputu7"  name="thumbnail" placeholder="" value='<?= $_SESSION['user']->profilePicture ?>' type="text" />
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

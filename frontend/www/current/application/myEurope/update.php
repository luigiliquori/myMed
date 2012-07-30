<?php

/*
*  update your profile
*/

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();
Template::checksession();

$msg="";

if (isset($_POST['oldPassword'])) { //profile update
	require_once '../../lib/dasp/beans/MUserBean.class.php';
	require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';

	// update the authentication
	$msg="";
	if ( ($_POST["newPassword"] !=  $_POST["newPasswordConf"]) && $_POST["newPassword"] != "" ){
		$msg = "mot de passes non identiques";
	} else if ($_POST["newPassword"] == "") {
		$msg = "";
	} else {
		$msg = "upd";
	}

	$mAuthenticationBean = new MAuthenticationBean();
	$mAuthenticationBean->login =  $_SESSION['user']->email;
	$mAuthenticationBean->user = $_SESSION['user']->id;
	$mAuthenticationBean->password = hash('sha512', ($msg == "upd")?$_POST["newPassword"]:$_POST["oldPassword"]);

	$request = new Request("v2/AuthenticationRequestHandler", UPDATE);
	$request->addArgument("authentication", json_encode($mAuthenticationBean));

	$request->addArgument("oldLogin", $_SESSION['user']->email);
	$request->addArgument("oldPassword", hash('sha512', $_POST["oldPassword"]));

	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	$msg = "";

	if($responseObject->status != 200) {
		$msg = $responseObject->description;
		//return;
	}
	if ($_POST["email"] == ""){
		$msg = "email vide";
	}

	if ($msg == ""){
		// update the profile
		$mUserBean = new MUserBean();
		$mUserBean->id = $_SESSION['user']->id;
		$mUserBean->firstName = $_POST["prenom"];
		$mUserBean->lastName = $_POST["nom"];
		$mUserBean->name = $_POST["prenom"] . " " . $_POST["nom"];
		$mUserBean->email = $_POST["email"];
		$mUserBean->login = $_POST["email"];
		$mUserBean->birthday = $_POST["birthday"];
		$mUserBean->profilePicture = $_POST["thumbnail"];

		// keep the session opened
		$mUserBean->socialNetworkName = $_SESSION['user']->socialNetworkName;
		$mUserBean->SocialNetworkID = $_SESSION['user']->socialNetworkID;
		$mUserBean->SocialNetworkID = $_SESSION['accessToken'];

		$request = new Request("ProfileRequestHandler", UPDATE);
		$request->addArgument("user", json_encode($mUserBean));

		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status != 200) {
			$msg = $responseObject->description;
		} else {
			header("Location: ./");
		}	
		//$_SESSION['user'] = $responseObject->dataObject->profile;
	}

}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>


<body>
	<div data-role="page" id="Update"  data-theme="d">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-transition="flip" data-direction="reverse" data-icon="back"><?= _("Back") ?></a></li>
					<li><a data-icon="check" data-theme="b" data-mini="true" onclick="$('#updateForm').submit();">Enregistrer</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">

			<form action="update" method="post" id="updateForm" >

				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?><?= isset($_GET['extended'])?" Veuillez compléter et enregistrer votre profil, pour l'utilisation de myEurope":""?>
				</div>

				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu1"> <?= _('Prénom') ?>: </label> <input id="textinputu1" name="prenom" placeholder="" value='<?= $_SESSION['user']->firstName ?>'
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu2"> Nom: </label> <input id="textinputu2" name="nom" placeholder="" value='<?= $_SESSION['user']->lastName ?>'
							type="text" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu3"> eMail: </label> <input id="textinputu3" name="email" placeholder="" value='<?= $_SESSION['user']->email ?>'
							type="email" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu4"> Mot de passe: </label> <input id="textinputu4" name="oldPassword" placeholder="" value="" type="password" />
					</fieldset>
				</div>
				<br />
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu51"> Changez de mot de passe? </label> <input id="textinputu51" name="newPassword" placeholder="" value="" type="password" />
					</fieldset>
				</div>
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputu52"> Confirmation du nouveau mot de passe: </label> <input id="textinputu52" name="newPasswordConf" placeholder="" value="" type="password" />
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</body>
</html>

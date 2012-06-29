<?php

//ob_start("ob_gzhandler");

require_once 'Template.php';
Template::init(false);


if (count($_POST)) {
	require_once '../../lib/dasp/beans/MUserBean.class.php';
	require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';

	$responseObject = new stdClass(); $responseObject->status = false;

	$mUserBean = new MUserBean();
	$email = trim(strtolower($_REQUEST["email"]));
	$mUserBean->id = "MYMED_" . $email;
	$mUserBean->firstName = $_REQUEST["prenom"];
	$mUserBean->lastName = $_REQUEST["nom"];
	$mUserBean->name = $_REQUEST["prenom"] . " " . $_REQUEST["nom"];
	$mUserBean->email = $mUserBean->login = $email;
	$mUserBean->birthday = isset($_REQUEST["birthday"])?$_REQUEST["birthday"]:"";
	$mUserBean->profilePicture = isset($_REQUEST["thumbnail"])?$_REQUEST["thumbnail"]:"";

	// create the authentication
	$mAuthenticationBean = new MAuthenticationBean();
	$mAuthenticationBean->login =  $mUserBean->login;
	$mAuthenticationBean->user = $mUserBean->id;
	$mAuthenticationBean->password = hash('sha512', $_REQUEST["password"]);

	// register the new account
	$request = new Request("AuthenticationRequestHandler", CREATE);
	$request->addArgument("authentication", json_encode($mAuthenticationBean));
	$request->addArgument("user", json_encode($mUserBean));
	$request->addArgument("application", $_REQUEST['application']);

	// force to delete existing accessToken
	unset($_SESSION['accessToken']);
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);

	if($responseObject->status == 200) {
		header("Location: ./register?ok");
	} else {
		header("Location: ./register");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>
<body>
	<div data-role="page" id="Register">
		<div class="wrapper">
			<div data-role="header" data-theme="c" style="max-height: 38px;">
				<a href="search" data-icon="back" data-transition="flip" data-direction="reverse"> Retour </a>
				<h2>
					<a href="./" style="text-decoration: none;">myEurope</a>
				</h2>
			</div>
			<div data-role="content">
				<?= isset($_GET['ok'])?"<div style='color:lightGreen;text-align:center;'>Compte créé, validez-le par mail</div>":"" ?>
				<form action="#" method="post" id="registerForm">
					<input name="application" value="myEurope" type="hidden" />
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputr1"> Prénom: </label> <input id="textinputr1" name="prenom" placeholder="" value="" type="text" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputr2"> Nom: </label> <input id="textinputr2" name="nom" placeholder="" value="" type="text" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputr3"> eMail: </label> <input id="textinputr3" name="email" placeholder="" value="" type="email" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputr4"> Mot de passe: </label> <input id="textinputr4" name="password" placeholder="" value="" type="password" />
						</fieldset>
					</div>
					<a href="" type="submit" onclick="$('#registerForm').submit();">Créer</a>
				</form>
				<div class="push"></div>
			</div>
		</div>
		<?= Template::credits(); ?>
	</div>
</body>
</html>

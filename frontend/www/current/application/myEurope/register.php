<!DOCTYPE html>
<html>

<?php

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	
	// DEBUG
	require_once('PhpConsole.php');
	PhpConsole::start();
	//debug('boo '.dirname(__FILE__));
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	require_once '../../lib/dasp/beans/MUserBean.class.php';
	require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';
	session_start();
	
	
	
	if (count($_POST)){ // to publish something
		// create the new user
		$mUserBean = new MUserBean();
		$mUserBean->id = "MYMED_" . $_REQUEST["email"];
		$mUserBean->firstName = $_REQUEST["prenom"];
		$mUserBean->lastName = $_REQUEST["nom"];
		$mUserBean->name = $_REQUEST["prenom"] . " " . $_REQUEST["nom"];
		$mUserBean->email = $_REQUEST["email"];
		$mUserBean->login = $_REQUEST["email"];
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
			debug("sdfh");
			echo '<script type="text/javascript">alert(\'' . Veuillez valider votre compte par mail . '\');</script>';
		}
	}
	
?>

	<head>
		<?= $template->head(); ?>
	</head>
	<body>
		<div data-role="page" id="Register">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="search" data-icon="back"> Retour </a>
					<h3>myEurope - insertion</h3>
				</div>
				<div data-role="content">
					<?= ($responseObject->status==200)?"<div style='color:lightGreen;text-align:center;'>Contenu publié</div>":"" ?>
					<form action="#" id="registerForm">
						<input name="application" placeholder="" value="myEurope" type="hidden" />
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
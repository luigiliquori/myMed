<?php

//ob_start("ob_gzhandler");

require_once 'Template.php';
Template::init(false);

$msg="";

if (count($_POST)) {
	require_once '../../lib/dasp/beans/MUserBean.class.php';
	require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';
	var_dump($_POST);
	
	if ( $_POST["password"] == ""){
		$msg = "<span style='color: red; text-align:center;'>mot de passe vide</span>";
	}
	if ( $_POST["password"] != $_POST["passwordConf"]){
		$msg = "<span style='color: red; text-align:center;'>mot de passe mal confirmé</span>";
	}
	if ( $_POST["checkCondition"] == ""){
		$msg = "<span style='color: red;text-align:center; '>Veuillez accepter les conditions d'utilisation</span>";
	}

	
	if ($msg == ""){
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
		$request = new Request("v2/AuthenticationRequestHandler", CREATE);
		$request->addArgument("authentication", json_encode($mAuthenticationBean));
		$request->addArgument("user", json_encode($mUserBean));
		$request->addArgument("application", $_REQUEST['application']);
		
		// force to delete existing accessToken
		unset($_SESSION['accessToken']);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status == 200) {
			$msg="<span style='color: lightgreen;text-align:center;'>Compte myMed créé, validez-le par mail</span>";
		} else {
			$msg="<span style='color: red; text-align:center;'>".$responseObject->description."</span>";
		}
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
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-transition="flip" data-direction="reverse" data-icon="back">Retour</a></li>
					<li><a href="option" data-icon="check" data-theme="b" data-mini="true" onclick="$('#registerForm').submit();">Valider</a></li>
				</ul>
			</div>
		</div>

		<div data-role="content">
			<?= $msg ?>
			<h2 style="text-align:center;">
				<a href="" style="text-decoration: none;">Inscription à myMed</a>
			</h2>
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
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<label for="textinputr5"> Confirmation: </label> <input id="textinputr5" name="passwordConf" placeholder="" value="" type="password" />
					</fieldset>
				</div>
				<div style="text-align:center;">
					<span style="display: inline-block;vertical-align: middle;"><input id="service-term" type="checkbox" name="checkCondition" style="left: 0;"/></span>
					<span style="display: inline-block;padding-left: 15px;">
						J'accepte les 
						<a href="../../application/myRiviera/system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external">conditions d'utilisation</a>
	<!-- 					I accept  -->
	<!-- 					<a href="system/templates/application/myRiviera/doc/CONDITIONS_GENERALES_MyMed_version1_EN.pdf" rel="external">the general terms and conditions</a> -->
					</span><br />
					<div style="display:none;"><input type="submit" data-theme="b" data-inline="true" value="Valider" /></div>
				</div>
			</form>
		</div>
	</div>
</body>
</html>

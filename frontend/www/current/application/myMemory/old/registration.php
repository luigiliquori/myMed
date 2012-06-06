<?php
	/*require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	require_once '../../lib/dasp/beans/MUserBean.class.php';
	require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';
	session_start();
	
	$responseObject = new stdClass(); $responseObject->success = false;
	
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
		$responseObject->success = true;
	}
	
	echo json_encode($responseObject);
	*/


	include("./header.php");
?>

<div data-role="page" id="Register">
	<div data-role="header" data-position="fullscreen">
		<a href="#Connect" class="homeButton ui-btn-left" data-transition="slide" data-direction="reverse">Log In</a>
		<a href="#Quit" class="homeButton ui-btn-right" data-transition="pop" data-icon="delete">Quit</a>
		<h3>myMemory</h3>
	</div>
	<div data-role="content">
		<form action="#" method="post" id="registerForm" data-transition="pop" data-direction="reverse">
			<input name="application" placeholder="" value="myMemory" type="hidden" />
			<div data-role="fieldcontain">
					<label for="textinputr1"> Prénom: </label> <input id="textinputr1"  name="prenom" placeholder="" value="" type="text" />
			</div>
			<div data-role="fieldcontain">
					<label for="textinputr2"> Nom: </label> <input id="textinputr2"  name="nom" placeholder="" value="" type="text" />
			</div>
			<div data-role="fieldcontain">
					<label for="textinputr3"> eMail: </label> <input id="textinputr3"  name="email" placeholder="" value="" type="email" />
			</div>
			<div data-role="fieldcontain">
					<label for="textinputr4"> Password: </label> <input id="textinputr4"  name="password" placeholder="" value="" type="password" />
			</div>
			<input type="button" value="Créer" />
		</form>
	</div>
</div>
<?php $template->getFooter();?>
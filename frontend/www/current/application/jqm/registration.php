<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	require_once '../../beans/MUserBean.class.php';
	require_once '../../beans/MAuthenticationBean.class.php';
	session_start();
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	
	// create the new user
	$mUserBean = new MUserBean();
	$mUserBean->id = "MYMED_" . $_REQUEST["email"];
	$mUserBean->firstName = $_REQUEST["prenom"];
	$mUserBean->lastName = $_REQUEST["nom"];
	$mUserBean->name = $_REQUEST["prenom"] . " " . $_REQUEST["nom"];
	$mUserBean->email = $_REQUEST["email"];
	$mUserBean->login = $_REQUEST["email"];
	$mUserBean->birthday = $_REQUEST["birthday"];
	$mUserBean->profilePicture = $_REQUEST["thumbnail"];
	
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

	if($responseObject->status != 200) {
		$responseObject->success = false;
	} else {
		$responseObject->success = "Félicitation, Un email de confirmation vient de vous être envoyé!";
	}
	
	echo json_encode($responseObject);

?>
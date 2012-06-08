<?php
	require_once('../../../lib/dasp/request/Request.class.php');
	require_once('../../../system/config.php');
	require_once '../../../lib/dasp/beans/MUserBean.class.php';
	require_once '../../../lib/dasp/beans/MAuthenticationBean.class.php';
	session_start();
	
	$responseObject = new stdClass(); $responseObject->success = false; $responseObject1->success = false;
	
	if($_POST['password'] == ""){
		$responseObject->error = "FAIL: password cannot be empty!";
		return;
	} else if($_POST['email'] == ""){
		$responseObject->error = "FAIL: email cannot be empty!";
		return;
	}
	// update the authentication
	$mAuthenticationBean = new MAuthenticationBean();
	$mAuthenticationBean->login =  $_SESSION['user']->email;
	$mAuthenticationBean->user = $_SESSION['user']->id;
	$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
		
	$request = new Request("AuthenticationRequestHandler", UPDATE);
	$request->addArgument("authentication", json_encode($mAuthenticationBean));
		
	$request->addArgument("oldLogin", $_SESSION['user']->email);
	$request->addArgument("oldPassword", hash('sha512', $_POST["oldPassword"]));
		
	$responsejSon = $request->send();
	$responseObject1 = json_decode($responsejSon);
		
	if($responseObject1->status != 200) {
		echo json_encode($responseObject1);
		return;
	}
		
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

	if($responseObject->status == 200) {
		$responseObject->success = true;
	}
		
	$_SESSION['user'] = $responseObject->dataObject->profile;
	
	//header("Refresh:0;url=".$_SERVER['PHP_SELF']);
	
	echo json_encode($responseObject);

?>
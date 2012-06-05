<?php

// DEBUG
require_once('PhpConsole.php');
PhpConsole::start();

	/*
	 *
	 * all or almost handlers are here
	 * 
	 */
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	ini_set('display_errors', 1);
	
	if(isset($_GET['registration'])) { // registration account validation
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $_GET['accessToken']);
			
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status != 200) {
			header("Location: ./search?registration=no");
		} else {
			header("Location: ./authenticate");
		}
		return;
	} else if (isset($_GET['logout'])){ // deconnect
		$request = new Request("SessionRequestHandler", DELETE);
		$request->addArgument("accessToken", $_SESSION['user']->session);
		$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);
		
		session_destroy();
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			header("Location: ./search");
		}
	} else if (isset($_GET['predicate'])){ // unsubscription by mail
		$_POST['method'] = "unsubscribe";
	}
	
	
	if (count($_POST)==0) return;
	
	if($_POST['method'] == "publish") {

		$predicates = Array();
		$data = Array();
		foreach( $_POST as $i => $value ){
			if ( $i!='application' && $i!='method' && $i[0]!='_' && ($value!='' || $i=='~') ){ //pred keys starting with _ are not included
				$ontology = new stdClass();
				$ontology->key = $i;
				$ontology->value = $value;
				//$ontology->ontologyID = isset($_POST['_'.$i])?$_POST['_'.$i]:0; // '_'.$i form fields contain the ontologyID of the value
		
				if(isset($_POST['_'.$i])){ // keys "_key" indicates if "key" is a predicate or a data
					array_push($data, $ontology);
				}else{
					array_push($predicates, $ontology);
				}
			}
		}
		
		// the following is added in order to display easily results (@see search.php)
		if (!isset($_POST['commentOn'])){ // but don't do that on comments, they use data also, this is a pain...
			$preds = new stdClass();
			foreach( $predicates as $v ){
				$k = $v->key;
				$preds->$k = $v->value;
			}
			$ontology = new stdClass();
			$ontology->key = "data";
			$ontology->value = json_encode($preds);
			array_push($data, $ontology);
		}
		
		if (count($predicates)){
			usort($predicates, "cmp"); // VERY important, to be able to delete the exact same predicates later
			$data = array_merge($predicates, $data);
			
			$request = new Request("PublishRequestHandler", CREATE);
			$request->addArgument("application", $_POST['application']);
			$request->addArgument("predicate", json_encode($predicates));
			
			$request->addArgument("data", json_encode($data));
			if(isset($_SESSION['user'])) {
				$request->addArgument("user", json_encode($_SESSION['user']));
			}
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			if ($responseObject->status==200){
				if (isset($_POST['commentOn'])){
					header("Location: ./detail?".$_SERVER['QUERY_STRING']);
				}else{
					header("Location: ./post?ok=1");
				}
			} else {
				header("Location: ./");
			}
		}
		
	} else if($_POST['method'] == "subscribe") {
		ksort($_GET); // important to match a possible predicate, keys must be ordered
		$predicate = "";
		foreach( $_GET as $i => $value ){
			if ( $i!='application' && $i!='method' && $i[0]!='_' && $value!=''){
				$predicate .= $i . $value;
			}
		}
		$request = new Request("SubscribeRequestHandler", CREATE);
		$request->addArgument("application", $_REQUEST['application']);
		$request->addArgument("predicate", $predicate);
		$request->addArgument("user", json_encode($_SESSION['user']));
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if ($responseObject->status==200){
			$sub = true;
		}
		
	} else if($_POST['method'] == "unsubscribe") {
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", $_REQUEST['application']);
		$request->addArgument("predicate", $_REQUEST['predicate']);
		$request->addArgument("userID", $_REQUEST['userID'] );
		if (isset($_REQUEST['accessToken']))
			$request->addArgument('accessToken', $_REQUEST['accessToken']);
		// ^  to be able to unsubscribe from emails to deconnected session but not deleted session (will fail in this case)
		// I will see with Laurent if we can remove the token check for unsubscribe DELETE handler
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if ($responseObject->status==200){
			header("Location: ./option");
		}
		
	} else if($_POST['method'] == "delete") { // to delete our text or comment
		$request = new Request("PublishRequestHandler", DELETE);
		$request->addArgument("application", $_POST['application']);
		$request->addArgument("predicate", $_POST['predicates']);
		$request->addArgument("user", json_encode($_SESSION['user']) );
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			header("Location: ./");
		}
		
	} else if($_POST['method'] == "authenticate") {
		if(!isset($_SESSION['user'])) {
			$request = new Request("AuthenticationRequestHandler", READ);
			$request->addArgument("login", $_REQUEST["login"]);
			$request->addArgument("password", hash('sha512', $_REQUEST["password"]));
		
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			if($responseObject->status == 200) {
				$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
				//$_SESSION['user'] = $responseObject->dataObject->user;
				$request = new Request("SessionRequestHandler", READ);
				$request->addArgument("socialNetwork", "myMed");
				$responsejSon = $request->send();
				$session = json_decode($responsejSon);
				if($session->status == 200) {
					$_SESSION['user'] = $session->dataObject->user;
					if(!isset($_SESSION['friends'])){
						$_SESSION['friends'] = array();
					}
					header("Location: ./search");
				}
					
			} else{
				header("Location: ./search");
			}
		} else{
			header("Location: ./option?please-logout-first");
		}
		
	} else if($_POST['method'] == "register") {
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
		
	} else if($_POST['method'] == "update") {
		require_once '../../lib/dasp/beans/MUserBean.class.php';
		require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';
		$responseObject = new stdClass();
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
			header("Location: ./option");
		}
		
		$_SESSION['user'] = $responseObject->dataObject->profile;
		
	} else if($_POST['method'] == "startInteraction") {

	}
	
	
	// sorting function, to sort predicates always the same way in pub / sub / delete
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}

?>
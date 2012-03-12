<?php
	ob_start("ob_gzhandler");			// page compression
	@set_magic_quotes_runtime(false);	// for the magic_quotes
	
	// DEBUG
	ini_set('display_errors', 1);
	
	require_once 'lib/dasp/beans/MPositionBean.class.php';
	require_once 'lib/php-mobile-detect/Mobile_Detect.php';
	
	require_once dirname(__FILE__).'/config.php';
	require_once dirname(__FILE__).'/templates/TemplateManager.class.php';
	require_once dirname(__FILE__).'/templates/handler/LoginHandler.class.php';
	
	session_start();
	
	// HANDLE LOGIN
	$handler = new LoginHandler();
	$handler->handleRequest();
	
	define('USER_CONNECTED', isset($_SESSION['user']));
	
	// MOBILE DETECT
	$detect = new Mobile_Detect();
	if ($detect->isMobile()) {
		define('TARGET', "mobile");
	} else {
		define('TARGET', "desktop");
	}
	
	/* ----------------------------------------------------------------------------- */
	// TODO - MOVE THIS PART
	// Try to get th position && Store the position of the user
	if(USER_CONNECTED) {
		if(isset($_GET["latitude"]) && isset($_GET["longitude"])) {
			
			$request = new Request("PositionRequestHandler", UPDATE);
			$position = new MPositionBean();
			$position->userID = $_SESSION['user']->id;
			$position->latitude = $_GET["latitude"];
			$position->longitude = $_GET["longitude"];
			$geoloc = json_decode(file_get_contents(
			"http://maps.googleapis.com/maps/api/geocode/json?latlng=" . 
			$position->latitude.",".$position->longitude."&sensor=true"));
			$position->formattedAddress = $geoloc->results[0]->formatted_address;
			// TODO add city+zipCode
			$request->addArgument("position", json_encode($position));
			$request->addArgument("accessToken", $_SESSION['accessToken']);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
		}
		
		// GET THE LASTEST KNOWN POSITION OF THE USER: TODO move this part
		$request = new Request("PositionRequestHandler", READ);
		$request->addArgument("userID", $_SESSION['user']->id);
		$request->addArgument("accessToken", $_SESSION['accessToken']);
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status == 200) {
			$_SESSION['position'] = json_decode($responseObject->data->position);
		}
	}
	/* ----------------------------------------------------------------------------- */
	
	// Select the template & call it
	$templateManager = new TemplateManager();
	if(isset($_GET['application'])) {
		$_SESSION['application'] = $_GET['application'];
	}
	if(isset($_SESSION['application']) && $_SESSION['application'] != "0"){
		$templateManager->selectTemplate('application/' . $_SESSION['application']);
	} else {
		$templateManager->selectTemplate('application/myMed');
	}
	$templateManager->callTemplate();
	
?>


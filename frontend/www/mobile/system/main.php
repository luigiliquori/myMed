<?php
	ob_start("ob_gzhandler");			// page compression
	@set_magic_quotes_runtime(false);	// for the magic_quotes
	
	// DEBUG
	ini_set('display_errors', 0);
	
	require_once dirname(__FILE__).'/config.php';
	require_once dirname(__FILE__).'/templates/TemplateManager.class.php';
	require_once dirname(__FILE__).'/LoginHandler.class.php';
	require_once 'system/beans/MPositionBean.class.php';
	
	session_start();
	
	// HANDLE LOGIN
	$handler = new LoginHandler();
	$handler->handleRequest();
	
	// MOBILE DETECT
	require_once 'php-mobile-detect/Mobile_Detect.php';
	$detect = new Mobile_Detect();
	if ($detect->isMobile()) {
		define('TARGET', "mobile");
	} else {
		define('TARGET', "desktop");
	}
	
	$templateManager = new TemplateManager();
	define('USER_CONNECTED', isset($_SESSION['user']));
	if (USER_CONNECTED) {
		
		// Try to get th position && Store the position of the user: TODO move this part
		if(isset($_GET["latitude"]) && isset($_GET["longitude"])) {
			$request = new Request("PositionRequestHandler", UPDATE);
			$position = new MPositionBean();
			$position->userID = $_SESSION['user']->id;
			echo '<script type="text/javascript">alert(\'' . $_SESSION['user']->id . '\');</script>';
			$position->latitude = $_GET["latitude"];
			$position->longitude = $_GET["longitude"];
			$geoloc = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$position->latitude.",".$position->longitude."&sensor=true"));
			$position->formattedAddress = $geoloc->results[0]->formatted_address;
// 			TODO add city+zipCode
			$request->addArgument("position", json_encode($position));
			$request->send();
			
			if($responseObject->status != 200) {
				echo '<script type="text/javascript">alert(\'' . $responseObject->description . '\');</script>';
			} 
		}
		
		// GET THE LASTEST KNOWN POSITION OF THE USER: TODO move this part
		$request = new Request("PositionRequestHandler", READ);
		$request->addArgument("userID", $_SESSION['user']->id);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$_SESSION['position'] = json_decode($responseObject->data->position);
		}
		
		if(isset($_GET['application']) && $_GET['application'] != "0"){
			$templateManager->selectTemplate('application/'.$_GET['application']);
		} else if(isset($_GET['admin']) && $_GET['admin'] != "0"){
			$templateManager->selectTemplate('admin');
		} else {
			$templateManager->selectTemplate(TARGET . '/home');
		}
	} else {
		$templateManager->selectTemplate(TARGET . '/login');
	}
	$templateManager->callTemplate();
	
?>


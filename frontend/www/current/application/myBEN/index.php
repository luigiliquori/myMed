<?php
	
	ob_start("ob_gzhandler");

	require_once dirname(__FILE__).'/TemplateManager.class.php';
	
	if(isset($_GET['accessToken'])) {
		// HANDLER REGISTRATION VALIDATION
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $_GET['accessToken']);
			
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) {
			header("Refresh:0;url=/application/jqm?registration=no");
		} else {
			header("Refresh:0;url=/application/jqm?hello please log in now");
		}
	}
	
	$template = new TemplateManager();
	$template->getContent('app.html');
	
	
	//echo '<link rel="stylesheet" type="text/css" href="sencha-touch.css" />';
	//echo '<link rel="stylesheet" type="text/css" href="app.css" />';

	//echo '<script type="text/javascript" src="sencha-touch-all-debug.js"></script>';
	//echo '<script type="text/javascript" src="app.js"></script>';

	// Google Maps
	//echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
	
		
?>
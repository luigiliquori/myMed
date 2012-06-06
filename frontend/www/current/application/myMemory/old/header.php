<?php
	
	ob_start("ob_gzhandler");

	require_once dirname(__FILE__).'/TemplateManager.class.php';
	
	if (!isset($_SESSION['user']))
	{
		header("Refresh:0;url=login.php");
	}
	
	
	
	/*
	 * Confirmation d'inscription si on detecte la présence d'un accessToken
	 */
	if(isset($_GET['accessToken'])) {
		// HANDLER REGISTRATION VALIDATION
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $_GET['accessToken']);
			
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) {
			header("Refresh:0;url=registration.php");
		} else {
			header("Refresh:0;url=index.php");
		}
	}
	
	$template = new TemplateManager();
	$template->getHeader();
		
?>
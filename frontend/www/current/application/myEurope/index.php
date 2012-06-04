<?php
	
	ob_start("ob_gzhandler");
	
	if(isset($_GET['accessToken'])) {
		// HANDLER REGISTRATION VALIDATION
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $_GET['accessToken']);
			
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) {
			header("Location: ./search?registration=no");
		} else {
			header("Location: ./authenticate");
		}
	}
	
	header("Location: ./search");
		
?>
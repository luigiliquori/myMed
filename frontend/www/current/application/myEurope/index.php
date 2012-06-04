<?php
	
	ob_start("ob_gzhandler");
	
	if(isset($_GET['accessToken'])) {
		// HANDLER REGISTRATION VALIDATION
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $_GET['accessToken']);
			
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) {
			header("Refresh:0;url=/application/myEurope?registration=no");
		} else {
			header("Refresh:0;url=/application/myEurope?log-in-now-please");
		}
	}
	
	header("Location: ./search");
		
?>
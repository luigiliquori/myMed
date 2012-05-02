<?php

require_once dirname(__FILE__).'/../../lib/dasp/request/Request.class.php';
require_once dirname(__FILE__).'/../../system/config.php';

class TemplateManager {
	
	/**
	 * Print the http header
	 */
	public function getHeader(){ ?>
		<!doctype html>
		<html manifest="">
		<head>
			<meta charset="utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1" />
			<title>myTemplate</title>
			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
			<link rel="stylesheet" href="my.css" />
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
			<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
			<script src="app.js"></script>
		    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
			
		</head>
		
		<body>
			<?php 
			if(isset($_GET['accessToken'])) {
				// HANDLER REGISTRATION VALIDATION
				$request = new Request("AuthenticationRequestHandler", CREATE);
				$request->addArgument("accessToken", $_GET['accessToken']);
			
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
			
				if($responseObject->status != 200) {
					header("Refresh:0;url=/jqm?registration=no");
				} else {
					header("Refresh:0;url=/jqm?hello");
				}
			}
			?>
			<?php include('app.html');?>
		</body>
		</html>
	<?php }
}
?>
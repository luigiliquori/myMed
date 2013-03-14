<?php
	ini_set('xdebug.var_display_max_depth', 5);
	
	define('APPLICATION_LABEL', 'Transborder social network');
	
	/** true if you want to send page wind XHTML mimetype */
	define('MIMETYPE_XHTML', false&&isset($_SERVER["HTTP_ACCEPT"])&&stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"));
	
	/** define minimum height of desktop design */
	define('MOBILESWITCH_HEIGHT', 1);
	
	/** define minimum width of desktop design */
	define('MOBILESWITCH_WIDTH', 737);
	
	/** define Rest methods codes */
	define('CREATE'		, 0);
	define('READ'		, 1);
	define('UPDATE'		, 2);
	define('DELETE'		, 3);
	
	/** define the backend's URL'*/
	define('MOBILE_PARAMETER_SEPARATOR', '::');
	define('BACKEND_URL', 'http://localhost:8080/backend/');
	
	//Social Networks Keys
	//define('Facebook_APP_ID', '263064853727560');
	//define('Facebook_APP_SECRET', '31132a354804069960f966668df6daa2');
	
	//define('Google_APP_SECRET', 'AIzaSyClDiWqR7pPsXmkLpA3gj7jzL-Cd59My44');
	//define('Google_APP_CLIENT_ID', '${googleappclientid}');
	//define('Google_APP_CLIENT_SECRET', '${googleappclientsecret}');
	
	//define('Twitter_APP_KEY', '${twitterappkey}');
	//define('Twitter_APP_SECRET', '${twitterappsecret}');
	
	//CITYWAY Keys
	define('Cityway_URL', 'http://openservice.cityway.fr/api/');
	define('Cityway_APP_ID', 'INRIACARF');
?>
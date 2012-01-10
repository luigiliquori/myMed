<?php
	ini_set('xdebug.var_display_max_depth', 5);
	/** true if you want to send page wind XHTML mimetype */
	define('MIMETYPE_XHTML', false&&isset($_SERVER["HTTP_ACCEPT"])&&stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"));
	/** define minimum height of desktop design */
	define('MOBILESWITCH_HEIGHT', 1);
	/** define minimum width of desktop design */
	define('MOBILESWITCH_WIDTH', 737);
	/** define the path in URL to access to the website, must begin with '/' and ended with '/' */
	define('ROOTPATH', '/mymed/mobile/');
	/** define the backend's URL'*/
	/** define the backend's URL'*/
	define('MOBILE_PARAMETER_SEPARATOR', '::');
	define('BACKEND_URL', 'http://mymed2.sophia.inria.fr:8080/mymed_backend/');
	
	//Social Networks Keys
	define('Facebook_APP_ID', '263064853727560');
	define('Facebook_APP_SECRET', '31132a354804069960f966668df6daa2');
	
	//Social Networks Keys
	define('Google_APP_SECRET', 'IQVBaitB9zaBX1UIytixrw7i');
	
	//CITYWAY Keys
	define('Cityway_URL', 'http://openservice.cityway.fr/api/');
	define('Cityway_APP_ID', 'INRIACARF');
?>

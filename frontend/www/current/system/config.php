<?php
	ini_set('xdebug.var_display_max_depth', 5);
	
	/** true if you want to send page wind XHTML mimetype */
	define('MIMETYPE_XHTML', false&&isset($_SERVER["HTTP_ACCEPT"])&&stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"));
	
	/** define minimum height of desktop design */
	define('MOBILESWITCH_HEIGHT', 1);
	
	/** define minimum width of desktop design */
	define('MOBILESWITCH_WIDTH', 737);
	
	/** define the backend's URL'*/
	define('MOBILE_PARAMETER_SEPARATOR', '::');
	define('BACKEND_URL', 'http://138.96.242.18:8080/backend/');
	
	//Social Networks Keys
	define('Facebook_APP_ID', '@facebookappid@');
	define('Facebook_APP_SECRET', '@facebookappsecret@');
	
	//Social Networks Keys
	define('Google_APP_SECRET', '@googleappsecret@');
	
	//CITYWAY Keys
	define('Cityway_URL', '@citywayurl@');
	define('Cityway_APP_ID', '@citywayappid@');
?>

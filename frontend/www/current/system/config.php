<?php
	ini_set('xdebug.var_display_max_depth', 5);
	
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
	define('BACKEND_URL', '@backendurl@');
	
	//Social Networks Keys
	define('Facebook_APP_ID', '@facebookappid@');
	define('Facebook_APP_SECRET', '@facebookappsecret@');
	
	define('Google_APP_SECRET', '@googleappsecret@');
	define('Google_APP_CLIENT_ID', '@googleappclientid@');
	define('Google_APP_CLIENT_SECRET', '@googleappclientsecret@');
	
	//CITYWAY Keys
	define('Cityway_URL', '@citywayurl@');
	define('Cityway_APP_ID', '@citywayappid@');
?>

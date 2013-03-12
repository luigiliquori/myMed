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
	define('BACKEND_URL', '@backendurl@');
	
	//CITYWAY Keys
	define('Cityway_URL', '@citywayurl@');
	define('Cityway_APP_ID', '@citywayappid@');

	if(strpos($_SERVER['SERVER_NAME'], 'mymed231')!==false){ 	// http://mymed231.sophia.inria.fr
		define('Facebook_APP_ID', '173481506133880');
		define('Facebook_APP_SECRET', 'a9ed4094e0e010422218be67da8168ba');
	}else if(strpos($_SERVER['SERVER_NAME'], 'www')===false){ 	// http://mymed.fr
		define('Facebook_APP_ID', '263064853727560');
		define('Facebook_APP_SECRET', 'dbcad40d88c3c5e4a3532be114117e56');
	}else{ 														// http://www.mymed.fr
		define('Facebook_APP_ID', '161275950692324');
		define('Facebook_APP_SECRET', 'c46c7bbbdf2c83990b7858ecb5c9e53c');
	}
?>

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
	define('BACKEND_URL', 'http://127.0.0.1:8080/mymed_backend/');
	
	//Social Networks Keys
	define('ConnexionTwitter_KEY', 'HgsnlIpCJ7RqHhCFELkTvw');
	define('ConnexionTwitter_SECRET', 'P7Gkj9AfeNEIHXrj0PMTiNHM3lJbHEqkuXwuWtGzU');
	define('ConnexionMySpace_KEY', 'ca09cf41d3c047ecbed4eeac8b6f14c7');
	define('ConnexionMySpace_SECRET', 'b9e2b88eb7aa4b878f913db25c1bb3f60bcbf3cdb99c47fb99e5aed9d5919eb5');
	define('ConnexionLiveId_KEY', '000000004405611F');
	define('ConnexionLiveId_SECRET', 'Akxh3kwLGjkyKpjpfBShzk9wrDAjme94');
	//define('ConnexionLinkedIn_KEY', '');
	//define('ConnexionLinkedIn_SECRET', '');
	define('ConnexionGoogle_KEY', $_SERVER['HTTP_HOST']);//unused
	define('ConnexionGoogle_SECRET', 'LCQZrwojk1KdSf1ARurdjIr8');
	define('FACEBOOK_APP_ID', '263064853727560');
	define('FACEBOOK_SECRET', '31132a354804069960f966668df6daa2');
?>
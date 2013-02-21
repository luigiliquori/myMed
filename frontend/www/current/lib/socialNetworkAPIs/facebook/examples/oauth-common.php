<?php


define('ROOT', dirname(__FILE__).'/../../../../');

require_once ROOT.'system/common/PhpConsole.php';
PhpConsole::start();
debug('boo');

require_once(ROOT.'system/config.php');

if(strpos($_SERVER['SERVER_NAME'], 'www')===false){ // http://mymed.fr
	define('Facebook_APP_ID', '263064853727560');
	define('Facebook_APP_SECRET', 'dbcad40d88c3c5e4a3532be114117e56');
}else{ // http://www.mymed.fr
	define('Facebook_APP_ID', '161275950692324');
	define('Facebook_APP_SECRET', 'c46c7bbbdf2c83990b7858ecb5c9e53c');
}
debug($_SERVER['SERVER_NAME']." ".Facebook_APP_ID." ".Facebook_APP_SECRET);

function getReturnTo() {
	return sprintf("%s://%s%s/oauth_catch.php",
			getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']));
}
function getTrustRoot() {
	return sprintf("%s://%s/",
			getScheme(), $_SERVER['SERVER_NAME']);
}

function getScheme() {
	$scheme = 'http';
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
		$scheme .= 's';
	}
	return $scheme;
}

?>
<?php

define('ROOT', dirname(__FILE__).'/../../../../../');

require_once ROOT.'system/common/PhpConsole.php';
PhpConsole::start();
debug('boo');

require_once(ROOT.'system/config.php');

if(strpos($_SERVER['SERVER_NAME'], 'www')===false){ // http://mymed.fr
	define('Google_APP_SECRET', 'AIzaSyDUjDokDbzFpxNKXAj8EXqyWeUrGW06TIk');
	define('Google_APP_CLIENT_ID', '376803621438.apps.googleusercontent.com');
	define('Google_APP_CLIENT_SECRET', 'v3qux1l94rOJqCyXS1qMrFhy');
}else{
	define('Google_APP_SECRET', 'AIzaSyAsFQAe0YULLS72-o_Nd0fX1jyj97CZBgA');
	define('Google_APP_CLIENT_ID', '493954073161.apps.googleusercontent.com');
	define('Google_APP_CLIENT_SECRET', '2eqeVS49dcgQuTQDdl42MtJ_');
}

$path_extra = ROOT.'lib/socialNetworkAPIs/google/src';
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

require_once 'Google_Client.php';

$client = new Google_Client();
$client->setApplicationName("myMed");
$client->setClientId(Google_APP_CLIENT_ID);
$client->setClientSecret(Google_APP_CLIENT_SECRET);
$client->setRedirectUri(
		sprintf("%s://%s%s/oauth_catch.php",
				getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']))
	);
$client->setDeveloperKey(Google_APP_SECRET);

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
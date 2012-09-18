<?php

define('ROOT', dirname(__FILE__).'/../../../../../');

require_once ROOT.'system/common/PhpConsole.php';
PhpConsole::start();
debug('boo');

require_once(ROOT.'system/config.php');

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


function getScheme() {
	$scheme = 'http';
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
		$scheme .= 's';
	}
	return $scheme;
}

?>
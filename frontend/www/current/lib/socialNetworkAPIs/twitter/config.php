<?php

/**
 * @file
 * A single location to store configuration.
 */

define('ROOT', dirname(__FILE__).'/../../../');

require_once ROOT.'system/common/PhpConsole.php';
PhpConsole::start();

require_once(ROOT.'system/config.php');

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

if(strpos($_SERVER['SERVER_NAME'], 'www')===false){ // http://mymed.fr
	define('CONSUMER_KEY', 'bNiImVmOCf4wdNPjEIsgw');
	define('CONSUMER_SECRET', 't3WP9o1jQUg6eIfNR9asU8YG0pCzPTa6ccak5sDsVc');
}else{
	define('CONSUMER_KEY', 'rxnjz46zCzvrrYZmyxx0A');
	define('CONSUMER_SECRET', 'oVRSV3p2BVzld2Ay95DG1MXM6Va1KGvUQq2kySAHWBc');
}

define('OAUTH_CALLBACK', sprintf("%s://%s%s/callback.php",
			getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF'])));

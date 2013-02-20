<?php

/**
 * @file
 * A single location to store configuration.
 */

define('ROOT', dirname(__FILE__).'/../../../');

require_once ROOT.'system/common/PhpConsole.php';
PhpConsole::start();
debug('config twitter');
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

define('CONSUMER_KEY', 'bNiImVmOCf4wdNPjEIsgw');
define('CONSUMER_SECRET', 't3WP9o1jQUg6eIfNR9asU8YG0pCzPTa6ccak5sDsVc');
define('OAUTH_CALLBACK', sprintf("%s://%s%s/callback.php",
			getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF'])));

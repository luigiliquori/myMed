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

define('CONSUMER_KEY', 'LQjBz4v6WQmXSvkrQKUirg');
define('CONSUMER_SECRET', 'ijFWf1fTzjcYxCYvUcYqqKSGOpDzGE72N91cOV2KyCs');
define('OAUTH_CALLBACK', sprintf("%s://%s%s/callback.php",
			getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF'])));

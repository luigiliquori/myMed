<?php


define('ROOT', dirname(__FILE__).'/../../../../');

require_once ROOT.'system/common/PhpConsole.php';
PhpConsole::start();
debug('boo');

require_once(ROOT.'system/config.php');

function getReturnTo() {
	return sprintf("%s://%s%s/oauth_catch.php",
			getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']));
}

function getScheme() {
	$scheme = 'http';
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
		$scheme .= 's';
	}
	return $scheme;
}

?>
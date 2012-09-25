<?php

define('ROOT', dirname(__FILE__).'/../../../../../');

require_once ROOT.'system/common/PhpConsole.php';
PhpConsole::start();
debug('boo');

$path_extra = ROOT.'/lib/socialNetworkAPIs/php-openid';
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);


// Includes required files
require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/FileStore.php";
require_once "Auth/OpenID/AX.php";
require_once "Auth/OpenID/SReg.php";
require_once "Auth/OpenID/PAPE.php";

function getScheme() {
    $scheme = 'http';
    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
        $scheme .= 's';
    }
    return $scheme;
}

function getReturnTo() {
    return sprintf("%s://%s%s/oid_catch.php",
                   getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']));
}

function getTrustRoot() {
    return sprintf("%s://%s/",
                   getScheme(), $_SERVER['SERVER_NAME']);
}

function getStorePath(){
	$store_path = null;
	if (function_exists('sys_get_temp_dir')) {
		$store_path = sys_get_temp_dir();
	}
	else {
		if (strpos(PHP_OS, 'WIN') === 0) {
			$store_path = $_ENV['TMP'];
			if (!isset($store_path)) {
				$dir = 'C:\Windows\Temp';
			}
		}
		else {
			$store_path = @$_ENV['TMPDIR'];
			if (!isset($store_path)) {
				$store_path = '/tmp';
			}
		}
	}
	$store_path .= DIRECTORY_SEPARATOR . '_php_consumer_test';

	if (!file_exists($store_path) &&
			!mkdir($store_path)) {
		print "Could not create the FileStore directory '$store_path'. ".
				" Please check the effective permissions.";
		exit(0);
	}
	return $store_path;
}

?>

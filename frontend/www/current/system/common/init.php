<?php

// ------------------------------------------------------------------------------------------------
// Common initialisations
// ------------------------------------------------------------------------------------------------

// Page compression
ob_start("ob_gzhandler");

// Debugging in Chrome. Not in CLI mode 
//if (php_sapi_name() != "cli") {
	require('PhpConsole.php');
	PhpConsole::start();
// } else {
//	function debug($msg) {		
//		error_log($msg);
//	}
//}

function debug_r($obj) {
	debug(print_r($obj, true));
}

// For the magic_quotes
ini_set("magic_quotes_runtime", 0);

// Throw exception when backend fails with 5XX errors
define('FAIL_ON_BACKEND_ERROR', true);

// ------------------------------------------------------------------------------------------------
// Set Autoload
// ------------------------------------------------------------------------------------------------

function add_path($path) {
	if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir'))
	{
		// trigger_error("Include path '{$path}' does not exists", E_USER_WARNING);
		return;
	}
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}

// Set the paths
add_path(APP_ROOT . '/include/');
add_path(APP_ROOT . '/controllers/');
add_path(APP_ROOT . '/controllers/abstract');
add_path(APP_ROOT . '/models/');
add_path(APP_ROOT . '/views/');
add_path(APP_ROOT . '/views/parts');
add_path(MYMED_ROOT . '/lib/dasp/beans');
add_path(MYMED_ROOT . '/lib/dasp/request');
add_path(MYMED_ROOT . '/system/controllers/');
add_path(MYMED_ROOT . '/system/common/');
add_path(MYMED_ROOT . '/lib/php-mobile-detect');

// Set autoload
function autoload($className) {

	foreach(array(".class.php", ".php") as $x) {

		foreach(explode(PATH_SEPARATOR, get_include_path()) as $path) {
			$fname = $path . '/' . $className.$x;
			if(@file_exists($fname)) {
				require_once($fname);
				return true;
			}
		}
	}
	
	error_log("Failed to find '$className' in " . get_include_path());
	return false;
}

spl_autoload_register('autoload');

// ------------------------------------------------------------------------------------------------
// Global Config
// ------------------------------------------------------------------------------------------------

require_once MYMED_ROOT . '/system/config.php';
require_once MYMED_ROOT . '/system/common/common-utils.php';


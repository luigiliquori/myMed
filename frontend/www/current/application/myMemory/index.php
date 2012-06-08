<?php

	// ------------------------------------------------------------------------------------------------
	// Init things
	// ------------------------------------------------------------------------------------------------

	// Page compression
	ob_start("ob_gzhandler");
		
	// Debugging in Chrome
	include('include/PhpConsole.php');
	PhpConsole::start();
	
	// For the magic_quotes
	@set_magic_quotes_runtime(false);	
	
	// Start session
	session_start();
	
	// ------------------------------------------------------------------------------------------------
	// Helpers
	// ------------------------------------------------------------------------------------------------
	
	function add_path($path) {
		if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir'))
		{
			trigger_error("Include path '{$path}' does not exists", E_USER_WARNING);
			return;
		}
		set_include_path(get_include_path() . PATH_SEPARATOR . $path);
	}
	
	// ---------------------------------------------------------------------
	// 	 Constants
	// ---------------------------------------------------------------------
	
	define('APPLICATION_NAME', "myMemory");
	define('APP_ROOT', __DIR__);
	define('MYMED_ROOT', __DIR__ . '/../..');
	
	// ------------------------------------------------------------------------------------------------
	// Include
	// ------------------------------------------------------------------------------------------------
	
	// Set the paths
	add_path(__DIR__ . '/include/');
	add_path(__DIR__ . '/controllers/');
	add_path(__DIR__ . '/views/');
	add_path(__DIR__ . '/views/parts');
	add_path(MYMED_ROOT . '/lib/dasp/beans');
	add_path(MYMED_ROOT . '/lib/dasp/request');
	
	// Get config
	require_once MYMED_ROOT . '/system/config.php';
	
	// Set autoload
	spl_autoload_register(function ($className) {
		
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
	});

	
	// ---------------------------------------------------------------------
	// 	Main process
	// ---------------------------------------------------------------------

	// Get action, default is "main" 
	$action = $_REQUEST["action"];
	if (empty($action)) {
		$action = "main";
	}
	
	// Hardcoded action "egister" if we have GET["register"]="ok" (link from confirmation email)
	if ($_GET['registration'] == "ok") {
		$action = "register";
	}
	
	// Name/Path of view and controllers
	$className = ucfirst($action) . "Controller";

	// Create controller
	$controller = new $className();
	
	// Process the request
	$controller->handleRequest();
	
	// We should not reach that point (view already rendered by the controller) 
	throw new Exception("${className}->handleRequest() should never return");
	
?>

<?php

require(__DIR__ . "/../common/init.php");

// ---------------------------------------------------------------------
// 	Main process
// ---------------------------------------------------------------------

// Start session
session_start();

// Get action, default is "main"
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "main";

// Hardcoded action "register" if we have GET["register"]="ok" (link from confirmation email)
if (isset($_GET['registration']) && ($_GET['registration'] == "ok")) {
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

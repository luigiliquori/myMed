<?php

require(__DIR__ . "/../common/init.php");

// ---------------------------------------------------------------------
// Call a controller (reused in AsbtractController#forward)
// ---------------------------------------------------------------------
function callController($action, $method=null) {

	// Name/Path of view and controllers
	$className = ucfirst($action) . "Controller";

	// Create controller
	$controller = new $className();

	// Process the request
	$controller->handleRequest();
	
	if ($method == null) $method = "defaultMethod";
	
	// Call the methods
	$controller->$method();
	
	// We should not reach that point (view already rendered by the controller)
	throw new Exception("${className}:  #handleRequest() followed by ${method}() should never return");
}

// ---------------------------------------------------------------------
// 	Main process
// ---------------------------------------------------------------------

// Start session
session_start();



/*if (isset($_SESSION['user'], $_SESSION['user']->lang))
	$s = $_SESSION['user']->lang;
else
	$s = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0 ,2);
switch ($s) {
	case 'fr':
		$locale = 'fr_FR.utf8';
		break;
	case 'it':
		$locale = 'it_IT.utf8';
		break;
	default:
		$locale = 'en_US.utf8';
		break;
}

$filename = 'dict';
putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
bindtextdomain($filename, MYMED_ROOT.'/lang');
textdomain($filename);
bind_textdomain_codeset($filename, 'UTF-8');*/

// Get action, default is "main"
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "main";

// Hardcoded action "register" if we have GET["register"]="ok" (link from confirmation email)
if (isset($_GET['registration']) && ($_GET['registration'] == "ok")) {
	$action = "register";
}

$method = (in_request("method")) ? $_REQUEST['method'] : null;

callController($action, $method);



?>

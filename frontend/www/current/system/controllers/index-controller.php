<?php

require(__DIR__ . "/../common/init.php");

// ---------------------------------------------------------------------
// Call a controller (reused in AbstractController#forward)
// ---------------------------------------------------------------------
function callController(
		$action, 
		$method=null,
		$success = null, // Forward success message
		$error = null)  // Forward error message 
{
	try {
		
		if (empty($method)) $method = "defaultMethod";
		
		// Name/Path of view and controllers
		$className = ucfirst($action) . "Controller";

		// Create controller
		$controller = new $className($action);
		
		// Set succes/error
		$controller->setSuccess($success);
		$controller->setError($error);
		
		
		// Process the request, Access Control is checked there
		$controller->handleRequest();
		
		debug('call '.$className.' '.$method);
		/*return call_user_func(
			array( $controller, $method )
		);*/
		// invoke controller method after checking access control
		//$controller->accessControl( $method, $_SESSION['acl']);
		
		if (!isset($_SESSION['acl'])){
			debug('-----!!!!!!!!---------should not happen but make sure to give sth to Acl--');
			$_SESSION['acl'] = array('defaultMethod', 'read');
		}
			
		// create an Access Control object
		$acl = new Acl($controller, $_SESSION['acl']);
		
		//call the controller with it
		return call_user_func_array(
			array( $acl, $method ),
			array()
		);
		//$acl->$method();

		// ---------------------------------------------
		// Use introspection to fill out parameters
		// ---------------------------------------------
		/*$ref = new ReflectionClass($controller);
		$refMethod = $ref->getMethod($method);
		$refParams = $refMethod->getParameters();
		$params = array();
		foreach($refParams as $refParam) {
			$name = $refParam->getName();
			$pos = $refParam->getPosition();
			if (isset($_REQUEST[$name])) {
				$val = $_REQUEST[$name];
			} else {
				if (!$refParam->isOptional() ) {
					trigger_error("Mandatory parameter $name not found in request for action $action:$method", E_USER_WARNING);
				}
				$val = null;
			}
			$params[$pos] = $val;
		}
		
		// Call the methods
		$refMethod->invokeArgs($controller, $params);*/

		/*if ( method_exists( $controller, $method )){
			return call_user_func(
				array( $controller, $method )
			);
		}*/

		// We should not reach that point (view already rendered by the controller)
		throw new InternalError("${className}:  #handleRequest() followed by ${method}() should never return");

	} catch (UserError $e) {
		debug_r($e);
		debug($e->getMessage());
		$controller->setError($e->getMessage());
		$controller->renderView("error");
	}
}


// ---------------------------------------------------------------------
// 	Main process
// ---------------------------------------------------------------------

// Start session
session_start();
session_set_cookie_params(3600);

// ---------------------------------------------------------------------
// Internationalization
// ---------------------------------------------------------------------
/*
 * Generate a op file with xgettext -f trads
 * where trads is a file containing the files to translate ex:
 *  views/MainView.php
 *  views/LoginView.php
 *  
 *  use poEdit to fill the *.po
 *  at the end open with poEdit the global messages.po and catalog>update from pot file and select your .po
 */

// Global domain name
define('GLOBAL_MESSAGES', 'messages');

// Connected user
global $LANG, $LOCALE;
if (isset($_SESSION['user'], $_SESSION['user']->lang) && !empty($_SESSION['user']->lang)) {
	$LANG = $_SESSION['user']->lang;
	// In request => save into session
} elseif (in_request("lang")) {
	$LANG = $_REQUEST['lang'];
	$_SESSION['lang'] = $LANG;

	// In session
} elseif (isset($_SESSION['lang'])) {
	$LANG = $_SESSION['lang'];

	// Get Browser preference
} else {
	$LANG = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0 ,2);
}
// Default lang
if (empty($LANG)) $LANG = "en";

switch ($LANG) {
	case 'fr':
		$LOCALE = 'fr_FR.utf8';
		break;
	case 'it':
		$LOCALE = 'it_IT.utf8';
		break;
	default:
		$LOCALE = 'en_US.utf8';
		break;
}
debug($LOCALE);
if(isset($_SESSION['user'])) $_SESSION['user']->lang = $LANG;

/////////////////////////////////////////////////////////////////////////////
putenv('LANGUAGE='.$LOCALE);
putenv('LANG='.$LOCALE);
putenv('LC_ALL='.$LOCALE);
putenv('LC_MESSAGES='.$LOCALE);
setlocale(LC_ALL,$LOCALE);
setlocale(LC_CTYPE,$LOCALE);

// Define domain "<APPLICATION_NAME>"
bindtextdomain(APPLICATION_NAME, APP_ROOT.'/lang');
bind_textdomain_codeset(APPLICATION_NAME, 'UTF-8');

// Define global domain (MyMed wide messages)
bindtextdomain(GLOBAL_MESSAGES, MYMED_ROOT.'/lang');
bind_textdomain_codeset(GLOBAL_MESSAGES, 'UTF-8');
textdomain(GLOBAL_MESSAGES);

/////////////////////////////////////////////////////////////////////////////
debug(APPLICATION_NAME);

// Set locale
setlocale(LC_ALL, 'en_US.utf8');


// Support for php-gettext as a replacement for native gettext :
// This is a pure PHP implementation of gettext that does not reply on system installed locales.
// It loads the same .mo files, and he's easier to debug :(You can put your debug logs in gettext.inc and gettext.php)
// However, if you want to use that, you should use __() instead of _(). Make a massive search / replace for that
define('PHP_GETTEXT', true); // Set to true to enable php-gettext

if (PHP_GETTEXT) {
	require(__DIR__ . "./../common/gettext/gettext.inc");
	_setlocale(LC_ALL, $LOCALE);
	
	// Define domain "<APPLICATION_NAME>"
	_bindtextdomain(APPLICATION_NAME, APP_ROOT.'/lang');
	_bind_textdomain_codeset(APPLICATION_NAME, 'UTF-8');
	
	// Define global domain (MyMed wide messages)
	_bindtextdomain(GLOBAL_MESSAGES, MYMED_ROOT.'/lang');
	_bind_textdomain_codeset(GLOBAL_MESSAGES, 'UTF-8');
	
} else {
	
	// Support for __() function => use gettext "_()"
	function __($msg) {
		return _($msg);
	}
	
}



// Make a main function to be called in each root index
function main_controller() {
	global $ACTION; 
	// Get action, default is "main"
	$ACTION = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "main";

	// Hardcoded action "register" if we have GET["register"]="ok" (link from confirmation email)
	if (isset($_GET['registration']) && ($_GET['registration'] == "ok")) {
		$ACTION = "register";
	}
	
	$method = (in_request("method")) ? $_REQUEST['method'] : null;

	// Call the methods of the controller
	callController($ACTION, $method);
}


?>

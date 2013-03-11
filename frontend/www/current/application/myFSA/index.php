<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myFSA");
define('APP_ROOT', '.');
define('MYMED_ROOT', '../../');
define('MYMED_URL_ROOT', '../../');

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(GLOBAL_MESSAGES);

// Start a new session
session_start();

// Name of current application used for the
// redirection when log in with the socialNetworks
$_SESSION['appliName'] = APPLICATION_NAME;

// use the common dictionary
// include('../../system/lang/langue.php');

// use the specific dictionary
// include('lang/langue.php');

// Call the main controller
main_controller();

?>

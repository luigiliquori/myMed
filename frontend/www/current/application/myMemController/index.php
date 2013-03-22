<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myTemplateExtended");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
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

require_once('header-bar.php');

// Print Page
include_once('header.php');
main_controller();
include_once('footer.php');

?>

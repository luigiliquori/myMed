<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myEurope");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('MYMED_URL_ROOT', '../../');

session_start();

$_SESSION['appliName'] = APPLICATION_NAME; // used in order to access to the name of the current application for the redirection when log in with the socialNetworks

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/index-controller.php');

textdomain(GLOBAL_MESSAGES);

require_once('header-bar.php');

// Print Page
include_once('header.php');
echo '<input type="hidden" id="isGuest" value="' . $_SESSION['user']->is_guest . '" />';
main_controller();
include_once('footer.php');

?>

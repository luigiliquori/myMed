<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myBen");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');

// Include main controller : Dispatches actions to individual controllers
include(MYMED_ROOT . '/system/controllers/main-controller.php')

?>

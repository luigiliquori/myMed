<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<?php

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------

define('APPLICATION_NAME', "myConsolato");
define('APPLICATION_LABEL', "myConsolato");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/../..');
define('DATE_FORMAT', "d/m/Y");

// Include main controller : Dispatches actions to individual controllers
require(MYMED_ROOT . '/system/controllers/index-controller.php');

// Use the application specific locales
textdomain(APPLICATION_NAME);

// Support for php-gettext
if (PHP_GETTEXT) {
	_textdomain(APPLICATION_NAME);
}

// Call the main controller
main_controller();

?>

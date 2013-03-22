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
require("include/PhpConsole.php");
require("../../system/config.php");
require("../../lib/dasp/beans/OntologyBean.php");
require("../../lib/dasp/request/DeleteRequest.class.php");
PhpConsole::start();
session_start();
define('APPLICATION_NAME', "myMemory");

/*
* Build the predicates Array
*/
$predicateList = array();
$predicateList[] = new OntologyBean("role", "ExtendedProfile", KEYWORD);


// Build the request
//$delete = new DeleteRequest($_SESSION['user']->id, $predicateList);
$delete = new DeleteRequest("MYMED_contact@daviddasilva.net", $predicateList);
$res = $delete->send();

echo "res = " . $res;
echo "profil supprimé";




?>
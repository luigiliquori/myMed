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
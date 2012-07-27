<?php
require("include/PhpConsole.php");
require("../../system/config.php");
require("../../lib/dasp/beans/OntologyBean.php");
require("../../lib/dasp/beans/DataBean.php");
require("../../lib/dasp/request/DeleteRequest.class.php");
PhpConsole::start();
session_start();
define('APPLICATION_NAME', "myMemory");

/*
* Build the predicates Array
*/
$predicates[] = new OntologyBean("role", "ExtendedProfile", KEYWORD);

/*
* Buid the datas Array
*/
$datas = array();

// Build the DataBean
$dataBean = new DataBean($predicates, $datas);

// Build the request
$delete = new DeleteRequest($_SESSION['user']->id, $predicateList)
$publish = new PublishRequest(/* DELETE */3, null, $dataBean);
$publish->send();




?>
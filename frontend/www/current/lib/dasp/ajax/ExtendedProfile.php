<?php

require_once('../request/RequestJson.php');
require_once('../request/Reputationv2.php');
require_once('../../../system/config.php');

include_once('../../../application/myEurope/views/parts/utils.php');

session_start();

$request = new RequestJson(null, $_GET);
if ($request->hasArgument('application'))
	$request->addArgument('application', $_GET['application'].":users");

$res = $request->send();
if (!empty($res)){
	$rep =  new Reputationv2($_GET['id']);
	$res->details->reputation = $rep->send();

}

session_write_close();

printProfile($res->details);

// echo json_encode($res);

?>
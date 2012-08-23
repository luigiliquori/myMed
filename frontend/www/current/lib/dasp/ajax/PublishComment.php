<?php

require_once('../request/Requestv2.php');
require_once('../../../system/config.php');


$request = new Requestv2("v2/DataRequestHandler", UPDATE);
session_start();

$request->addArgument('application', $_POST['application']);

$request->addArgument('id', $_POST['id']);

$t = time();
$k = hash("crc32", $t.$_SESSION['user']->id);
$data = array(
		$k => json_encode(array(
				"time"=>$t,
				"user"=>$_SESSION['user']->id,
				"replyTo"=>$_POST['replyTo'],
				"text"=>$_POST['text']
		))
);
$request->addArgument('data', json_encode($data));

$responsejSon = $request->send();
session_write_close();

$responseObject = json_decode($responsejSon);

if ($responseObject->status == 200) {
	$responseObject->field = $k;
	$responseObject->user = $_SESSION['user']->id;
	$responseObject->userh = hash("crc32",$responseObject->user);
	$responseObject->time = date('j/n/y G:i', $t);
}
echo json_encode($responseObject);


?>
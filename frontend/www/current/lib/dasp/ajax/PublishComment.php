<?php

require_once('../request/RequestJson.php');
require_once('../../../system/config.php');


$request = new RequestJson(null, $_POST, UPDATE);
session_start();

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
$request->addArgument('data', $data);

$response = $request->send();

if (isset($response)) {
	$response->field = $k;
	$response->user = $_SESSION['user']->id;
	$response->userh = hash("crc32",$response->user);
	$response->time = date('j/n/y G:i', $t);
}

session_write_close();

echo json_encode($response);


?>
<?php 

//require(dirname(__FILE__) . '/../../../lib/dasp/request/RequestJson.php');
require(dirname(__FILE__) . '/../../../lib/dasp/request/Requestv2.php');
require(dirname(__FILE__) . '/../../../system/config.php');




/*$req = new RequestJson(null,
		array("application"=>"myApp", "id"=>"someFuckingCassandraTests", "data"=>array("k2"=>"22222222")),
		UPDATE );*/


// -----------RS READ

$req = new Requestv2("v2/ReputationRequestHandler");
//$req->addArgument("producer", "toto"); 
$req->addArgument("id", "Test12"); 
//uncomment one of the 2 above lines (or both but producer not used)
$req->addArgument("consumer", "titi"); 

$req->setURL("http://mymed20.sophia.inria.fr:8080/backend/");
if (!isset($_SESSION['accessToken']))
	$req->addArgument('accessToken', 'a7ed1679b474ece4681bdbeea7eb22a7d58d6926'); //for tests

$res = $req->send();

print_r($res);

// -----------RS UPDATE

// $request = new Requestv2("InteractionRequestHandler", UPDATE);
// $request->addArgument("consumer", "titi" );
// $request->addArgument("start", time() );
// $request->addArgument("end", time() );

// $request->addArgument("application", "Test12" ); //application is used as Data Id
// $request->addArgument("producer", "toto");
// $request->addArgument("feedback", 0.9 );
// $req->setURL("http://mymed20.sophia.inria.fr:8080/backend/");
// if (!isset($_SESSION['accessToken']))
// 	$req->addArgument('accessToken', 'a7ed1679b474ece4681bdbeea7eb22a7d58d6926'); //for tests

// $res = $req->send();

// print_r($res);

?>
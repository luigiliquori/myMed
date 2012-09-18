<?php 

require(dirname(__FILE__) . '/../../../lib/dasp/request/RequestJson.php');
require(dirname(__FILE__) . '/../../../lib/dasp/request/Requestv2.php');
require(dirname(__FILE__) . '/../../../lib/dasp/request/Requestv2Wrapper.php');
require(dirname(__FILE__) . '/../../../system/config.php');




$req = new Requestv2Wrapper(null,
		array("user"=>"MYMED_cyril.auburtin@inria.fr", "key"=>"applicationList", "value"=>json_encode(array("myEurope", "myRiviera"))),
		UPDATE, "v2/ProfileRequestHandler" );

/*$req = new Requestv2("v2/ProfileRequestHandler", READ, array());

session_start();
$req->addArgument("userID", "MYMED_cyril.auburtin@gmail.com");*/
$req->setURL("http://mymed20.sophia.inria.fr:8080/backend/");


if (!isset($_SESSION['accessToken']))
	$req->addArgument('accessToken', 'b5f7d04a94704896a719c6777a7faa55e8e68c3f'); //for tests


/*$req->addArgument("user", "MYMED_cyril.auburtin@gmail.com");
$req->addArgument("key", "lastConnection");
$req->addArgument("value", 10);
$req->setURL("http://mymed20.sophia.inria.fr:8080/backend/");
if (!isset($_SESSION['accessToken']))
	$req->addArgument('accessToken', 'ya29.AHES6ZQGDWn2w9d7X-kc7fHBpc_noEuGk6fjNp9Evsdj0Y4R46z29g'); //for tests
*/
/*$req = new RequestJson(null );

session_start();
$req->addArgument("application", "myEurope:profiles");
$req->addArgument("id", "fdbfae26cc33117334437d8761090ce7");
$req->setURL("http://mymed20.sophia.inria.fr:8080/backend/");
if (!isset($_SESSION['accessToken']))
	$req->addArgument('accessToken', 'c852f1ad014df55e350f1e0ed24a642db86fd73f'); //for tests
*/
$res = $req->send();

print_r($res);

// -----------RS READ
// _("Association - Coopérative - Mutuelle");
// $req = new Requestv2("v2/ReputationRequestHandler");
// //$req->addArgument("producer", "toto"); 
// $req->addArgument("id", "Test12"); 
// //uncomment one of the 2 above lines (or both but producer not used)
// $req->addArgument("consumer", "titi"); 

// $req->setURL("http://mymed20.sophia.inria.fr:8080/backend/");
// if (!isset($_SESSION['accessToken']))
// 	$req->addArgument('accessToken', '2989c62d6bcab48ffafa3d80c5e415218a4b09d6'); //for tests

// $res = $req->send();

// print_r($res);

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
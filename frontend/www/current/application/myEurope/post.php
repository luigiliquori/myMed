<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

require_once 'Template.php';

Template::init();


if (count($_POST)){ // to publish something

	//$tags = preg_split('/[ +]/', $_POST['q'], NULL, PREG_SPLIT_NO_EMPTY);
	//array_push($tags, '~'); //let's add this common tag for all texts, to easily retrieve all texts if necessary
	//$p = array_unique(array_map('strtolower', $tags));


	//sort($p); //important

	// 		$all = array_search('~', $p);
	// 		unset($p[$all]); // remove it
	


	$data = array();

// 	foreach( $p as $v ){ // tags ontologies array
// 		array_push($data, array("key"=>$v, "value"=>"", "ontologyID"=>KEYWORD));
// 	}
	
	foreach( $_POST as $i=>$v ){
		if ($v == "on"){
			array_push($data, array("key"=>$i, "value"=>"", "ontologyID"=>KEYWORD));
		}
	}

	array_push($data, array("key"=>"rate", "value"=>0, "ontologyID"=>FLOAT));
	array_push($data, array("key"=>"nbOfRates", "value"=>1, "ontologyID"=>TEXT));
	
	if (isset($_POST['date']))
		array_push($data, array("key"=>"date", "value"=>$_POST['date'], "ontologyID"=>DATE));

	if (isset($_POST['text']))
		array_push($data, array("key"=>"text", "value"=>$_POST['text'], "ontologyID"=>TEXT));

	$request = new Request("v2/PublishRequestHandler", CREATE);
	$request->addArgument("application", $_POST['application'].$_POST['type']); //application + namespace {offer , part}

	$request->addArgument("data", json_encode($data));
	$request->addArgument("userID", $_SESSION['user']->id);
	if (!isset($_POST['id'])){
		$_POST['id'] = "Projet".date("Y-m-d");
	}
	$request->addArgument("id", $_POST['id']); 
	$request->addArgument("level", 3);

	if ($_POST['type']=="part" && $_SESSION['userPerm']>0 || $_POST['type']=="offer" && $_SESSION['userPerm']>1){

		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);

		if ($responseObject->status==200){
			if ($_POST['type']=="part"){
				header("Location: ./search?".http_build_query(array_filter($_POST, "isTag")));
			} else {
				header("Location: ./");
			}
		}
	}

}



?>
<?php

/*
 * Post controller
 */

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

	$metiers = array();
	$regions = array();
	
	foreach( $_POST as $i=>$v ){
		if ($v == "on"){
			if ( strpos($i, "met") === 0){
				array_push($metiers, $i);
			} else if  ( strpos($i, "reg") === 0){
				array_push($regions, $i);
			}
		}
	}
	if (count($metiers)){
		array_push($data, new DataBean("met", ENUM, $metiers));
	}
	
	if (count($regions)){
		array_push($data, new DataBean("reg", ENUM, $regions));
	}
	
	if (isset($_POST['offre'])){
		array_push($data, new DataBean("offre", KEYWORD, array($_POST['offre'])));
	}
	
	if (isset($_POST['date'])) {
		if (strtotime($_POST['date']) !== false){
			array_push($data, new DataBean("date", DATE, array(strtotime($_POST['date']))));
		} else {
			
		}
	}
	
	if (isset($_POST['text']))
		array_push($data, new DataBean("text", TEXT, array($_POST['text'])));

	$request = new Request("v2/PublishRequestHandler", CREATE);
	$request->addArgument("application", Template::APPLICATION_NAME);
	$request->addArgument("namespace", $_POST['type']); //."temp"

	$request->addArgument("data", json_encode($data));
	$request->addArgument("user", $_SESSION['user']->id);
	
	if (!isset($_POST['id'])){
		$_POST['id'] = "Projet".date("Y-m-d");
	} else {
		$request->addArgument("id", $_POST['id']); 
	}

	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);

	if ($responseObject->status==200){
		if ($_POST['type']=="part"){
			header("Location: ./search?type=part&".http_build_query(array_filter($_POST, "Template::isCheckbox")));
		} else {
			header("Location: ./");
		}
	}
	

}



?>
<?php

	/*
	 *
	* usage:
	*  post
	*
	* what it does:
	*  display the form for posting new content: IMPORTANT this view should be reserved to special users
	*
	* 
	*/

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	
	if (count($_POST)){ // to publish something
		
		$tags = preg_split('/[ +]/', $_POST['q'], NULL, PREG_SPLIT_NO_EMPTY);
		array_push($tags, '~'); //let's add this common tag for all texts, to easily retrieve all texts if necessary
		$p = array_unique(array_map('strtolower', $tags));
		sort($p); //important
		$predicates=array();
		foreach( $p as $v ){ //do this for PubRequestHandler compatibility...
			array_push($predicates, array("key"=>$v, "value"=>""));
		}
		$all = array_search('~', $p);
		unset($p[$all]); // remove it 
		
		$metadata = array(
			"nom" => $_POST['nom'],
			"id" => $_POST['id'],
			"cout" => $_POST['cout'],
			"montant" => $_POST['montant'],
			"date" => $_POST['date']
		);
		$data = array(
			array("key"=>"text", "value"=>$_POST['text']),
			array("key"=>"id", "value"=>$_POST['id']), // data_id
			array("key"=>"data", "value"=>json_encode($metadata)), // there to display more info on the result list
			array("key"=>"deleteme", "value"=>json_encode($predicates)), // there to more easily remove this data
			array("key"=>"indexes", "value"=>json_encode($p)), // array with all indexes for searching this data 
		);
		
		$request = new Request("PublishRequestHandler", CREATE);
		$request->addArgument("application", $_POST['application']);
		$request->addArgument("predicate", json_encode($predicates));
			
		$request->addArgument("data", json_encode($data));
		$request->addArgument("id", $_POST['id']);
		$request->addArgument("level", 3);
		if(isset($_SESSION['user'])) {
			$request->addArgument("user", json_encode($_SESSION['user']));
		}
			
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);

		if ($responseObject->status==200){
			header("Location: ./post?ok=1");
		}	
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<?= $template->head(); ?>
	</head>
	<body>
		<div data-role="page" id="Post">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="./" data-icon="home" data-iconpos="notext"> Accueil </a>
					<h3>myEurope - insertion</h3>
				</div>
				<div data-role="content">
					<?= isset($_GET['ok'])?"<div style='color:lightGreen;text-align:center;'>Contenu publié</div>":"" ?>
					<form action="#" method="post" id="publishForm" >
						<input name="application" value="myEurope" type="hidden" />
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp1"> Nom de l'organisme bénéficiaire: </label> <input id="textinputp1"  name="nom" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp2"> Libellé du projet: </label> <input id="textinputp2"  name="id" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp3"> Coût total du projet (en euros): </label> <input id="textinputp3"  name="cout" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp4"> Montant du financement européen (en euros): </label> <input id="textinputp4"  name="montant" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp5"> Echéance: </label> <input id="textinputp5"  name="date" placeholder="" value="" type="date" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp6"> Tags d'indexation de ce texte, séparés par un espaces: </label> <input id="textinputp6"  name="q" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<hr>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp7"> Description: </label> <textarea id="textinputp7"  name="text" placeholder="" value=""></textarea>
							</fieldset>
						</div>
						<input type="submit" data-theme="g" value="Insérer"/>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
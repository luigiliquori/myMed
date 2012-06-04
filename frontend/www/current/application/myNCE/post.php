<!DOCTYPE html>
<html>

<?php

	/*
	 *
	* usage:
	*  post
	*
	* what it does:
	*  display the form for posting new content
	*
	*  if param commentOn is present it will attempt to publish a comment on this data
	*  if param predicates is present it will attempt to delete either text (if we are the authod) or our comment (only our comments not other's or change it if you want)
	*/

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	
	// DEBUG
	//require_once('PhpConsole.php');
	//PhpConsole::start();
	//debug('boo '.dirname(__FILE__));
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}
	
	$responseObject = new stdClass(); $responseObject->status = false;
	
	if (count($_POST)){ // to publish something
		$predicates = Array();
		$data = Array();
		foreach( $_POST as $i => $value ){
			if ( $i!='application' && $i[0]!='_' && ($value!='' || $i=='~') ){
				$ontology = new stdClass();
				$ontology->key = $i;
				$ontology->value = $value;
				//$ontology->ontologyID = isset($_POST['_'.$i])?$_POST['_'.$i]:0; // '_'.$i form fields contain the ontologyID of the value
		
				if(isset($_POST['_'.$i])){
					array_push($data, $ontology);
				}else{
					array_push($predicates, $ontology);
				}
			}
		}
		
		// the following is added in order to display easily results (@see search.php)
		$preds = new stdClass();
		foreach( $predicates as $v ){
			$k = $v->key;
			$preds->$k = $v->value;
		}
		$ontology = new stdClass();
		$ontology->key = "data";
		$ontology->value = json_encode($preds, JSON_FORCE_OBJECT);
		array_push($data, $ontology);
		
		if (count($predicates)){
			usort($predicates, "cmp"); // VERY important, to be able to delete the exact same predicates later
			$data = array_merge($predicates, $data);
			
			$request = new Request("PublishRequestHandler", CREATE);
			$request->addArgument("application", $_POST['application']);
			$request->addArgument("predicate", json_encode($predicates));
			
			$request->addArgument("data", json_encode($data));
			if(isset($_SESSION['user'])) {
				$request->addArgument("user", json_encode($_SESSION['user']));
			}
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
		}
	}
	
?>

	<head>
		<?= $template->head(); ?>
	</head>
	<body>
		<div data-role="page" id="Post">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="search" data-icon="back"> Retour </a>
					<h3>myEurope - insertion</h3>
				</div>
				<div data-role="content">
					<?= ($responseObject->status==200)?"<div style='color:lightGreen;text-align:center;'>Contenu publié</div>":"" ?>
					<form action="#" method="post" id="publishForm" >
						<input name="application" value="myEurope" type="hidden" />
						<input name="_desc" value="4" type="hidden" />
						<input name="~" value="" type="hidden" />
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp1"> Nom de l'organisme bénéficiaire: </label> <input id="textinputp1"  name="nom" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp2"> Libellé du projet: </label> <input id="textinputp2"  name="lib" placeholder="" value="" type="text" />
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
						<hr>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp6"> Description: </label> <textarea id="textinputp6"  name="desc" placeholder="" value=""></textarea>
							</fieldset>
						</div>
						<input type="submit" data-theme="g" value="Publier" />
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
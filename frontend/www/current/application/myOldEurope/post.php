<!DOCTYPE html>
<html>

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
	
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}
	
	if (count($_POST)){ // to publish something
		$predicates = Array();
		$data = Array();
		foreach( $_POST as $i => $value ){
			if ( $i!='application' && $i!='method' && $i[0]!='_' && ($value!='' || $i=='~') ){ //pred keys starting with _ are not included
				$ontology = new stdClass();
				$ontology->key = $i;
				$ontology->value = $value;
				//$ontology->ontologyID = isset($_POST['_'.$i])?$_POST['_'.$i]:0; // '_'.$i form fields contain the ontologyID of the value
		
				if(isset($_POST['_'.$i])){ // keys "_key" indicates if "key" is a predicate or a data
					array_push($data, $ontology);
				}else{
					array_push($predicates, $ontology);
				}
			}
		}

		// these line are added from default publish to be able to easily display predicates in result list (@see search)
		$preds = new stdClass();
		foreach( $predicates as $v ){
			$k = $v->key;
			$preds->$k = $v->value;
		}
		$ontology = new stdClass();
		$ontology->key = "data";
		$ontology->value = json_encode($preds);
		array_push($data, $ontology);
		//
		
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

			if ($responseObject->status==200){
				header("Location: ./post?ok=1");
			}
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
					<a href="search" data-icon="home" data-iconpos="notext"> Accueil </a>
					<h3>myEurope - insertion</h3>
				</div>
				<div data-role="content">
					<?= isset($_GET['ok'])?"<div style='color:lightGreen;text-align:center;'>Contenu publié</div>":"" ?>
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
						<input type="submit" data-theme="g" value="Publier"/>
					</form>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
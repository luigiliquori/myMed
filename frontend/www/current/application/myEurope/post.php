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
	require_once 'Template.php';
	$template = new Template();
	$template->checkSession();
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	
	if (count($_POST)){ // to publish something
		
		$tags = preg_split('/[ +]/', $_POST['q'], NULL, PREG_SPLIT_NO_EMPTY);
		array_push($tags, '~'); //let's add this common tag for all texts, to easily retrieve all texts if necessary
		$p = array_unique(array_map('strtolower', $tags));
		
		
		//sort($p); //important

// 		$all = array_search('~', $p);
// 		unset($p[$all]); // remove it 

		
		
		$metadata = array(
			"nom" => $_POST['nom'],
			"id" => $_POST['id'],
			"montant" => $_POST['montant'],
		);
		
		$data = array();
		
		foreach( $p as $v ){ //do this for PubRequestHandler compatibility...
			array_push($data, array("key"=>$v, "value"=>"", "ontologyID"=>0));
		}
		array_push($data, array("key"=>"cout", "value"=>$_POST['cout'], "ontologyID"=>2));
		array_push($data, array("key"=>"date", "value"=>$_POST['date'], "ontologyID"=>3));
		array_push($data, array("key"=>"text", "value"=>$_POST['text'], "ontologyID"=>4));
		array_push($data, array("key"=>"data", "value"=>json_encode($metadata), "ontologyID"=>4));
		// there to display more info on the result list
		
		$request = new Request("v2/PublishRequestHandler", CREATE);
		$request->addArgument("application", $_POST['application']);
			
		$request->addArgument("data", json_encode($data));
		$request->addArgument("id", $_POST['id']);
		$request->addArgument("level", 3);
		if(isset($_SESSION['user'])) {
			$request->addArgument("userID", $_SESSION['user']->id);
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
								<label for="textinputp0"> Dépôt d'une offre : </label>
								<select  id="textinputp0" data-role="slider" data-mini="true">
									<option value="co">de partenariat</option>
									<option value="offre">de projet institutionnel</option>
								</select>
							</fieldset>
						</div>
						
						
						<div data-role="fieldcontain">
							<fieldset id="test" data-role="controlgroup" data-type="horizontal" data-mini="true"  >
						     	<legend>Type d'offre:</legend>
						     	<input type="radio" name="type" id="radio-view-a" value="Pacalabs" checked="checked"/>
						     	<label for="radio-view-a">Pacalabs</label>
						     	<input type="radio" name="type" id="radio-view-b" value="Interreg" />
						     	<label for="radio-view-b">Interreg</label>
						     	<input type="radio" name="type" id="radio-view-c" value="Edu" />
						     	<label for="radio-view-c">Edu</label>
						     	<input type="radio" name="type" id="radio-view-d" value="Autre" />
						     	<label for="radio-view-d">Autre</label>
							</fieldset>
						</div>
						
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp1"> Nom de l'organisme emetteur: </label> <input id="textinputp1"  name="nom" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp2"> Libellé du projet: </label> <input id="textinputp2"  name="id" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp3"> Coût estimé du projet (en euros): </label> <input id="textinputp3"  name="cout" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp4"> Montant estimé du financement européen (en euros): </label> <input id="textinputp4"  name="montant" placeholder="" value="" type="text" />
							</fieldset>
						</div>
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<label for="textinputp5"> Date d'échéance: </label> <input id="textinputp5"  name="date" placeholder="" value="" type="date" />
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
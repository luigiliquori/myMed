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
$template->init();

if (count($_POST)){ // to publish something

	$tags = preg_split('/[ +]/', $_POST['q'], NULL, PREG_SPLIT_NO_EMPTY);
	//array_push($tags, '~'); //let's add this common tag for all texts, to easily retrieve all texts if necessary
	$p = array_unique(array_map('strtolower', $tags));


	//sort($p); //important

	// 		$all = array_search('~', $p);
	// 		unset($p[$all]); // remove it


	$data = array();

	foreach( $p as $v ){ // tags ontologies (KEYWORD with empty value, the are easy to search)
		array_push($data, array("key"=>$v, "value"=>"", "ontologyID"=>KEYWORD));
	}

	array_push($data, array("key"=>"cout", "value"=>isset($_POST['cout'])?$_POST['cout']:5, "ontologyID"=>FLOAT));
	
	if (isset($_POST['date']))
		array_push($data, array("key"=>"date", "value"=>$_POST['date'], "ontologyID"=>DATE));
	if (isset($_POST['type']))
		array_push($data, array("key"=>"type", "value"=>$_POST['type'], "ontologyID"=>KEYWORD));
	if (isset($_POST['offre']))
		array_push($data, array("key"=>"offre", "value"=>$_POST['offre'], "ontologyID"=>KEYWORD));
	if (isset($_POST['text']))
		array_push($data, array("key"=>"text", "value"=>$_POST['text'], "ontologyID"=>TEXT));

	$request = new Request("v2/PublishRequestHandler", CREATE);
	$request->addArgument("application", $_POST['application']);
		
	$request->addArgument("data", json_encode($data));
	$request->addArgument("userID", $_SESSION['user']->id);
	$request->addArgument("id", $_POST['id']);
	$request->addArgument("level", 3); //put min (3, $data)
	
		
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
				<form action="#" method="post" id="publishForm">
					<input name="application" value="myEurope" type="hidden" />
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputp0"> Dépôt d'une offre : </label> <select name="offre" id="textinputp0" data-role="slider" data-mini="true">
								<option value="part">de partenariat</option>
								<option value="inst">de projet institutionnel</option>
							</select>
						</fieldset>
					</div>


					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
							<legend>Type d'offre:</legend>
							<input type="radio" name="type" id="checkbox-5" /> <label for="checkbox-5">Pacalabs</label> <input type="radio" name="type" id="checkbox-6" />
							<label for="checkbox-6">Interreg</label> <input type="radio" name="type" id="checkbox-7" /> <label for="checkbox-7">Edu</label> <input
								type="radio" name="type" id="checkbox-8" /> <label for="checkbox-8">Autre</label>
						</fieldset>
					</div>

					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
							<legend>Domaines métier concernés:</legend>
								<input type="radio" name="metier" id="checkbox-9" class="custom" /> <label for="checkbox-9">Métier1</label> <input type="radio"
									name="metier" id="checkbox-10" class="custom" /> <label for="checkbox-10">Métier2</label> <input type="radio" name="metier"
									id="checkbox-11" class="custom" /> <label for="checkbox-11">Métier3</label>
						</fieldset>
					</div>

					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputp2"> Libellé du projet: </label> <input id="textinputp2" name="id" placeholder="" value="projet1" type="text" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputp5"> Date d'échéance: </label> <input id="textinputp5" name="date" placeholder="" value="2012-07-15" type="date" />
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputp6"> Mots clés: </label> <input id="textinputp6" name="q" placeholder="..."
								value="Europe " type="text" />
						</fieldset>
					</div>
					<hr>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<label for="textinputp7"> Contenu: </label>
							<textarea style="min-height: 200px;" id="textinputp7" name="text" placeholder="" value=""></textarea>
						</fieldset>
					</div>
					<input type="submit" data-theme="g" value="Insérer" />
				</form>
				<div class="push"></div>
			</div>
		</div>
		<?= $template->credits(); ?>
	</div>
</body>
</html>

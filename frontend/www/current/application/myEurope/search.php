<?php

/*
 * usage:
*  search?application=val1&param2=val2&param3=val3
*
* what it does:
*  list all data entries for the predicate built with params (other than application)
*  (_* are also ignored)
*
*  you can subscribe+search for a specific set of predicates
*/

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();
Template::checksession();

$res = null;

if (count($_GET) > 0){
	//$tags = preg_split('/[ +]/', $_GET['q'], NULL, PREG_SPLIT_NO_EMPTY);
	//$p = array_unique(array_map('strtolower', $tags));
	//sort($p); //important
	//$predicate = join("", array_slice($p, 0, 3)); // we use a broadcast level of 3 for myEurope see in post.php


	// @todo verify date format
	$namespace = $_GET['namespace'];
	unset($_GET['namespace']);
	$index=array();
	$p=array();
// 	foreach( $p as $v ){ //tags
// 		$predicateList[$v] = array("valueStart"=>"", "valueEnd"=>"", "ontologyID"=>KEYWORD);
// 	}

	$metiers = array();
	$regions = array();

	foreach( $_GET as $i=>$v ){
		array_push($p, new DataBean($i, KEYWORD, array($v)));
		if ($v == "on"){
			if ( strpos($i, "met") === 0){
				array_push($metiers, $i);
			} else if  ( strpos($i, "reg") === 0){
				array_push($regions, $i);
			}	
		}
	}
	if (count($metiers)){
		array_push($index, new DataBean("met", ENUM, $metiers));
	}
	
	if (count($regions)){
		array_push($index, new DataBean("reg", ENUM, $regions));
	}
	
	if (isset($_GET['offre'])){
		array_push($index, new DataBean("offre", KEYWORD, array($_GET['offre'])));
	}
	
	if ($_GET['dateMin'] != 0 && $_GET['dateMax'] != 0){
		array_push($index, new DataBean("date", DATE, array(strtotime($_GET['dateMin']), strtotime($_GET['dateMax']))));
	}

	$request = new Request("v2/FindRequestHandler", READ);
	$request->addArgument("application", Template::APPLICATION_NAME);
	$request->addArgument("namespace", $namespace);
	$request->addArgument("index", json_encode($index));

	$responsejSon = $request->send();
	$res = json_decode($responsejSon);
}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="Search" data-theme="d">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-icon="back"><?= _("Back") ?></a></li>	
					<li><a href="home"  data-icon="home"><?= _('Home') ?></a></li>				
				</ul>
			</div>
		</div>

		<div data-role="content">
			
			<div  style="display:inline-block;width:49%;">
			<label for="flip-a2">Trier par:</label>
			<select data-theme="b" data-mini="true" name="slider2" id="flip-a2" data-role="slider"
				onchange="">
				<option value="3">réputation</option>
				<option value="0">nom</option>
			</select>
			</div>
		<div style="display:inline-block;width:49%;">
			<label for="flip-a">Si un nouveau contenu correspond à cette recherche:</label>
			<select data-theme="e" data-mini="true" name="slider" id="flip-a" data-role="slider"
				onchange="$.get('../../lib/dasp/ajax/Subscribe', { code: $(this).val(), application: '<?= Template::APPLICATION_NAME ?>' ,namespace: '<?= $namespace ?>' ,data: '<?= urlencode(json_encode($p)) ?>' } );">
				<option value="3">Souscrire</option>
				<option value="0">Désabonner</option>
			</select>
			</div>
			
			<br /><br /><br />

			<?php 	
			if($res->status == 200) {
				?>
			<ul data-role="listview" data-filter="true" data-filter-placeholder="filtrer parmi les résultats">
				<?php
				$res = $res->dataObject->results;
				
				if ($_GET['rate'] == 1){
					//sort res by rate
				}

				foreach( $res as $i => $value ){

					$metiers = array();
					$regions = array();
					foreach ( (array) $value as $k=>$v){
						if (strpos($k, "met") === 0 ){
							array_push($metiers, $k);
						} else if (strpos($k, "reg") === 0 ) {
							array_push($regions, $k);
						}
					}

					?>
				<li><a href="detail?id=<?=  urlencode($value->id) ?>&user=<?=  urlencode($value->publisherID) ?>&namespace=<?= urlencode($namespace) ?>" 
				 style="padding-top: 1px; padding-bottom: 1px;">
						<h3>
							projet: <?= $value->predicate ?>
						</h3>
						<p>
							réputation: <?= 100 - $value->rate ?>
						</p>
						<p style="font-weight:lighter;"> métiers: <?= join(", ",$metiers) ?>...
						 régions: <?= join(", ", $regions) ?>... </p>
						<p class="ui-li-aside">
							publié par: <span style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= $value->publisherName ?> </span> échéance: <strong><?= date("Y-m-d h:i:s", $value->date) ?> </strong>
						</p>

				</a>
				</li>
				<?php 
				}
				?>
			</ul>
			<br />
			<div style="float: right;">
				<?= count($res) ?>
				résultats
			</div>
			<br />
			<?php	
			} else{
				?>
			Votre recherche n'a abouti à aucun résultat.
			<?php	
			}
			?>

		</div>
	</div>
</body>
</html>

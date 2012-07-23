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

$res = null;

if (count($_GET) > 0){
	//$tags = preg_split('/[ +]/', $_GET['q'], NULL, PREG_SPLIT_NO_EMPTY);
	//$p = array_unique(array_map('strtolower', $tags));
	//sort($p); //important
	//$predicate = join("", array_slice($p, 0, 3)); // we use a broadcast level of 3 for myEurope see in post.php

	// @todo verify date format 
	
	$application = Template::APPLICATION_NAME.$_GET['type'];

	$predicateList=array();
	$p=array();
// 	foreach( $p as $v ){ //tags
// 		$predicateList[$v] = array("valueStart"=>"", "valueEnd"=>"", "ontologyID"=>KEYWORD);
// 	}

	foreach( $_GET as $i=>$v ){
		if ($v == "on"){
			$predicateList[$i] = array("valueStart"=>"", "valueEnd"=>"", "ontologyID"=>KEYWORD);
			array_push($p, $i);
		}
	}
	
	if ($_GET['dateMin'] != 0 && $_GET['dateMax'] != 0){
		$predicateList["date"] = array("valueStart"=>strtotime($_GET['dateMin']), "valueEnd"=>strtotime($_GET['dateMax']), "ontologyID"=>DATE);
	}
	if ($_GET['rate'] == 1){
		$predicateList["rate"] = array("valueStart"=>0, "valueEnd"=>999, "ontologyID"=>FLOAT);
	}

	$request = new Request("v2/FindRequestHandler", READ);
	$request->addArgument("application", $application);
	$request->addArgument("predicateList", json_encode($predicateList));
	$request->addArgument("level", 3);
	//$request->addArgument("predicate", $predicate);

	//$request->addArgument("start", isset($_GET['start'])?$_GET['start']:"");
	//$request->addArgument("count", isset($_GET['count'])?$_GET['count']:10);
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
	<div data-role="page" id="Search">
		<div data-role="header" data-theme="c" style="max-height: 38px;" id="headerSearch">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
			<select data-theme="b" data-mini="true"
				onchange="$.get('../../lib/dasp/ajax/Subscribe', { code: $(this).val(), application: '<?= $application ?>' ,predicate: '<?= urlencode(join("", $p)) ?>' } );"
				 name="slider" id="flip-a" data-role="slider">
				<option value="3">Souscrire</option>
				<option value="0">Désabonner</option>
			</select>
			
		</div>
		<div data-role="content">
			<?php 	
			if($res->status == 200) {
				?>
			<ul data-role="listview" data-filter="true" data-filter-placeholder="filtrer parmi les résultats">
				<?php
				$res = $res->dataObject->results;

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
				<li><a href="detail?id=<?=  urlencode($value->predicate) ?>&user=<?=  urlencode($value->publisherID) ?>&application=<?= urlencode($application) ?>" 
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
			Aucun résultats
			<?php	
			}
			?>

		</div>
	</div>
</body>
</html>

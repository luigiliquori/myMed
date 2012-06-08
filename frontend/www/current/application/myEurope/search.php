<?php

	/*
	 * usage:
	 *  search?application=val1&param2=val2&param3=val3
	 *  
	 * what it does:
	 *  list all data entries for the predicate built with params (other than application)
	 *  (_* are also ignored)
	 *  
	 *  you can subscribe+search for a specific set of predicates (recherche avancée)
	 *    (the first input is just an jquery filter for the all results)
	 *  
	 *  ex: yourPC/application/myEurope/search?application=myTemplate&keyword=2 (publish something on mytemplate with keyword 2)
	 */

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	
	$application = isset($_REQUEST['application'])?$_REQUEST['application']:"myEurope";
	
	$res = null;
	$predicate = "";
	if (isset($_GET['q'])){
		$tags = preg_split('/[+]/', $_GET['q'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_unique(array_map('strtolower', $tags));
		sort($p); //important
		foreach( $p as $v ){ //do this for FindRequestHandler compatibility...
			$predicate .= $v;
		}
		
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", $predicate);
		$responsejSon = $request->send();
		$res = json_decode($responsejSon);
		
	}

	
?>

<!DOCTYPE html>
<html>
	<head>
		<?= $template->head(); ?>
	</head>

	<body>
		<div data-role="page" id="Search">
			<div class="wrapper">
				<div data-role="header" data-theme="b" id="headerSearch">
					<select data-theme="e" class="ui-btn-right" onchange="$.get('ajaxsubscribe.php', { code: $(this).val(), application: '<?= $application ?>' ,predicate: '<?= urlencode($predicate) ?>' } );" style="position: absolute;left: 5px;" name="slider" id="flip-a" data-role="slider" data-mini="true">
						<option value="3">Non abonné</option>
						<option value="0">Abonné</option>
					</select>
					<h2>myEurope</h2>
					<a id="opt" href=<?= $_SESSION['user']?"option":"authenticate" ?> class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
				</div>
				<div data-role="content">	
					<form action="search" id="subscribeForm" data-ajax="false">
						<input id="searchBar" name="q" placeholder="chercher un partenaire par mot clés" data-type="search" style="width: 80%;"/>
					</form>
					<br />
					<?php 	
						if($res->status == 200) {
						?>
						<ul data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="filtrer parmi tous les résultats">
						<?php
							$res = $res->dataObject->results;
							
							foreach( $res as $i => $value ){
								$preds = json_decode($value->data);
							?>
							<li><a href="" onclick="$('#detailForm<?= $i ?>').submit();">
								<?= $preds->nom ?>, <?= $preds->lib ?>, <?= $preds->cout ?>, <?= $preds->montant ?>, 
								<?= $preds->date ?>
								<form action="detail" id="detailForm<?= $i ?>">
									<input name="application" value='<?= $application ?>' type="hidden" />
									<input name="predicate" value="<?= urlencode($value->predicate) ?>" type="hidden" />
									<input name="user" value='<?= $value->publisherID ?>' type="hidden" />
								</form>
								</a>
							</li>
							<?php 
							}
							?>
							</ul>
							<div style="float:right;"><?= count($res) ?> résultats</div><br />
						<?php	
						} else{
						?>
						Aucun résultats
						<?php	
						}
						?>

					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>

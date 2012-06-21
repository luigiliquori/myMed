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
	$template->checkSession();
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	
	if (!isset($_SESSION['user'])) {
		header("Location: ./authenticate");
	}
	
	$application = isset($_REQUEST['application'])?$_REQUEST['application']:"myEurope";
	
	$res = null;
	$predicate = "";
	if (isset($_GET['q'])){
		$tags = preg_split('/[ +]/', $_GET['q'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_unique(array_map('strtolower', $tags));
		sort($p); //important
		$predicate = join("", array_slice($p, 0, 3)); // we use a broadcast level of 3 for myEurope see in post.php
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", $predicate);
		$request->addArgument("start", isset($_GET['start'])?$_GET['start']:"");
		$request->addArgument("count", isset($_GET['count'])?$_GET['count']:10);
		$responsejSon = $request->send();
		$res = json_decode($responsejSon);
		
	}

	function is_subarray($q, $r){
		return count(array_intersect( $q , $r)) == min(count($q), count($r));
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
					<h2><a href="./" style="color:white; text-decoration:none;">myEurope</a></h2>
					<a id="opt" href=<?= $_SESSION['user']?"option":"authenticate" ?> class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
				</div>
				<div data-role="content">
					<?php 	
						if($res->status == 200) {
						?>
						<ul data-role="listview" data-filter="true" data-filter-placeholder="filtrer parmi tous les résultats">
						<?php
							$res = $res->dataObject->results;

							foreach( $res as $i => $value ){
								$data = json_decode($value->data);

								if (!is_subarray(array_slice($p, 3), json_decode($value->indexes))){
									continue; //this item is filtered out, after level (=3)
								}
									
							?>
							<li><a href="" onclick="$('#detailForm<?= $i ?>').submit();" style="padding-top: 1px;padding-bottom: 1px;">
								<h3><?= $data->nom ?>, <?= $data->id ?></h3>
								<p><?= $data->cout ?>, <?= $data->montant ?></p>
								<p class="ui-li-aside"><strong><?= $data->date ?></strong></p>
								<p class="ui-li-aside" style="position: absolute;top: 60%;right: 14px;"><span><a style="color: #0060AA;" href="mailto:<?= substr($value->publisherID, 6) ?>"><?= substr($value->publisherID, 6) ?></a></span></p>
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
							<br />
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

<!DOCTYPE html>
<html>

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
		
	if (count($_POST)) {
		if(!isset($_SESSION['user'])) {
			$request = new Request("AuthenticationRequestHandler", READ);
			$request->addArgument("login", $_REQUEST["login"]);
			$request->addArgument("password", hash('sha512', $_REQUEST["password"]));
		
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			if($responseObject->status == 200) {
				$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
				//$_SESSION['user'] = $responseObject->dataObject->user;
				$request = new Request("SessionRequestHandler", READ);
				$request->addArgument("socialNetwork", "myMed");
				$responsejSon = $request->send();
				$session = json_decode($responsejSon);
				if($session->status == 200) {
					$_SESSION['user'] = $session->dataObject->user;
					if(!isset($_SESSION['friends'])){
						$_SESSION['friends'] = array();
					}
					header("Location: .");
				} else {
					header("Location: ./authenticate");
				}
		
			} else{
				header("Location: ./authenticate");
			}
		} else{
			header("Location: ./option?please-logout-first");
		}
	}
	
	$sub = false;
	
	//get all results

	/*ksort($_GET); // important to match a possible predicate, keys must be ordered
	$predicate = "";
	if (count($_GET)) {
		foreach( $_GET as $i => $value ){
			if ( $i!='application' && $i!='method' && $i[0]!='_' && $value!=''){
				$predicate .= $i . $value;
			}
		}
		$request = new Request("SubscribeRequestHandler", CREATE);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", $predicate);
		$request->addArgument("user", json_encode($_SESSION['user']));
		$responsejSon = $request->send();
		$sub = true;
	}*/
	
	$tags = explode("+", $_GET['q']);
	sort($tags); //important
	$predicate = "";
	foreach( $tags as $v ){ //do this for FindRequestHandler compatibility...
		$predicate .= strtolower($v);
	}

	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $application);
	$request->addArgument("predicate", $predicate);
	$responsejSon = $request->send();
	$res = json_decode($responsejSon);
	
?>
	<head>
		<?= $template->head(); ?>
	</head>

	<body>
		<div data-role="page" id="Search">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="about" data-theme="b" type="button" data-icon="info" data-transition="slide" data-direction="reverse">about</a>
					<h2>myEurope</h2>
					<a id="opt" href=<?= $_SESSION['user']?"option":"authenticate" ?> class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
				</div>
				<div data-role="content">	
					<form action="search" id="subscribeForm">
						<input name="q" placeholder="chercher un partenaire par mot clés" value="" data-type="search" style="width: 80%;"/>
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

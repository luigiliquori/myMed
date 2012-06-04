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
	 *  if param _sub is present it will attempt a mail subscription for this predicate
	 *  
	 *  ex: yourPC/application/myEurope/search?application=myTemplate&keyword=2 (publish something on mytemplate with keyword 2)
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
	$application = isset($_REQUEST['application'])?$_REQUEST['application']:"myEurope";
		
	ksort($_GET); // important to match a possible predicate, keys must be ordered
	ksort($_REQUEST);
	
	//debug("post l ".count($_POST));
	//debug("get l ".count($_GET));
	
	$session = new stdClass(); $session->status = false;
	
	if (count($_POST) > 1){ // to authenticate
	
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
					//header("Location: ./");
				}
					
			}else{
				//header("Location: ./");
			}
	
		}
	} else { //try to see is we have a session ongoing
		$request = new Request("SessionRequestHandler", READ);
		if(isset($_REQUEST['accessToken'])){
			$request->addArgument("socialNetwork", $_REQUEST['accessToken']);
		} else {
			$request->addArgument("socialNetwork", "myMed");
		}
		if(isset($_SESSION['accessToken'])) {
		
			$responsejSon = $request->send();
			$session = json_decode($responsejSon);
			if($session->status == 200) {
				$_SESSION['user'] = $session->dataObject->user;
				if(!isset($_SESSION['friends'])){
					$_SESSION['friends'] = array();
				}
			}
		}
	}
	
	
	$predicate = "";
	$sub = false;
	if (count($_GET) > 1){ // to subscribe for this result
		foreach( $_REQUEST as $i => $value ){
			if ( $i!='application' && $i[0]!='_' && $value!=''){
				$predicate .= $i . $value;
			}
		}
		$request = new Request("SubscribeRequestHandler", CREATE);
		$request->addArgument("application", $application);
		$request->addArgument("predicate", $predicate);
		$request->addArgument("user", json_encode($_SESSION['user']));
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if ($responseObject->status==200){
			$sub = true;
		}
	}
	
	
	
	$predicate = ""; 
	$_GET["~"] = "";//we add ~ in predicates (tags in all texts) so we get all results by default
	foreach( $_GET as $i => $value ){
		if ( $i!='application' && $i[0]!='_' && ($value!='' || $i=='~')){
			$predicate .= $i . $value;
		}
	}
	
	

	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $application);
	$request->addArgument("predicate", $predicate);
	$responsejSon = $request->send();
	$res = json_decode($responsejSon);
?>
	<head>
		<?= $template->head(); ?>
		<script src="lib/jquery.dataTables.nightly.js">
        </script>
		<script type="text/javascript">
			$(document).live("pageshow", function(){
				console.log("pageshow");
				$('#example').dataTable();
				$('#Search').trigger('pagecreate');
			});
		</script>
	</head>

	<body>
		<div data-role="page" id="Search">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<h2>myEurope</h2>
					<a href=<?= $session->status==200?"option":"authenticate" ?> data-icon="forward" class="ui-btn-right"><?= $session->status==200?$_SESSION['user']->name:"Connexion" ?></a>
				</div>
				<div data-role="content">
					<div style="text-align: center;">
					<br />
					PROVENCE-ALPES-COTE D'AZUR : Rechercher un ou plusieurs projets cofinancés par l'Union européenne
					</div>
					<br />
					<table width="100%" cellpadding="0" align="center" cellspacing="0" border="0" class="display" id="example">
						<thead>
							<tr>
								<th>Nom de l'organisme bénéficiaire</th>
								<th>Libellé du projet</th>
								<th>Coût total du projet (en euros)</th>
								<th>Montant du financement européen (en euros)</th>
								<th>Date de clôture</th>
								<th>Contact</th>	
							</tr>
						</thead>
						<tbody>
						<?php 
							
						if($res->status == 200) {
							$res = $res->dataObject->results;
							foreach( $res as $i => $value ){
								$preds = json_decode($value->data);
							?>
							<tr id="tr_<?= $i ?>" class="gradeA" onmouseover="$(this).css('cursor','pointer');" onclick="$('#detailForm<?= $i ?>').submit();">
								<td class="center"><?= $preds->nom ?></td>
								<td class="center"><?= $preds->lib ?></td>
								<td class="center"><?= $preds->cout ?></td>
								<td class="center"><?= $preds->montant ?></td>
								<td class="center"><?= $preds->date ?></td>
								<td class="center">
									<a href="mailto:<?= substr($value->publisherID, 6) ?>" target="_blank">mail</a>
									<form action="detail" id="detailForm<?= $i ?>">
										<input name="application" value='<?= $application ?>' type="hidden" />
										<input name="predicate" value='<?= $value->predicate ?>' type="hidden" />
										<input name="user" value='<?= $value->publisherID ?>' type="hidden" />
									</form>
								</td>
							</tr>
							<?php 
							}
						}
					
						?>
						</tbody>
					</table>
					
					<br /><br />
					
						
					
					<div data-role="collapsible" data-mini="true" style="width:80%;margin-right: auto; margin-left: auto;">
						<h3>Recherche avancée</h3>
						<form  data-ajax="false" action="#" id="subscribeForm">
							<div>
							<input name="application" value='<?= $application ?>' type="hidden" />
							<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
								<fieldset data-role="controlgroup" >
									<label for="textinputs1"> Nom de l'organisme bénéficiaire: </label> <input id="textinputs1"  name="nom" placeholder="" value="" type="text" />
								</fieldset>
							</div>
							<div data-role="fieldcontain" style="margin-left: auto;margin-right: auto;">
								<fieldset data-role="controlgroup" >
									<label for="textinputs2"> Libellé du projet: </label> <input id="textinputs2"  name="lib" placeholder="" value="" type="text" />
								</fieldset>
							</div>
							<a href="" type="button" data-icon="gear" onclick="$('#subscribeForm').submit();" style="width:280px;margin-left: auto;margin-right: auto;">rechercher</a></div>
						</form>
					</div>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>

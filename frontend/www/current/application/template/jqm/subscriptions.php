<?php 
	//in case you refresh page
	require_once dirname(__FILE__).'/TemplateManager.class.php';
	$template = new TemplateManager();
	$template->getHead();
	// DEBUG
	//require_once('PhpConsole.php');
	//PhpConsole::start();

?>


<div data-role="page" id="Subscriptions">
	<div data-theme="b" data-role="header" data-position="fixed">
		<h3>Souscriptions</h3>
		<div data-role="navbar" data-iconpos="left">
			<ul>
				<li><a href="#Find" data-theme="c" data-icon="search"> Chercher </a></li>
				<li><a href="#Publish" data-theme="c" data-icon="grid"> Publier </a></li>
				<li><a href="#Subscribe" data-theme="c" data-icon="alert"> Souscrire </a></li>
			</ul>
		</div>
	</div>
	<div data-role="content">
		<ul data-role="listview" data-filter="true">
		
<?php
	require_once('../../../lib/dasp/request/Request.class.php');
	require_once('../../../system/config.php');
	session_start();
	
	$responseObject = new stdClass();$responseObject->success = false;
	
	$request = new Request("SubscribeRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("userID", $_SESSION['user']->id);
	
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status != 200) {
		echo '<script type="text/javascript">alert(\'' . $responseObject->description . '\');</script>';
	}else{
		$res = $responseObject->dataObject->subscriptions;

		foreach( $res as $i => $value ){ 
			?>
		<li>
			<a href="" onclick="$(this).prev('form').submit(); return false;">
			<?= $value ?>
				<form action="unsubscribe.php" id="deleteSubscriptionForm<?= $i ?>">
					<input name="application" value=<?= $_REQUEST['application'] ?> type="hidden" />
					<input name="predicate" value=<?= $value ?> type="hidden" />
					<input name="userID" value=<?= $_SESSION['user']->id ?> type="hidden" />
				</form>
				<a href="javascript://" data-icon="delete" data-theme="r" onclick="$('#deleteSubscriptionForm<?= $i ?>').submit();">stop this</a>
			</a>
		</li>
		<?php }
		
	}
	//echo json_encode($responseObject);
?>
		
		</ul>
	</div>

</div>



<div data-role="page" id="Results">
	<div data-theme="b" data-role="header" data-position="fixed">
		<h3>Résultats</h3>
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
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	
	$predicate = "";
	foreach( $_REQUEST as $i => $value ){
		if ( $i!='application' && $i[0]!='_' && $value && $value!='false' && $value!='' ){
			$predicate .= $i . $value;
		}		
	}
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $predicate);
	
	$responsejSon = $request->send();
	debug($responsejSon);
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
		$results = $responseObject->dataObject->results;
		foreach( $results as $i => $value ){ 
			$profPic = $value->publisherProfilePicture ? publisherProfilePicture : "http://graph.facebook.com//picture?type=large";
			$date = $value->end ? " le ".$value->end : " ";
			$position = $value->data ? " à ".$value->data : "";
			?>
		<li><form action="getDetail.php">
			<input name="application" value="myTemplate" type="hidden" />
			<input name="predicate" value=<?= $value->predicate ?> type="hidden" />
			<input name="user" value=<?= $value->publisherID ?> type="hidden" />
			<input name="profPic" value=<?= $profPic ?> type="hidden" />
			<input name="index" value=<?= $i ?> type="hidden" />
			</form>
			<a href="" onclick="$(this).prev('form').submit(); return false;" style="padding-top: 1px;padding-bottom: 1px;">
				<div class="row"><img src='<?= $profPic ?>' width="60" height="60" /></div>
				<div class="row" style="padding-left: 10px;">
				<?= $value->publisherName ?>
				<?= $date ?>
				<?= $position ?>
				</div>
		</a></li>
		<?php }
		
	}
	//echo json_encode($responseObject);
?>
		
		</ul>
	</div>
	<script language=javascript>
		results = <?php echo json_encode($results);?>
	</script>
</div>


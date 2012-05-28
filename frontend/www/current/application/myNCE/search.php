<!DOCTYPE html>
<html>

<?php

	/*
	 * search.php
	 * 
	 * arguments: 
	 * 		application
	 * 		predicate
	 */


	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	$template->head();
	// DEBUG
	require_once('PhpConsole.php');
	PhpConsole::start();
	debug('boo '.dirname(__FILE__));
	
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	
	ksort($_REQUEST); // important to match a possible predicate, keys must be ordered
	
	$predicate = "";
	foreach( $_REQUEST as $i => $value ){
		if ( $i!='application' && $i[0]!='_' && $value && $value!='false' && $value!='' ){
			$predicate .= $i . $value;
		}
	}
	
	$sub = false;
	if (isset($_REQUEST['_sub'])){ // to subscribe for this result
		$request = new Request("SubscribeRequestHandler", CREATE);
		$request->addArgument("application", $_REQUEST['application']);
		$request->addArgument("predicate", $predicate);
		$request->addArgument("user", json_encode($_SESSION['user']));
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if ($responseObject->status==200){
			$sub = true;
		}
	}
	
	
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $predicate);
	$responsejSon = $request->send();
	$res = json_decode($responsejSon);
?>

	<body>
		<div data-role="page" id="Search">
			<div data-role="header" data-theme="e">
				<a href="home.html" data-icon="back"> Back </a>
				<h2>myNCE Recherche</h2>
				<form action="#Search" method="post" id="commentForm">
					<input name="application" value="myNCE" type="hidden" />
					<input name="predicate" value="<?= $_REQUEST['predicate'] ?>" type="hidden" />
					<input name="_sub" value="" type="hidden" />
					<a href="" type="button" class="<?= $sub?"ui-disabled":"" ?> ui-btn-right" data-inline="true" data-iconpos="right" data-icon="alert" onclick="$('#commentForm').submit();">Souscrire</a>
				</form>
			</div>
			<div data-role="content">

			<ul data-role="listview" data-filter="true" data-divider-theme="b" data-inset="true">
				<?php 
					
				if($res->status == 200) {
					$res = $res->dataObject->results;
					foreach( $res as $value ){
						$profPic = $value->publisherProfilePicture ? $value->publisherProfilePicture : "http://graph.facebook.com//picture?type=large";
						$date = $value->end ? " le ".$value->end : " ";
						$position = $value->data ? " à ".$value->data : "";
						?>
				<li><form action="detail.php">
						<input name="application" value="myNCE" type="hidden" />
						<input name="predicate" value=<?= $value->predicate ?> type="hidden" />
						<input name="user" value=<?= $value->publisherID ?> type="hidden" />
					</form> <a href="" onclick="$(this).prev('form').submit(); return false;" style="padding-top: 1px; padding-bottom: 1px;">
						<div class="row">
							<img src='<?= $profPic ?>' width="60" height="60" />
						</div>
						<div class="row" style="padding-left: 10px;">
							<?= $value->publisherName ?>
							<?= $date ?>
							<?= $position ?>
						</div>
				</a></li>
				<?php 
				}
			}
		
			?>
			</ul>


		</div>
	</body>
</html>

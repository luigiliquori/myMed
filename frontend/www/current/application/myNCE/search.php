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
	 *  ex: yourPC/application/myNCE/search?application=myTemplate&keyword=2 (publish something on mytemplate with keyword 2)
	 */


	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();
	$template->head();
	// DEBUG
	//require_once('PhpConsole.php');
	//PhpConsole::start();
	//debug('boo '.dirname(__FILE__));
	
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
				<form action="#Search" method="post" id="subscribeForm">
					<input name="application" value="<?= $_REQUEST['application'] ?>" type="hidden" />
					<input name="_sub" value="" type="hidden" />
					<a href="" type="button" class="<?= $sub?"ui-disabled":"" ?> ui-btn-right" data-inline="true" data-iconpos="right" data-icon="alert" onclick="$('#commentForm').submit();">Souscrire</a>
				</form>
			</div>
			<div data-role="content">

				<ul data-role="listview" data-filter="true" data-divider-theme="b" data-inset="true" data-filter-placeholder="...">
				<?php 
					
				if($res->status == 200) {
					$res = $res->dataObject->results;
					foreach( $res as $value ){
						$profPic = $value->publisherProfilePicture ? $value->publisherProfilePicture : "http://graph.facebook.com//picture?type=large";
						$date = $value->end ? " <span style='font-weight:lighter;'>le</span> ".$value->end : " ";
						$position = $value->data ? " <span style='font-weight:lighter;'>à</span> ".$value->data : "";
						?>
					<li><form action="detail.php">
							<input name="application" value="<?= $_REQUEST['application'] ?>" type="hidden" />
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
		</div>
	</body>
</html>

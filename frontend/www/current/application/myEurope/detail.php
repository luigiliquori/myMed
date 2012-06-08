<!DOCTYPE html>
<html>

<?php

	/*
	 * 
	 * usage:
	 *  detail?application=val1&predicate=val2&user=val3
	 * 
	 * what it does:
	 *  display the data identified by predicate and user in the given application
	 *  
	 *  you can post a comment on this data
	 *  you can delete either text (if we are the author) or our comment (only our comments not other's or change it if you want)
	 */		

	//ob_start("ob_gzhandler");
	require_once 'Template.class.php';
	$template = new Template();

	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	
	$msg = ""; //feedback text
	
	if (isset($_POST['commentOn'])){ // we want to comment

		$predicates = array(
			array("key"=>"commentOn", "value"=>$_POST['commentOn']),
			array("key"=>"end", "value"=>$_POST['end']),
		);
		$data = array(array("key"=>"data", "value"=>$_POST['data']));
		//$data = array_merge($predicates, $data);
		$request = new Request("PublishRequestHandler", CREATE);
		$request->addArgument("application", $_POST['application']);
		$request->addArgument("predicate", json_encode($predicates));
		$request->addArgument("data", json_encode($data));
		if(isset($_SESSION['user'])) {
			$request->addArgument("user", json_encode($_SESSION['user']));
		}
		$responsejSon = $request->send();
		
	} else if (isset($_POST['predicates'])) { //delete text or comment
		$request = new Request("PublishRequestHandler", DELETE);
		$request->addArgument("application", $_POST['application']);
		$request->addArgument("predicate", urldecode($_POST['predicates']));
		$request->addArgument("user", json_encode($_SESSION['user']) );
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$msg = "supprimé";
		}
	}
	
	if(isset($_POST['feedback'])) {
		require_once '../../lib/dasp/request/StartInteraction.class.php';
		$startInteraction = new StartInteraction();
		$responsejSon = $startInteraction->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$msg = "vote pris en compte";
		}
	}
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", urldecode($_GET['predicate']));
	$request->addArgument("user", $_GET['user']);
	$responsejSon = $request->send();
	$detail = json_decode($responsejSon);
	
	$request = new Request("ProfileRequestHandler", READ);
	$request->addArgument("id", $_GET['user']);
	$responsejSon = $request->send();
	$profile = json_decode($responsejSon);
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", "commentOn" . $_GET['predicate']);
	$responsejSon = $request->send();
	$comments = json_decode($responsejSon);
	$totalCom = 0;
	if($comments->status == 200) {
		$totalCom = count($comments->dataObject->results);
	}
	
	$request = new Request("ReputationRequestHandler", READ);
	$request->addArgument("application",  $_REQUEST['application']);
	$request->addArgument("producer",  $_GET['user']);
	$request->addArgument("consumer",  $_SESSION['user']->id);
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	$reputation = 50;
	if(isset($responseObject->dataObject->reputation)){
		$i=0;
		$reputation = round($responseObject->dataObject->reputation * 100);
	}

	
?>
	<head>
		<?= $template->head(); ?>
	</head>

	<body>
        <div data-role="page" id="Detail">
        	<div class="wrapper">
	        	<div data-role="header" data-theme="b">
	        		<a href="./" data-icon="home" data-iconpos="notext" > Accueil </a>
					<h3>myEurope - détail</h3>
				</div>
	            <div data-role="content">
				<div style='color:lightGreen;text-align:center;'><?= $msg ?></div>
				<?php
			
				if($profile->status == 200) {
					$profile = $profile->dataObject->user;
					$profPic = ($profile->profilePicture) ? $profile->profilePicture : "http://graph.facebook.com//picture?type=large";
				}
		
				if($detail->status == 200) {
					$detail = $detail->dataObject->details;
					$text="";
					foreach( $detail as $value ) {
						if ($value->key=="text"){
							$text = str_replace("\n", "<br />", $value->value);
						} else if ($value->key=="data"){
							$preds = json_decode($value->value);
						} else if ($value->key=="deleteme"){
							$deleteme = urlencode($value->value);
						}
					}
					
					?>
					<div style="float:right;text-align:center;">
						<img style="text-align: center; max-height: 100px;opacity: 0.6;" src="<?= $profPic ?>" /><br />
						<b>Réputation</b>: 
						<a data-role="button" data-icon="minus" data-iconpos="notext" data-inline="true" onclick="$('#feedback').val('0'); document.StartInteractionForm.submit();"></a>
						<span id="author-rep"><?= $reputation ?>%</span>
						<a data-role="button" data-icon="plus" data-iconpos="notext" data-inline="true" onclick="$('#feedback').val('1'); document.StartInteractionForm.submit();"></a>
		    			
					</div>
					
					<form id="StartInteractionForm" action="#" method="post" name="StartInteractionForm" id="StartInteractionForm" enctype="multipart/form-data">
						<input type="hidden" name="application" value="<?= $_REQUEST['application'] ?>" />
						<input type="hidden" name="producer" value="<?= $_REQUEST['user'] ?>" />
						<input type="hidden" name="consumer" value="hib" />
						<input type="hidden" name="start" value="<?= time() ?>" />
						<input type="hidden" name="end" value="<?= time() ?>" />
						<input type="hidden" name="predicate" value="<?= $_REQUEST['predicate'] ?>" />
						<input type="hidden" name="feedback" value="" id="feedback"/>
					</form>
					
					<b>Auteur</b>: <span style="left-margin:5px; color: #0060AA; font-size:160%;"><?= $profile->name ?></span>
					<br />
					<br /><br /><br />
					<b>Nom de l'organisme bénéficiaire:</b>&nbsp; <span style="left-margin:5px; color: #0060AA; font-size:140%;"><?= $preds->nom ?></span><br />
					<b>Libellé du projet:</b>&nbsp; <span style="left-margin:5px; color: #0060AA; font-size:140%;"><?= $preds->lib ?></span><br />
					<br />
					<b>Texte</b>:
					<br /><br /><br />
					<div id="detailstext"><?= $text ?></div>
					<br />
				<?php 
				}
		
				?>
			
				<?php 
				
				if ($_GET['user'] == $_SESSION['user']->id){ //we can delete our own text
					?>
					<form action="#" method="post" id="deleteForm">
						<input name="application" value='<?= $_REQUEST['application'] ?>' type="hidden" />
						<input name="predicates" value='<?= $deleteme ?>' type="hidden" />
						<input name="user" value='<?= $_REQUEST['user'] ?>' type="hidden" />
					</form>
					<a href="" type="button" data-theme="r" data-icon="delete" onclick="$('#deleteForm').submit();" style="width:270px;margin-left: auto; margin-right: auto">Supprimer ce document</a>
					<?php 
					}
		
					?>
				
					<a id="CommentButton" href="" type="button" data-icon="arrow-d" onclick="showComment();" style="width: 300px;margin-left: auto; margin-right: auto;">Afficher les commentaires (<?= $totalCom ?>)</a>
		
					<div id="Comments" style="text-align: center;display: none;">
						<ul data-role="listview" data-inset="true" data-divider-theme="c">
						<?php

				    	if($comments->status == 200) {
				    		$comments = $comments->dataObject->results;
					    	foreach($comments as $i => $value) { ?>
				    		<li data-role="list-divider">
				    			<form action="detail?<?= $_SERVER['QUERY_STRING'] ?>" method="post" id="deleteCommentForm<?= $i ?>">
									<input name="application" value='<?= $_REQUEST['application'] ?>' type="hidden" />
									<?php 
										$predicates = array(
											array("key"=>"commentOn", "value"=>$_REQUEST['predicate']),
											array("key"=>"end", "value"=>$value->end)
										);
									?>
									<input name="predicates" value='<?= urlencode(json_encode($predicates)) ?>' type="hidden" />
									<input name="user" value='<?= $value->publisherID ?>' type="hidden" />
								</form>
				    			<?= $value->publisherName ?><span style="margin-left:15px;font-weight:lighter;"> le <?= $value->end ?></span>
				    			<div style="position: absolute;right: 0;top: -5px;">
					    			<a href="" type="button" data-inline=true data-iconpos="notext" 
					    				<?php 
									if ($value->publisherID == $_SESSION['user']->id){ //we can delete our comments
										?>
					    				data-theme="r" data-icon="delete" onclick="$('#deleteCommentForm<?= $i ?>').submit();"
					    				<?php
									} else {
									?>
										data-theme="" data-icon="plus"
									<?php
									}
									?>
				    				></a>
				    			</div>
				    		</li>
				    		<li><?= $value->data ?></li>					    		
					    <?php } 
						}
						?>
						</ul>
				
			
					</div>
					<div id="Commenter" style="text-align: center;display: none;">
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<form action='#' method="post" id="commentForm">
									<input name="application" value='<?= $_REQUEST['application'] ?>' type="hidden" />
									<input name="commentOn" value='<?= $_REQUEST['predicate'] ?>' type="hidden" />
									<input name="end" value='<?= date("Y-m-d") . "T" . date("H:i:s") ?>' type="hidden" />
									<textarea name="data" id="textarea1" placeholder="" style="height: 22px;"></textarea>
									<a href="" type="button" data-inline="true" data-mini=true data-iconpos="right" data-icon="check" onclick="$('#commentForm').submit();">Commenter</a>
									
								</form>
							</fieldset>
						</div>
					</div>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>



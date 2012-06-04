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
	 *  if param commentOn is present it will attempt to publish a comment on this data
	 *  if param predicates is present it will attempt to delete either text (if we are the authod) or our comment (only our comments not other's or change it if you want)
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
	
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}
	
	if (isset($_POST['commentOn'])){ // we want to comment
		$predicates = Array();
		$data = Array();
		foreach( $_POST as $i => $value ){
			if ( $i!='application' && $i[0]!='_' && $value && $value!='false' && $value!='' ){
				$ontology = new stdClass();
				$ontology->key = $i;
				$ontology->value = $value;
				$ontology->ontologyID = isset($_POST['_'.$i])?$_POST['_'.$i]:0; // '_'.$i form fields contain the ontologyID of the value
	
				if(isset($_POST['_'.$i])){
					array_push($data, $ontology);
				}else{
					array_push($predicates, $ontology);
				}
			}
		}
		usort($predicates, "cmp"); // VERY important, to be able to delete the exact same predicates later
		$data = array_merge($predicates, $data);
	
		$request = new Request("PublishRequestHandler", CREATE);
		$request->addArgument("application", $_POST['application']);
		$request->addArgument("predicate", json_encode($predicates));
	
		$request->addArgument("data", json_encode($data));
		if(isset($_SESSION['user'])) {
			$request->addArgument("user", json_encode($_SESSION['user']));
		}
	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	}
	
	if (isset($_POST['predicates'])){ // to delete our text or comment
		$request = new Request("PublishRequestHandler", DELETE);
		$request->addArgument("application", $_POST['application']);
		$request->addArgument("predicate", $_POST['predicates']);
		$request->addArgument("user", json_encode($_SESSION['user']) );
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			header("Refresh:1;url=/application/myNCE/search");
		}
	}
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $_REQUEST['predicate']);
	$request->addArgument("user", $_REQUEST['user']);
	$responsejSon = $request->send();
	$detail = json_decode($responsejSon);
	
	$request = new Request("ProfileRequestHandler", READ);
	$request->addArgument("id", $_REQUEST['user']);
	$responsejSon = $request->send();
	$profile = json_decode($responsejSon);
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", "commentOn" . $_REQUEST['predicate']);
	$responsejSon = $request->send();
	$comments = json_decode($responsejSon);
	$totalCom = 0;
	if($comments->status == 200) {
		$totalCom = count($comments->dataObject->results);
	}

	
?>
	<head>
		<?= $template->head(); ?>
		<script type="text/javascript">
	    	$(".ui-slider-handle .ui-btn-inner").live("mouseup", function() {
		    <?php 
				if ($_REQUEST['user'] == $_SESSION['user']->id){ //we can't update it's reputation
				?>
		        $("#slider-0").val(25).slider("refresh");
		        <?php
				} 
			?>
		    });
		</script>
	</head>

	<body>
        <div data-role="page" id="Detail">
        	<div class="wrapper">
	        	<div data-role="header" data-theme="b">
					<a href="search" data-icon="back" data-ajax=false> Retour </a>
					<h3>myEurope - détail</h3>
				</div>
	            <div data-role="content">
		
				<?php
			
				if($profile->status == 200) {
					$profile = $profile->dataObject->user;
					$profPic = ($profile->profilePicture) ? $profile->profilePicture : "http://graph.facebook.com//picture?type=large";
				}
		
				if($detail->status == 200) {
					$detail = $detail->dataObject->details;
					$text="";
					foreach( $detail as $i => $value ) {
						if ($value->key=="desc"){
							$text = str_replace("\n", "<br />", $value->value);
							array_splice($detail, $i, 1);
						}
					}
					foreach( $detail as $i => $value ) {
						if ($value->key=="data"){
							$preds = json_decode($value->value);
							array_splice($detail, $i, 1);
						}
					}
					foreach( $detail as $value ) {
						//debug($value->key." -> ".$value->value);
						if ($value->key=="data"){
							array_splice($detail, $i, 1);
						}
					}
					
					usort($detail, "cmp"); //important, we sorted also when we published this predicates.
					
					// Todo add a profile request on the publisher to get his reputation
					?>
					<img style="float:right;text-align: center; max-height: 100px;opacity: 0.6;" src="<?= $profPic ?>" />
					<b>Auteur</b>: <span style="left-margin:5px; color:DarkBlue; font-size:160%;"><?= $profile->name ?></span>
					<br />
					<b>Réputation</b>: <input type="range" name="slider" id="slider-0" value="25" min="0" max="100" data-highlight="true" data-mini="true" /> 
					<br /><br /><br />
					<b>Nom de l'org:</b>&nbsp; <span style="left-margin:5px; color:DarkBlue; font-size:140%;"><?= $preds->nom ?></span><br />
					<b>Libellé du projet:</b>&nbsp; <span style="left-margin:5px; color:DarkBlue; font-size:140%;"><?= $preds->lib ?></span><br />
					<b>Texte</b>:
					<br /><br /><br />
					<div id="detailstext"><?= $text ?>
					
					But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?
					
					</div>
					<br />
				<?php 
				}
		
				?>
			
				<?php 
				if ($_REQUEST['user'] == $_SESSION['user']->id){ //we can delete our own text
					?>
					<form action="#" method="post" id="deleteForm">
						<input name="application" value="<?= $_REQUEST['application'] ?>" type="hidden" />
						<input name="predicates" value=<?= json_encode($detail) ?> type="hidden" />
						<input name="user" value="<?= $_REQUEST['user'] ?>" type="hidden" />
					</form>
					<a href="" type="button" data-theme="r" data-icon="delete" onclick="$('#deleteForm').submit();" style="width:222px;float:right;">Supprimer cette fiche</a>
					<?php 
					}
		
					?>
				
					<a id="CommentButton" href="" type="button" data-icon="arrow-d" onclick="showComment();" style="width: 300px;margin-left: auto; margin-right: auto">Afficher les commentaires (<?= $totalCom ?>)</a>
		
					<div id="Comments" style="text-align: center;display: none;">
						<ul data-role="listview" data-inset="true">
						<?php

				    	if($comments->status == 200) {
				    		$comments = $comments->dataObject->results;
					    	foreach($comments as $i => $value) { ?>
					    		<li>
					    		<div style="width:100%; overflow:hidden;">
					    			<form action="#" method="post" id="deleteCommentForm<?= $i ?>">
										<input name="application" value="<?= $_REQUEST['application'] ?>" type="hidden" />
										<?php 
											$commentOn = new stdClass();
											$commentOn->key = "commentOn";
											$commentOn->value = $_REQUEST['predicate'];
											$commentOn->ontologyID = 0;
											$end = new stdClass();
											$end->key = "end";
											$end->value = $value->end;
											$end->ontologyID = 0;
											$predicates=array($commentOn, $end);
										?>
										<input name="predicates" value=<?= json_encode($predicates) ?> type="hidden" />
										<input name="user" value="<?= $value->publisherID ?>" type="hidden" />
									</form>
									<div style="display:inline; float:left; height:40px; line-height:40px;font-weight:lighter;"><?= $value->data ?></div>
					    			<div style="display:block;float:right;font-weight:lighter;"><b style="margin-right: 15px;"><?= $value->publisherName ?></b> le <?= $value->end ?>
					    			<?php 
									if ($_REQUEST['user'] == $_SESSION['user']->id){ //we can delete our comments
										?>
										<a href="" type="button" data-inline=true data-theme="r" data-iconpos="notext" data-icon="delete" onclick="$('#deleteCommentForm<?= $i ?>').submit();"></a>
										<?php
									} 
									?>
									</div>
								</div>

								</li>
					    		
								
								
					    		
					    	<?php } 
						}
						?>
						</ul>
				
			
					</div>
					<div id="Commenter" style="text-align: center;display: none;">
						<div data-role="fieldcontain">
							<fieldset data-role="controlgroup">
								<form action="#" method="post" id="commentForm">
									
									<input name="application" value="<?= $_REQUEST['application'] ?>" type="hidden" />
									<input name="commentOn" value="<?= $_REQUEST['predicate'] ?>" type="hidden" />
									<input name="end" value="<?= date("Y-m-d") . "T" . date("H:i:s") ?>" type="hidden" />
									<input name="_data" value="" type="hidden" />
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



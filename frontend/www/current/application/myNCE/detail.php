<!DOCTYPE html>
<html>

<?php

	/*
	 *  detail.php
	 *  
	 *  arguments : 
	 *  	application
	 *  	predicate
	 *  	user
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
	
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}
	
	
	if (isset($_REQUEST['commentOn'])){
		$predicates = Array();
		$data = Array();
		foreach( $_REQUEST as $i => $value ){
			if ( $i!='application' && $i[0]!='_' && $value && $value!='false' && $value!='' ){
				$ontology = new stdClass();
				$ontology->key = $i;
				$ontology->value = $value;
				$ontology->ontologyID = isset($_REQUEST['_'.$i])?$_REQUEST['_'.$i]:0; // '_'.$i form fields contain the ontologyID of the value
	
				if(isset($_REQUEST['_'.$i])){
					array_push($data, $ontology);
				}else{
					array_push($predicates, $ontology);
				}
			}
		}
		usort($predicates, "cmp"); // VERY important, to be able to delete the exact same predicates later
		$data = array_merge($predicates, $data);
	
		$request = new Request("PublishRequestHandler", CREATE);
		$request->addArgument("application", $_REQUEST['application']);
		$request->addArgument("predicate", json_encode($predicates));
	
		$request->addArgument("data", json_encode($data));
		if(isset($_SESSION['user'])) {
			$request->addArgument("user", json_encode($_SESSION['user']));
		}
	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	}
	
	if (isset($_REQUEST['predicates'])){ // to delete our comment
		$request = new Request("PublishRequestHandler", DELETE);
		$request->addArgument("application", $_REQUEST['application']);
		$request->addArgument("predicate", $_REQUEST['predicates']);
		$request->addArgument("user", json_encode($_SESSION['user']) );
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	}
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $_REQUEST['predicate']);
	$request->addArgument("user", $_REQUEST['user']);
	$responsejSon = $request->send();
	$detail = json_decode($responsejSon);
	
	$request = new Request("ProfileRequestHandler", READ);
	$request->addArgument("id", $_SESSION["user"]->id);
	$responsejSon = $request->send();
	$profile = json_decode($responsejSon);
	
	
	
?>



	<body>
        <div data-role="page" id="Detail">
        	<div data-role="header" data-theme="e">
				<a href="home.html" data-icon="back"> Back </a>
				<h3>myNCE - détail</h3>
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
				foreach( $detail as $i => $value ){
					if ($value->key=="text"){
						$text = str_replace("\n", "<br />", $value->value);
						array_splice($detail, $i, 1);
					}	
				}
				usort($detail, "cmp"); //important, we sorted also when we published this predicates.
				
				// Todo add a profile request on the publisher to get his reputation
				
				?>
				<img src='<?= $profPic ?>' width="180" style="float:right;" />
				<b>Auteur</b>: <span style="left-margin:5px; color:DarkBlue; font-size:160%;"><?= $profile->name ?></span>
				<br />
				<b>Réputation</b>: <input type="range" name="slider" id="slider-0" value="25" min="0" max="100" data-highlight="true" data-mini="true" /> 
				<br /><br />
				<b>Texte</b>:
				<br /><br /><br /><br />
				<div id="detailstext"><?= $text ?></div>
				<br />
			<?php 
			}
	
			?>
		
			<?php 
			if ($_REQUEST['user'] == $_SESSION['user']->id){ //we can delete our own text
				?>
				<form action="#Detail" id="deleteForm">
					<input name="application" value="myNCE" type="hidden" />
					<input name="predicates" value=<?= json_encode($detail) ?> type="hidden" />
					<input name="user" value=<?= $_REQUEST['user'] ?> type="hidden" />
				</form>
				<a href="" type="button" data-theme="r" data-icon="delete" onclick="$('#deleteForm').submit();" style="width:200px;float:right;">Supprimer le texte</a>
				<?php 
			}

			?>
			
			<div style="height:100px;" id="spacer"></div>
			<hr />
		
			<div style="text-align: center">
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<form action="#Detail" method="post" id="commentForm">
							<label for="textarea1" style="width: 80px;"> Commenter: </label>
							<input name="application" value="myNCE" type="hidden" />
							<input name="commentOn" value="<?= $_REQUEST['predicate'] ?>" type="hidden" />
							<input name="end" value="<?= date("Y-m-d") . "T" . date("H:i:s") ?>" type="hidden" />
							<input name="_data" value="" type="hidden" />
							<textarea name="data" id="textarea1" placeholder="" style="height: 26px;"></textarea>
							<a href="" type="button" data-inline="true" data-iconpos="right" data-icon="check" onclick="$('#commentForm').submit();">Envoyer</a>
							
						</form>
					</fieldset>
				</div>
			</div>
			<a href="" type="button" data-icon="arrow-d" onclick="$('#Comments').show();">Afficher les commentaires</a>
	
			<div id="Comments" style="display: none;">
			<?php
			$request = new Request("FindRequestHandler", READ);
			$request->addArgument("application", $_REQUEST['application']);
			$request->addArgument("predicate", "commentOn" . $_REQUEST['predicate']);
			$responsejSon = $request->send();
			$comments = json_decode($responsejSon);
			
	    	if($comments->status == 200) {
	    		$comments = $comments->dataObject->results;
		    	foreach($comments as $i => $value) { ?>
		    		<form action="#Detail" method="post" id="deleteCommentForm<?= $i ?>">
						<input name="application" value="myNCE" type="hidden" />
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
						<input name="user" value=<?= $value->publisherID ?> type="hidden" />
						<p><?= $value->data ?></p>
		    			<p><b><?= $value->publisherName ?></b> le <?= $value->end ?>
		    			<?php 
						if ($_REQUEST['user'] == $_SESSION['user']->id){ //we can delete our comments
							?>
							<a href="" type="button" data-inline="true" data-theme="r" data-iconpos="notext" data-icon="delete" onclick="$('#deleteCommentForm<?= $i ?>').submit();"></a>
							<?php
						} 
						?>
							
					</form>
		    		
		    	<?php } 
			}
			?>
			
		
				</div>
			</div>
		</div>
	</body>
</html>



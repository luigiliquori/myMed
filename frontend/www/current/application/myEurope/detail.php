<?php

/*
*  detail?application=val1&predicate=val2&user=val3
*
*  display the data identified by predicate and user in the given application
*
*  -> comment on this data
*  -> delete text (if we are the author) 
*  -> delete comment (only our comments not other's or change it if you want)(should be set it according to userPerm level)
*/

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();
Template::checksession();

$msg = ""; //feedback text

$namespace =  urldecode($_GET['namespace']);

$id = urldecode($_GET['id']); // data Id

$author =  urldecode($_GET['user']);

if (isset($_POST['commentOn'])){ // we want to comment

	$predicates = array(
			array("key"=>"commentOn", "value"=>$_POST['commentOn']),
			array("key"=>"end", "value"=>$_POST['end']),
	);
	$data = array(
			array("key"=>"data", "value"=>$_POST['data']),
			array("key"=>"end", "value"=>$_POST['end']),
			array("key"=>"deleteme", "value"=>json_encode($predicates))
	);

	$request = new Request("PublishRequestHandler", CREATE);
	$request->addArgument("application", Template::APPLICATION_NAME);
	$request->addArgument("predicate", json_encode($predicates));
	$request->addArgument("data", json_encode($data));
	if(isset($_SESSION['user'])) {
		$request->addArgument("user", json_encode($_SESSION['user']));
	}
	$responsejSon = $request->send();

} else if (isset($_POST['predicates'])) { //delete text or comment
	$request = new Request("v2/PublishRequestHandler", DELETE);
	$request->addArgument("application", Template::APPLICATION_NAME);
	$request->addArgument("namespace", $namespace);
	//$request->addArgument("predicateList", urldecode($_POST['predicates']));
	if (isset($_POST['id'])) {
		$request->addArgument("id", $id);
	}
	$request->addArgument("level", 3); // this will be enough since we insert everything with level <=3
	$request->addArgument("userID", $_SESSION['user']->id );

	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$msg = "supprimé";
	}
}

if(isset($_GET['feedback'])) {
	$request = new Request("InteractionRequestHandler", UPDATE);
	$request->addArgument("application", Template::APPLICATION_NAME);
	$request->addArgument("producer", $author);
	$request->addArgument("consumer", $_SESSION['user']->id );
	$request->addArgument("start", $_GET['start']);
	$request->addArgument("end", $_GET['end']);
	$request->addArgument("predicate", $id);
	$request->addArgument("feedback", $_GET['feedback']);
	if(isset($_GET['snooze'])){
		$request->addArgument("snooze", $_GET['snooze']);
	}
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$msg = "vote pris en compte";
		
		$request->addArgument("producer", $id);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$msg .= "<br />vote pour ce contenu pris en compte";
		} else if($responseObject->status == 409) {
			$msg .= "<br />déjà voté";
		} else if($responseObject->status == 500) {
			$msg .= "<br />Vous ne pouvez voter pour vore contenu";
		}
		
	} else if($responseObject->status == 409) {
		$msg = "déjà voté";
	} else if($responseObject->status == 500) {
		$msg = "Vous ne pouvez voter pour vous-même";
	}
	
}


$request = new Request("v2/PublishRequestHandler", READ);
$request->addArgument("application", Template::APPLICATION_NAME);
$request->addArgument("namespace", $namespace);
$request->addArgument("id", $id);
$request->addArgument("user", $author);
$responsejSon = $request->send();
$detail = json_decode($responsejSon);

$request = new Request("ProfileRequestHandler", READ);
$request->addArgument("id", $author);
$responsejSon = $request->send();
$profile = json_decode($responsejSon);

$request = new Request("FindRequestHandler", READ);
$request->addArgument("application", Template::APPLICATION_NAME);
$request->addArgument("predicate", "commentOn" . $id);
$responsejSon = $request->send();
$comments = json_decode($responsejSon);
$totalCom = 0;
if($comments->status == 200) {
	$totalCom = count($comments->dataObject->results);
}

$request = new Request("ReputationRequestHandler", READ);
$request->addArgument("application",  Template::APPLICATION_NAME);
$request->addArgument("producer",  $author);
$request->addArgument("consumer",  $_SESSION['user']->id);
$responsejSon = $request->send();
$responseObject = json_decode($responsejSon);
$authorRep = $dataRep =  50;
if(isset($responseObject->dataObject->reputation)){
	$authorRep = round($responseObject->dataObject->reputation->reputation * 100);
	$nbOfRatings = $responseObject->dataObject->reputation->noOfRatings;
	$likes = $responseObject->dataObject->reputation->reputation * $nbOfRatings;
	$dislikes = $nbOfRatings - $likes;
}

$request = new Request("ReputationRequestHandler", READ);
$request->addArgument("application",  Template::APPLICATION_NAME);
$request->addArgument("producer",  $id);
$request->addArgument("consumer",  $_SESSION['user']->id);
$responsejSon = $request->send();
$responseObject = json_decode($responsejSon);
if(isset($responseObject->dataObject->reputation)){
	$dataRep = round($responseObject->dataObject->reputation->reputation * 100);
}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="Detail" data-theme="d">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-icon="back"><?= _("Back") ?></a></li>
					<li><a href="./"  data-icon="home"><?= _('Home') ?></a></li>			
				</ul>
			</div>
		</div>
		<div data-role="content">
			<div style='color: lightGreen; text-align: center;'>
				<?= $msg ?>
			</div>
			<h2 style="text-align: center;">
				<a href="" style="text-decoration: none;"><?= $id ?></a>
			</h2>
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
						$text = str_replace("\n", "<br />", $value->value[0]);
						//unset($value);
					} else if ($value->key=="offre"){
						$rate = $value->value[0];
					}
				}
				$detail = array_values(array_filter($detail, "Template::isPredicate")); // to use details for delete

				/*if ( (100-$rate) != $dataRep ){
					echo 'bioooo';
					Template::updateDataReputation($detail, $dataRep, Template::APPLICATION_NAME, $id, $author );
				}*/
				
				?>
			

			<form id="StartInteractionForm" action="detail" name="StartInteractionForm" id="StartInteractionForm">
				<input type="hidden" name="id" value="<?= $id ?>" />
				<input type="hidden" name="user" value="<?= $author ?>" />
				<input type="hidden" name="application" value="<?= Template::APPLICATION_NAME ?>" />
				<input type="hidden" name="start" value="<?= time() ?>" />
				<input type="hidden" name="end" value="<?= time() ?>" />
				<input type="hidden" name="feedback" value="" id="feedback" />
			</form>

			
			<br /> <br />
			
			<div id="detailstext">
				<?= $text ?>
			</div>
			<br />
			<hr>
			<div data-role="fieldcontain" >
				<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
					<legend>Voter:</legend>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
						onclick="$('#feedback').val('0.2'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('0.4'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('0.6'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('0.8'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('1'); document.StartInteractionForm.submit();"></a>
				</fieldset>
			</div>
			<div data-role="fieldcontain" >
				<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
					<legend>Auteur:</legend>
					<img style="vertical-align: middle; max-height: 40px; opacity: 0.6;" src="http://graph.facebook.com//picture?type=large">
					&nbsp;<a style="color: #0060AA; font-size: 120%;" href="mailto:cyrila.uburtin@gmail.com" class="ui-link">gfn gf </a> 
					&nbsp;Réputation: <span style="left-margin: 5px; color: #0060AA; font-size: 120%;">80 </span>
				</fieldset>
			</div>
			
			<?php 

			if ($author == $_SESSION['user']->id){ //we can delete our own text
				?>
			<form action="#" method="post" id="deleteForm">
				<input name="application" value='<?= Template::APPLICATION_NAME ?>' type="hidden" />
				<input name="predicates" value='<?= urlencode(json_encode($detail)) ?>' type="hidden" />
				<input name="id" value='<?= $id ?>' type="hidden" />
				<input name="user" value='<?= $_REQUEST['user'] ?>' type="hidden" />
			</form>
			<a id="deleteTmp" href="" type="button" data-inline="true" data-mini="true" data-icon="delete" onclick="$('#deleteYes').fadeIn('slow');$('#deleteNo').fadeIn('slow');"
				style="width: 150px;">Supprimer?</a>
			<a id="deleteYes" href="" type="button" data-inline="true" data-mini="true" data-icon="check" onclick="$('#deleteForm').submit();"
				style="width: 80px;display: none;">oui</a>
			<a id="deleteNo" href="" type="button" data-inline="true" data-mini="true" data-icon="back" onclick="$('#deleteYes').fadeOut('slow');$('#deleteNo').fadeOut('slow');"
				style="width: 80px;display: none;">non</a>
			<?php 
			}

			?>

			<a id="CommentButton" data-mini="true" href="" type="button" data-icon="arrow-d" onclick="showComment();"
				style="width: 170px;">Commentaires (<?= $totalCom ?>)
			</a>
			
			
			<?php 
			}
			?>



			<div id="Comments" style="text-align: center; display: none;">
				<ul data-role="listview" data-inset="true" data-divider-theme="c">
					<?php

					if($comments->status == 200) {
						$comments = $comments->dataObject->results;
				    	foreach($comments as $i => $value) { ?>
					<li data-role="list-divider">
						<form action="detail?<?= $_SERVER['QUERY_STRING'] ?>" method="post" id="deleteCommentForm<?= $i ?>">
							<input name="application" value='<?= Template::APPLICATION_NAME ?>' type="hidden" />
							<?php 
							$predicates = array(
									array("key"=>"commentOn", "value"=>$_REQUEST['predicate'], "ontologyID"=>0),
									array("key"=>"end", "value"=>$value->end, "ontologyID"=>0)
							);
							?>
							<input name="predicates" value='<?= urlencode(json_encode($predicates)) ?>' type="hidden" /> <input name="user"
								value='<?= $value->publisherID ?>' type="hidden" />
						</form> <?= $value->publisherName ?><span style="margin-left: 15px; font-weight: lighter;"> le <?= $value->end ?>
					</span>
						<div style="position: absolute; right: 0; top: -5px;">
							<a href="" type="button" data-inline=true data-iconpos="notext"
							<?php
							if ($value->publisherID == $_SESSION['user']->id){ //we can delete our comments
								?> data-theme="r" data-icon="delete"
								onclick="$('#deleteCommentForm<?= $i ?>').submit();" <?php
				    	} else {
				    		?> data-theme="" data-icon="plus"
				    		<?php
				    	}
				    	?>></a>
						</div>
					</li>
					<li><?= $value->data ?></li>
					<?php } 
					}
					?>
				</ul>


			</div>
			<div id="Commenter" style="text-align: center; display: none;">
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<form action='#' method="post" id="commentForm">
							<input name="application" value='<?= Template::APPLICATION_NAME ?>' type="hidden" />
							<input name="commentOn" value='<?= $id ?>' type="hidden" />
							<input name="end" value='<?= date("Y-m-d") . "T" . date("H:i:s") ?>' type="hidden" />
							<textarea name="data" id="textarea1" placeholder="" style="height: 22px;"></textarea>
							<a href="" type="button" data-inline="true" data-mini=true data-iconpos="right" data-icon="check" onclick="$('#commentForm').submit();">Commenter</a>
						</form>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</body>
</html>



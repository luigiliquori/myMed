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
require_once 'Template.php';
Template::init();




$msg = ""; //feedback text

$application = $_GET['application'];

$type = strstr($application, "myEurope", true); // get namespace {part or offer}

$id = $_GET['id']; // data Id

$author = $_GET['user'];

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
	$request->addArgument("application", $application);
	$request->addArgument("predicate", json_encode($predicates));
	$request->addArgument("data", json_encode($data));
	if(isset($_SESSION['user'])) {
		$request->addArgument("user", json_encode($_SESSION['user']));
	}
	$responsejSon = $request->send();

} else if (isset($_POST['predicates'])) { //delete text or comment
	$request = new Request("v2/PublishRequestHandler", DELETE);
	$request->addArgument("application", $application);
	$request->addArgument("predicate", urldecode($_POST['predicates']));
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

if(isset($_POST['feedback'])) {
	require_once '../../lib/dasp/request/StartInteraction.class.php';
	$startInteraction = new StartInteraction();
	$responsejSon = $startInteraction->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$msg = "vote pris en compte";
	} else if($responseObject->status == 409) {
		$msg = "déjà voté";
	} else if($responseObject->status == 500) {
		$msg = "Vous ne pouvez voter pour vous-même";
	}
}

if (isset($_POST['rateNew'])){
	//update
	//construct data queryBean
	$detail = json_decode(urldecode($_POST['detail']));
	$dataList=array();
	foreach( $detail as $value ) {
		if ($value->key=="rate"){
			$dataList[$value->key] = array("valueStart"=>$value->value, "valueEnd"=>5-$_POST['rateNew'], "ontologyID"=>$value->ontologyID);
		} else {
			$dataList[$value->key] = array("valueStart"=>$value->value, "valueEnd"=>$value->value, "ontologyID"=>$value->ontologyID);
		}
	}


	$request = new Request("v2/FindRequestHandler", UPDATE);
	$request->addArgument("application", $application);
	$request->addArgument("predicateList", json_encode($dataList));

	$request->addArgument("id", $id);
	$request->addArgument("level", 3); // this will be enough since we insert everything with level <=3
	$request->addArgument("userID", $author );

	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$msg = "vote pris en compte";
	}

}

$request = new Request("v2/FindRequestHandler", READ);
$request->addArgument("application", $application);
$request->addArgument("predicate", $id);
$request->addArgument("user", $_GET['user']);
$responsejSon = $request->send();
$detail = json_decode($responsejSon);

$request = new Request("ProfileRequestHandler", READ);
$request->addArgument("id", $_GET['user']);
$responsejSon = $request->send();
$profile = json_decode($responsejSon);

$request = new Request("FindRequestHandler", READ);
$request->addArgument("application", $application);
$request->addArgument("predicate", "commentOn" . $id);
$responsejSon = $request->send();
$comments = json_decode($responsejSon);
$totalCom = 0;
if($comments->status == 200) {
	$totalCom = count($comments->dataObject->results);
}

$request = new Request("ReputationRequestHandler", READ);
$request->addArgument("application",  $application);
$request->addArgument("producer",  $_GET['user']);
$request->addArgument("consumer",  $_SESSION['user']->id);
$responsejSon = $request->send();
$responseObject = json_decode($responsejSon);
$reputation = 50;
if(isset($responseObject->dataObject->reputation)){
	$reputation = round($responseObject->dataObject->reputation->reputation * 100);
	$nbOfRatings = $responseObject->dataObject->reputation->noOfRatings;
	$likes = $responseObject->dataObject->reputation->reputation * $nbOfRatings;
	$dislikes = $nbOfRatings - $likes;
}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="Detail">
		<div class="wrapper">
			<div data-role="header" data-theme="c" style="max-height: 38px;">
				<h2>
					<a href="./" style="text-decoration: none;">myEurope</a>
				</h2>
			</div>
			<div data-role="content">
				<div style='color: lightGreen; text-align: center;'>
					<?= $msg ?>
				</div>
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
							unset($value);
						} else if ($value->key=="data"){
							$preds = json_decode($value->value);
						} else if ($value->key=="rate"){
							$rate = json_decode($value->value);
						}
					}
					$detail = array_values(array_filter($detail, "isPredicate")); // to use details for delete

					
					?>
				<div style="float: right; text-align: center;">
					<img style="text-align: center; max-height: 100px; opacity: 0.6;" src="<?= $profPic ?>" /><br />
					Auteur: <a style="left-margin: 10px; color: #0060AA; font-size: 120%;" href="mailto:<?= $profile->email ?>"><?= $profile->name ?> </a> <br />
					Réputation :&nbsp;	<span style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= (1-$rate)*100 ?> </span><br />
					Voter:
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('0'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('0.25'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('0.5'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('0.75'); document.StartInteractionForm.submit();"></a>
					<a data-role="button" data-icon="star" data-iconpos="notext" data-inline="true" style="margin-right:1px; margin-left:1px;"
							onclick="$('#feedback').val('1'); document.StartInteractionForm.submit();"></a>


				</div>

				<form id="StartInteractionForm" action="#" method="post" name="StartInteractionForm" id="StartInteractionForm" enctype="multipart/form-data">
					<input name="detail" value='<?= urlencode(json_encode($detail)) ?>' type="hidden" />
					<input type="hidden" name="application" value="<?= $application ?>" />
					<input type="hidden" name="producer" value="<?= $_REQUEST['user'] ?>" />
					<input type="hidden" name="consumer" value="<?= $_SESSION['user']->id ?>" />
					<input type="hidden" name="start" value="<?= time() ?>" />
					<input type="hidden" name="end" value="<?= time() ?>" />
					<input type="hidden" name="predicate" value="<?= $id ?>" />
					<input type="hidden" name="feedback" value="" id="feedback" />
				</form>

				
				Libellé du projet: <span style="left-margin: 5px; color: #0060AA; font-size: 120%;"><?= $id ?> </span><br /><br />
				
				Description: <br /> <br />
				
				<div id="detailstext">
					<?= $text ?>
				</div>
				<br />
				
				<?php 

				if ($_GET['user'] == $_SESSION['user']->id){ //we can delete our own text
					?>
				<form action="#" method="post" id="deleteForm">
					<input name="application" value='<?= $application ?>' type="hidden" /> <input name="predicates" value='<?= urlencode(json_encode($detail)) ?>'
						type="hidden" /> <input name="id" value='<?= $id ?>' type="hidden" /> <input name="user" value='<?= $_REQUEST['user'] ?>'
						type="hidden" />
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
								<input name="application" value='<?= $application ?>' type="hidden" />
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
								<input name="application" value='<?= $application ?>' type="hidden" /> <input name="commentOn" value='<?= $_REQUEST['predicate'] ?>'
									type="hidden" /> <input name="end" value='<?= date("Y-m-d") . "T" . date("H:i:s") ?>' type="hidden" />
								<textarea name="data" id="textarea1" placeholder="" style="height: 22px;"></textarea>
								<a href="" type="button" data-inline="true" data-mini=true data-iconpos="right" data-icon="check" onclick="$('#commentForm').submit();">Commenter</a>
							</form>
						</fieldset>
					</div>
				</div>
				<div class="push"></div>
			</div>
		</div>
		<?= Template::credits(); ?>
	</div>
</body>
</html>



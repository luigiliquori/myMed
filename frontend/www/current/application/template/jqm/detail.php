<?php 
	//in case you refresh page
	require_once dirname(__FILE__).'/TemplateManager.class.php';
	$template = new TemplateManager();
	$template->getHead();
	//require_once('PhpConsole.php');
	//PhpConsole::start();
	
?>


<div data-role="page" id="Detail">
	<div data-theme="b" data-role="header" data-position="fixed">
		<h3>Détail</h3>
		<div data-role="navbar" data-iconpos="left">
			<ul>
				<li><a href="#Find" data-theme="c" data-icon="search"> Chercher </a></li>
				<li><a href="#Publish" data-theme="c" data-icon="grid"> Publier </a></li>
				<li><a href="#Subscribe" data-theme="c" data-icon="alert"> Souscrire </a></li>
			</ul>
		</div>
	</div>
	<div data-role="content">

		
		<?php
	require_once('../../../lib/dasp/request/Request.class.php');
	require_once('../../../system/config.php');
	session_start();
	
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}
	
	$responseObject = new stdClass();
	
	$predicate = $_REQUEST['predicate'];
	$user = $_REQUEST['user'];
	$index = -1;
	
	foreach( $_SESSION['results'] as $i => $value ){
		if ($value->predicate == $predicate && $value->publisherID == $user)
			$index = $i;
	}
	
	debug($index);
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $predicate);
	$request->addArgument("user", $user);
	
	$profPic = ($_SESSION['results'][$index]->publisherProfilePicture && $index>=0) ? $_SESSION['results'][$index]->publisherProfilePicture : "http://graph.facebook.com//picture?type=large";
	
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$details = $responseObject->dataObject->details;
		$text="";
		foreach( $details as $i => $value ){
			if ($value->key=="text"){
				$text = str_replace("\n", "<br />", $value->value);
				array_splice($details, $i, 1);
			}	
		}
		usort($details, "cmp"); //important, we sorted also when we published this predicates.
		
		// Todo add a profile request on the publisher to get his reputation
		
		?>
		<img src='<?= $profPic ?>' width="180" style="float:right;" />
		<b>Id de l'auteur</b>: <span style="left-margin:5px; color:DarkBlue; font-size:160%;"><?= $user ?></span>
		<div id="interaction" style="display: inline;vertical-align: bottom;float:right;padding-right: 5px;">
			<div data-role="controlgroup">
				<a href="" data-role="button" data-icon="plus" data-iconpos="notext" style="color:blue;" onclick="interaction('<?= $_SESSION['user']->id ?>', '<?= $user ?>', 1);">+1</a>
				<a href="" data-role="button" data-icon="minus" data-iconpos="notext" style="color:blue;" onclick="interaction('<?= $_SESSION['user']->id ?>', '<?= $user ?>', 0);">-1</a>
			</div>
		</div>
		<br />
		<b>Prédicat</b>: <?= $predicate ?>
		<br />
		<b>Réputation</b>: <span style="color:blue;">30</span>
		<br />
		<b>Texte</b>:
		<br /><br />
		<div id="detailstext"><?= $text ?></div>
		<br />
	<?php }
	
	//echo json_encode($responseObject);

?>
		
		<form action="" id="deleteForm">
			<input name="application" value="myTemplate" type="hidden" />
			<input name="predicates" value=<?= json_encode($details) ?> type="hidden" />
			<input name="user" value=<?= $user ?> type="hidden" />
		</form>
		<a href="" type="button" data-theme="r" data-icon="delete" onclick="_delete();" style="width:240px;float:right;">Supprimer le texte</a>
		
		<div style="height:100px;" id="spacer"></div><hr>
		<?php
		$request = new Request("FindRequestHandler", READ);
    	$request->addArgument("application", $_REQUEST['application']);
    	$request->addArgument("predicate", "commentOn" . $predicate);
    	$responsejSon = $request->send();
    	$responseObject = json_decode($responsejSon);
    	if($responseObject->status == 200) {
	    	foreach(json_decode($responseObject->data->results) as $value) { ?>
	    		<form action="" id="deleteCommentForm">
					<input name="application" value="myTemplate" type="hidden" />
					<?php 
						$commentOn = new stdClass();
						$commentOn->key = "commentOn";
						$commentOn->value = $predicate;
						$commentOn->ontologyID = 0;
						$end = new stdClass();
						$end->key = "end";
						$end->value = $value->end;
						$end->ontologyID = 0;
						$predicates=array($commentOn, $end);
					?>
					<input name="predicates" value=<?= json_encode($predicates) ?> type="hidden" />
					<input name="user" value=<?= $value->publisherID ?> type="hidden" />
				</form>
	    		<p><?= $value->data ?></p>
	    		<p><b><?= $value->publisherName ?></b> le <?= $value->end ?>
				<a href="" type="button" data-theme="r" data-iconpos="notext" style="float:right;" data-icon="delete" onclick="__delete();" style="float:right;"></a></p>
	    		<br /><br />
	    	<?php } ?>
		<?php } else { ?>
			<p>0 comments</p>
		<?php } ?>
		
		<form action="" id="commentForm" >
			<input name="application" value="myTemplate" type="hidden" />
			<input name="commentOn" value=<?= $predicate ?> type="hidden" />
			<input name="end" value="" type="hidden" />
			<input name="_data" value="" type="hidden" />
			<textarea name="data" placeholder="" value=""></textarea>
			<a href="" type="button" data-theme="g" id="commentButton" onclick="comment();" data-inline="true" style="float:right;">Commenter</a>
		</form>
		
	</div>
	
</div>



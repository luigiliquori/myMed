<?php 
	//in case you refresh page
	require_once dirname(__FILE__).'/TemplateManager.class.php';
	$template = new TemplateManager();
	$template->getHead();
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	debug('dt');
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
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}
	
	$responseObject = new stdClass();
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $_REQUEST['predicate']);
	$request->addArgument("user", $_REQUEST['user']);
	
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
		<img src='<?= $_REQUEST['profPic'] ?>' width="180" style="float:right;" />
		<b>Id de l'auteur</b>: <span style="left-margin:5px; color:DarkBlue; font-size:160%;"><?= $_REQUEST['user'] ?></span>
		<br />
		<b>Prédicat</b>: <?= $_REQUEST['predicate'] ?>
		<br />
		<b>Réputation</b>: 30 &nbsp;&nbsp; <b>Vote</b>:
		<div id="interaction" style="display: inline-block;vertical-align: bottom;">
			<div data-role="controlgroup" data-type="horizontal" >
				<a href="" data-role="button" data-icon="plus" data-mini="true" data-iconpos="notext" onclick="interaction('<?= $_SESSION['user']->id ?>', '<?= $_REQUEST['user'] ?>', 1);">+1</a>
				<a href="" data-role="button" data-icon="minus" data-mini="true" data-iconpos="notext" onclick="interaction('<?= $_SESSION['user']->id ?>', '<?= $_REQUEST['user'] ?>', 0);">-1</a>
			</div>
		</div>
		<br />
		<b>Texte</b>: 
		<div id="detailstext"><?= $text ?></div>
		<br />
	<?php }
	
	//echo json_encode($responseObject);

?>
		
		<form action="" id="deleteForm">
			<input name="application" value="myTemplate" type="hidden" />
			<input name="predicates" value=<?= json_encode($details) ?> type="hidden" />
			<input name="user" value=<?= $_REQUEST['user'] ?> type="hidden" />
		</form>
		<a href="" type="button" data-theme="r" data-icon="delete" onclick="_delete();" style="width:270px;float:right;">Supprimer</a>
	</div>
	<script language=javascript>
		details = <?php echo json_encode($details);?> //saving details for being able to delete it with the above button ^
	</script>
</div>



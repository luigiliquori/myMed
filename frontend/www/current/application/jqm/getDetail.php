
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
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	
	$request = new Request("FindRequestHandler", READ);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $_REQUEST['predicate']);
	$request->addArgument("user", $_REQUEST['user']);
	
	$responsejSon = $request->send();
	debug($responsejSon);
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
		$details = $responseObject->dataObject->details;
		$text="";
		foreach( $details as $value ){
			if ($value->key=="text")
				$text = str_replace("\n", "<br />", $value->value);
		}
		?>
		<img src='<?= $_REQUEST['profPic'] ?>' width="180" style="float:right;" />
		<b>Id de l'auteur</b>: <span style="left-margin:5px; color:DarkBlue; font-size:160%;"><?= $_REQUEST['user'] ?></span>
		<br />
		<b>Prédicat</b>: <?= $_REQUEST['predicate'] ?>
		<br /><br />
		<b>Texte</b>: 
		<div id="detailstext"><?= $text ?></div>
		<br />
	<?php }
	
	//echo json_encode($responseObject);

?>
		

		<a href="" type="button" data-theme="r" data-icon="delete" onclick="_delete(<?= $_REQUEST['index'] ?>);" style="width:270px;float:right;">Supprimer</a>
	</div>
</div>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

	<head>
		<title>myMed | myTransport</title>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" href="css/style.css" />
		
		<!-- JQUERY -->
		<link href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" rel="stylesheet" />
		<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
		
		<!-- MAP -->
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
		<script src="system/templates/application/myTransport/javascript/map.js"></script>
		<link rel="stylesheet" href="system/templates/application/myTransport/css/style.css" />
		
		<?php require_once dirname(__FILE__).'/Publish.class.php'; ?>
		<?php require_once dirname(__FILE__).'/Find.class.php'; ?>
		<?php require_once dirname(__FILE__).'/Map.class.php'; ?>
		<?php require_once dirname(__FILE__).'/Result.class.php'; ?>
		<?php require_once dirname(__FILE__).'/Details.class.php'; ?>
		<?php require_once dirname(__FILE__).'/handler/MyTransportHandler.class.php'; ?>
		<?php $handler = new MyTransportHandler(); ?>
		<?php $handler->handleRequest(); ?>
		
	</head>
	
	<body onload="initialize()">
		<?php 
			if($handler->getError() == null && $handler->getSuccess() == null) {		
				$map = new Map();
				$map->printTemplate();
				$publish = new Publish();
				$publish->printTemplate();
				$find = new Find();
				$find->printTemplate();
			} else if(isset($_GET['publish']) || isset($_GET['subscribe'])) {
				$result = new Result($handler);
				$result->printTemplate();
			} else if(isset($_GET['getDetails'])) {
				$details = new Details($handler);
				$details->printTemplate();
			}
		?>
	</body>
</html> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

	<head>
		<title>myMed | myTemplate</title>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" href="css/style.css" />
		
		<!-- JQUERY -->
		<link href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" rel="stylesheet" />
		<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
		
		<!-- MAP -->
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
		<link rel="stylesheet" href="system/templates/application/myTemplate/css/style.css" />
		
		<?php require_once dirname(__FILE__).'/PublishView.class.php'; ?>
		<?php require_once dirname(__FILE__).'/SubscribeView.class.php'; ?>
		<?php require_once dirname(__FILE__).'/FindView.class.php'; ?>
		<?php require_once dirname(__FILE__).'/handler/MyTemplateHandler.class.php'; ?>
		
		<?php $handler = new MyTemplateHandler(); ?>
		<?php $handler->handleRequest(); ?>
		
	</head>
	
	<body>
		<?php 
			$publish = new PublishView();
			$publish->printTemplate();
			
			$subscribe = new SubscribeView();
			$subscribe->printTemplate();
			
			$find = new FindView();
			$find->printTemplate();
		?>
	</body>
</html> 
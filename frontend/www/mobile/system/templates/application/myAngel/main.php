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
		
		<!-- CSS -->
		<link rel="stylesheet" href="system/templates/application/myAngel/css/style.css" />
		
		<?php require_once dirname(__FILE__).'/MyAngel.class.php'; ?>
		
	</head>
	
	<body onload="initialize()">
		<?php 
			$myAngel = new MyAngel();
			$myAngel->printTemplate();
		?>
	</body>
</html> 
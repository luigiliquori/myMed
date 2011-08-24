<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

	<head> 
		<title>myMed | Mobile UI</title> 
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		
		<link rel="stylesheet" href="css/style.css" />
		<link href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" rel="stylesheet" />
		
		<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>

		<?php require_once dirname(__FILE__).'/Favorite.class.php'; ?>
		<?php require_once dirname(__FILE__).'/Category.class.php'; ?>			
		<?php require_once dirname(__FILE__).'/Top10.class.php'; ?>
		<?php require_once dirname(__FILE__).'/Profile.class.php'; ?>
		
		<?php require_once dirname(__FILE__).'/handler/MenuHandler.class.php'; ?>
		<?php 
			$menuHandler = new MenuHandler();
			$menuHandler->handleRequest();
		?>
	</head> 

	<body>
	
		<!-- Disconnect the user -->
		<form action="#" method="get" name="disconnectForm" id="disconnectForm">
			<input type="hidden" name="disconnect" value="1" />
		</form>
	
		<!-- HOME -->
		<?php 
			$favorite = new Favorite();
			$favorite->printTemplate();
			
			$category = new Category();
			$category->printTemplate();
			
			$top10 = new Top10();
			$top10->printTemplate();
		?>
		
		<!-- PROFILE -->
		<?php 
			$profile = new Profile();
			$profile->printTemplate();
		?>
		
		<script type="text/javascript">
			function scroller(){
				window.scrollTo(0, 40);
			}
			setTimeout("scroller()", 500);
		</script>
	</body>
	
</html>
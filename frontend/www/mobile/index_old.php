<!DOCTYPE html> 
<html> 
	<head> 
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		
		<title>myMed | Mobile UI</title> 
		
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
	
		<link rel="stylesheet" href="css/style.css" />
		
		<!-- 
		<link rel="stylesheet" href="css/jquery.mobile-1.0b2.min.css" />
		<script type="text/javascript" src="javascript/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="javascript/jquery/jquery.mobile-1.0b2.min.js"></script>
		 -->
		
		<link href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" rel="stylesheet">
		<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
	</head> 

<body> 

	<!-- LOGIN -->
	<?php include('login.php') ?>
	
	<!-- INSCRIPTION -->
	<?php include('inscription.php') ?>
	
	<!-- HOME -->
	<?php include('home.php') ?>
	
	<!-- STORE -->
	<?php include('myStore.php') ?>
	
	<!-- PROFILE -->
	<?php include('myProfile.php') ?>
	
	<!-- INSCRIPTION -->
	<?php include('privacy.php') ?>
	
	<!-- APPLICATIONS -->
	<?php include('application/myTransport.php') ?>
	
	<script type="text/javascript">
		function scroller(){
			window.scrollTo(0, 40);
		}
		setTimeout("scroller()", 500);
	</script>
	
</body>
</html>
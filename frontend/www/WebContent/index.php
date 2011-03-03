<?php $debug = 0 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
      
  <head>
  	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
  	<!-- JAVASCRIPT TOOLS AND LIBRARY -->
  	<script type="text/javascript" src="javascript/jquery/dist/jquery.js"></script>
  	<script type="text/javascript" src="javascript/cookie.js"></script>
	<script type="text/javascript" src="javascript/drag.js"></script>
	<script type="text/javascript" src="javascript/display.js"></script>
	<script type="text/javascript" src="javascript/requestHTML.js"></script>
	<!-- INCLUDE GOOGLE MAP -->
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="javascript/map.js"></script>
	<!-- INCLUDE CALENDAR -->
	<link type="text/css" rel="stylesheet" href="css/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="javascript/dhtmlgoodies_calendar.js?random=20060118"></script>
	
	<!-- TITLE -->
	<title>myMed</title>
	
	<!-- STYLESHEET -->
  	<link rel="stylesheet" href="css/style.css"> 
  </head>
      
  <body onclick="cleanView();">
  
  	<!-- INCLUDE THE SOCIAL NETWORK APIs -->
  	<?php include('socialNetworkAPIs/mySocialNetwork.php'); ?>
  
  	<!-- FORMAT -->
    <div align="center">
	  	
	  	<!-- HEADER -->
		<?php include('header.php'); ?>
	  	
	  	<!-- CONTENT -->
	  	<div id="content">
	  		<?php if ($user->name) { ?>
	  		
		  		<!-- USERINFO -->
				<?php include('user.php'); ?>
				
				<!-- FIRENDS -->
				<?php include('friends.php'); ?>
				
				<!-- DESKTOP -->
				<?php include('desktop.php'); ?>
			
				<!-- SERVICES -->
				<?php include('services.php'); ?>
				
				<!-- WARNING -->
				<?php include('warning.php'); ?>
				
				<!-- GOOGLE MAP -->
				
	    	<?php } else { ?>
				
				<!-- ACCUEIL -->
				<?php include('accueil.php'); ?>
				
			<?php } ?>
	    	
    	</div>
    	
    	<!-- FOOTER -->
		<?php include('footer.php'); ?>
		
		<!-- DEBUG -->
		<div id="debug" style="display: <?= $debug ? "block" : "none"; ?>">
			<h3>Debug Console:</h3>
			<?= $_POST["login"] ?><br /><br />
			<?= $_POST["email"] ?><br /><br />
			<?= $_POST["password"] ?><br /><br />
			<?= $isAuthenticated ?><br /><br />
		</div>
    	
    </div>
  </body>
</html>

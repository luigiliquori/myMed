<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
      
  <head>
  	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
  	<!-- SHARE THIS
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher:'8bbeee4c-99fe-4920-85a2-4417288a1bde'});</script> -->
  
  	<!-- JAVASCRIPT TOOLS AND LIBRARY -->
  	<script type="text/javascript" src="javascript/cookie.js"></script>
	<script type="text/javascript" src="javascript/drag.js"></script>
	<script type="text/javascript" src="javascript/display.js"></script>
	<script type="text/javascript" src="javascript/requestHTML.js"></script>
	<script type="text/javascript" src="javascript/jquery/dist/jquery.js"></script>
	
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
	  		<?php if ($user) { ?>
	  		
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
    	
    </div>
  </body>
</html>

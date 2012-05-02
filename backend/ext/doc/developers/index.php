<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
      
  <head>
 	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
  	<title>myMed developers page</title>
  	<link rel="stylesheet" href="css/style.css">
  	<link rel="stylesheet" href="dataStructure/css/style.css"> 
  	
  	<!-- MY APPs -->
	<script type="text/javascript" src="javascript/display.js"></script>
	<script type="text/javascript" src="javascript/jquery/dist/jquery.js"></script>
  </head>
      
  <body onclick="cleanMenu();">
    <div align="center">
	  	
	  	<!-- HEADER -->
		<?php include('header.php'); ?>
	  	
	  	<!-- CONTENT -->
	  	<div id="content">
		
			<!-- HOME -->
			<?php include('home.php'); ?>
			
			<!-- MODEL -->
			<?php include('model.php'); ?>
			
			<!-- ARCHITECTURE -->
			<?php include('architecture.php'); ?>
			
			<!-- VOCABULARY -->
			<?php include('vocabulary.php'); ?>
			
			<!-- WORKFLOW -->
			<?php include('workflow.php'); ?>
			
			<!-- REST API -->
			<?php include('restAPI.php'); ?>
	    	
    	</div>
    	
    	<!-- FOOTER -->
		<?php include('footer.php'); ?>
    	
    </div>
   	
  </body>
</html>

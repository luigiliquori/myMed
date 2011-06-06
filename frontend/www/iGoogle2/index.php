<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
      
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  	<title>myMed</title>
  	<link rel="stylesheet" href="css/style.css"> 
  	<script type="text/javascript" src="javascript/drag.js"></script>
	<script type="text/javascript" src="javascript/display.js"></script>
	<script type="text/javascript" src="javascript/jquery/dist/jquery.js"></script>
  </head>
      
  <body>
    <div align="center">
    
    	<!-- INCLUDE THE SOCIAL NETWORK APIs -->
  		<?php include('socialNetworkAPIs/mySocialNetwork.php'); ?>
	  	
	  	<!-- HEADER -->
		<?php include('header.php'); ?>
	  	
	  	<!-- RECHERCHER -->
	  	<div id="rechercher">
	  		<table>
	  		  <tr>
	  		  	<td rowspan="2"><img alt="myMed" src="img/logo-mymed.jpg" height="70px" style="position: relative; top:4px;"></td>
	  		  	<td><div style="position: relative; top:17px;">
					<a href="#"><b>Tous</b></a> |
					<a href="#">Actualite</a> |
					<a href="#">Divertissement</a> |
					<a href="#">Sport</a> |
					<a href="#">Culture</a> |
					<a href="#">Plus</a>
				</div></td>
	  		  	<td></td>
	  		  	<td></td>
	  		  </tr>
			  <tr>
			    <td><input type="text" style="width: 400px; height: 20px;" /></td>
			    <td><input type="button" value="Rechercher une application" /></td>
			    <td><input type="button" value="Top 10" /></td>
			  </tr>
			</table>
    	</div>
    	
    	<!-- CONTENT -->
    	<div id="content">
    	
    		<!-- PROFILE -->
    		<?php include('profile.php'); ?>
    		
    		<!-- TabFond -->
    		<div style="position:absolute; height: 57px; width: 100%; left:0px;  top: 130px; background-image: url('img/tab/tabFondM.png');"></div>
    		
    		<!-- tab1 -->
    		<?php include('tab1.php'); ?>
    		
    		<!-- tab2 -->
    		<?php include('tab2.php'); ?>
    		
    		<!-- tab3 -->
    		<?php include('tab3.php'); ?>
    		
    		<!-- Footer -->
    		<?php include('footer.php'); ?>
    		
    	</div>
    	
    </div>
   	
  </body>
</html>

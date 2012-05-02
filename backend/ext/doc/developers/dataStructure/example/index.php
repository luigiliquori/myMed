<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
      
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  	<title>dataStructure</title>
  	<link rel="stylesheet" href="../css/style.css"> 
  	<script type="text/javascript" src="../javascript/drag.js"></script>
	<script type="text/javascript" src="../javascript/display.js"></script>
	<script type="text/javascript" src="../javascript/jquery/dist/jquery.js"></script>
  </head>
      
  <body>
  		<h2>myTransport Example</h2>
  		<a href="../index.php">Back</a>
  		<div align="center">
  			<ul>
				  <li id="li1">USER_ID_1 Create/Define the myTransport application</li>
				  <li id="li2">What is an Ontology?</li>
				  <li id="li3">USER_ID_1: Publish: Nice/Sophia on 2011-04-28</li>
				  <li id="li4">How the matching works: predicate mecanism</li>
				  <li id="li5">USER_ID_2: Publish: Nice/Antibes on 2011-04-30</li>
				  <li id="li6">predicate updated!</li>
				  <li id="li7">USER_ID_3: Subscribe to Nice/Turin</li>
			</ul>
  			<hr>
  			<br>
			<input type="button" value="<-" onclick="back();"/><input type="button" value="->" onclick="nextStep();"/>
			<br>
  			
			<!-- STEP1 -->
  			<div id="step1">
	  			<table class="container">
				  <tr>
				    <td class="containerCell"><?php include('applicationView2.php'); ?></td>
				  </tr>
				</table>
			</div>
			
			<!-- STEP2 -->
  			<div id="step2">
	  			<table class="container">
				  <tr>
				    <td class="containerCell"><?php include('applicationView2.php'); ?></td>
				    <td class="containerCell"><?php include('ontology.php'); ?></td>
				  </tr>
				</table>
			</div>
			
			<!-- STEP3 -->
  			<div id="step3">
	  			<table class="container">
				  <tr>
				    <td class="containerCell"><?php include('applicationView2.php'); ?></td>
				    <td class="containerCell"><?php include('ontology.php'); ?></td>
				  </tr>
				  <tr>
				    <td class="containerCell"><?php include('applicationModel.php'); ?></td>
				    <td class="containerCell"></td>
				  </tr>
				</table>
			</div>
			
			<!-- STEP4 -->
  			<div id="step4">
	  			<table class="container">
				  <tr>
				    <td class="containerCell"><?php include('applicationView2.php'); ?></td>
				    <td class="containerCell"><?php include('ontology.php'); ?></td>
				  </tr>
				  <tr>
				    <td class="containerCell"><?php include('applicationModel.php'); ?></td>
				    <td class="containerCell"><?php include('applicationController.php'); ?></td>
				  </tr>
				</table>
			</div>
			
			<!-- STEP5 -->
  			<div id="step5">
	  			<table class="container">
				  <tr>
				    <td class="containerCell"><?php include('applicationView2.php'); ?></td>
				    <td class="containerCell"><?php include('ontology.php'); ?></td>
				  </tr>
				  <tr>
				    <td class="containerCell"><?php include('applicationModel2.php'); ?></td>
				    <td class="containerCell"><?php include('applicationController.php'); ?></td>
				  </tr>
				</table>
			</div>
			
			<!-- STEP6 -->
  			<div id="step6">
	  			<table class="container">
				  <tr>
				    <td class="containerCell"><?php include('applicationView2.php'); ?></td>
				    <td class="containerCell"><?php include('ontology.php'); ?></td>
				  </tr>
				  <tr>
				    <td class="containerCell"><?php include('applicationModel2.php'); ?></td>
				    <td class="containerCell"><?php include('applicationController2.php'); ?></td>
				  </tr>
				</table>
			</div>
			
			<!-- STEP7 -->
  			<div id="step7">
	  			<table class="container">
				  <tr>
				    <td class="containerCell"><?php include('applicationView2.php'); ?></td>
				    <td class="containerCell"><?php include('ontology.php'); ?></td>
				  </tr>
				  <tr>
				    <td class="containerCell"><?php include('applicationModel2.php'); ?></td>
				    <td class="containerCell"><?php include('applicationController3.php'); ?></td>
				  </tr>
				</table>
			</div>
    	</div>
  </body>
  
</html>

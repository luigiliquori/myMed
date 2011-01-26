<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
      
  <head>
  	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<!-- TITLE -->
	<title>myMed</title>
	
	<!-- STYLESHEET -->
  	<link rel="stylesheet" href="css/style.css"> 
  </head>
      
  <body>
  
  	<!-- FORMAT -->
    <div align="center">
	  	
	  	<!-- HEADER -->
		<div id="header">
			<!-- MENU -->
		 	<table>
			  <tr>
			    <td><img alt="" src="img/title.png" height="30"></td>
			     <td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a></td>
			     <td><a href="http://mymed2.sophia.inria.fr:4242/">Forum</a></td>
			     <td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a></td>
			     <td><a href="http://www.mymed.fr">Blog</a></td>
			     <td style="width: 400px;"></td>
			     <!-- <td><span class="st_sharethis" displayText="ShareThis"  style="width: 200px;"></span></td>  -->
			     <?php if ($user) { ?>
				     <td>
				   		<div id="logout"><a href="index.php?logout=true" onclick="FB.logout(); Delete_Cookie('try', '', ''); Delete_Cookie('fbs', '/', '');">Déconnexion</a></div>
				     </td> 
			     <?php } ?>
			  </tr>
			</table>
			
			<!-- CONNECTION -->
			<div style="position: relative; width: 900px; text-align: left; margin-top: 10px; font-size:30px;">
				<img src="img/maintenance.png" alt="" width="50px;"/> Site en maintenance...
			</div>
		</div>
	  	
	  	<!-- CONTENT -->
	  	<div id="content">
	    	<div id="accueil"align="center" style="height: 530px;">
				<!-- LEFT PART -->
				<div style="position: absolute; top: 70px; margin-left: 50px;">
					<div class="title">myMed se joint à votre réseau social préféré <br>pour ajouter de nouvelles fonctionnalités!</div><br>
					<img alt="" src="img/NetworkView.png" width="380px;">
				</div>
				
				<!-- RIGHT PART -->
				<div style="position: absolute; top: 70px; margin-left: 500px;">
				    <table>
				    	  <tr>
				    		<td colspan="2" style="text-align: left;"><div class="title">Vous n'êtes pas encore abonnés à un réseau social?<br>myMed en fourni un pour vous!</div><br></td>
						  </tr>
						  <tr>
						    <td>Nom</td>
						    <td><input type="text" name="nom"></td>
						  </tr>
						  <tr>
						    <td>Prénom</td>
						    <td><input type="text" name="prenom"></td>
						  </tr>
						  <tr>
						    <td>eMail</td>
						    <td><input type="text" name="eMail"></td>
						  </tr>
						  <tr>
						    <td>Mot de pass</td>
						    <td><input type="password" name="eMail"></td>
						  </tr>
						  <tr>
						    <td>Confirmation</td>
						    <td><input type="password" name="eMail"></td>
						  </tr>
						  <tr>
						    <td colspan="2" style="text-align: center;"><br><br>
							    <input type="submit" value="Inscription" disabled>
						    </td>
						 </tr>
						 <tr>
						    <td colspan="2"><hr><br></td>
						 </tr>
						 <tr>
							<td colspan="2" style="text-align: center;"><img alt="" src="img/logo-mymed.jpg" width="200px;"/><br>
							<a href="http://www.mymed.fr" class="title" style="font-style: italic;">Réseau Social Transfrontalier</a>
							</td>
						 </tr>
					</table>
				</div>
			</div>
    	</div>
    	
    	<!-- FOOTER -->
		<?php include('footer.php'); ?>
    	
    </div>
  </body>
</html>

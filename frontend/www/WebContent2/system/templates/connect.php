<?php //header("Content-Type:application/xhtml+xml") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		
		<!-- TITLE -->
		<title>myMed&gt;<?php $this->getTitle();?></title>
		<?php $this->headTags();?>
		
		<!-- STYLESHEET -->
		<link rel="stylesheet" href="style/desktop/style.css" />
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="style/desktop/design.css" />
		<!-- define design of website -->
	</head>
	<body>
	  	<!-- FORMAT -->
	    <div align="center">
		  	<!-- HEADER -->
			<div id="header">
				<!-- MENU -->
				<table>
					<tr>
						<td><img alt="" src="style/img/title.png" height="30" /></td>
						<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a></td>
						<td><a href="http://mymed2.sophia.inria.fr:4242/">Forum</a></td>
						<td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a></td>
						<td><a href="http://www.mymed.fr">Blog</a></td>
						<td style="width: 400px;"></td>
						<!-- <td><span class="st_sharethis" displayText="ShareThis"  style="width: 200px;"></span></td>  -->
					</tr>
				</table>
				<div style="position: relative; width: 900px; text-align: left; margin-top: 10px; font-size:30px;">
					<?php 
					if(isset($_GET['connexion'])&&$_GET['connexion']=='mymed')
					{?>
					<!-- login box -->
					<form action="" method="post" name="loginForm">
						<input type="hidden" name="login" value="true" />
						<table id="login" style="text-align: left;">
						  <tr>
						    <td class="title" style="font-size: 11px;">
						    	eMail<br />
						   		<input type="text" name="email" />
	
						    </td>
						    <td class="title" style="font-size: 11px;">
						    	Mot de passe<br />
						    	<input type="password" name="password" /><br />
						    </td>
						    <td>
						    	<button style="background-image:url('style/img/connexion.png');vertical-align: bottom;cursor:pointer;border:0 none;height:24px;width:80px;"></button>
						    	<a href="?" style="background-image:url('style/img/annuler.png');vertical-align: bottom;cursor:pointer;border:0 none;height:24px;width:80px;display:inline-block;"></a>
	
						    </td>
						  </tr>
						</table>
					</form>
					<?php }else{?>
					<!-- Connection -->
			  		<div id="connexion" style="font-size: 15px; text-align: left;" class="title">
			  			<div>Connectez-vous avec votre compte&nbsp;: <?php $this->button();?></div>
			  			<div>Ou simplement en tant que&nbsp;: <?php $this->guestButton();?></div>
			  		</div>
			  		<?php }?>
			  	</div>
			</div>
		  	<!-- CONTENT -->
		  	<div id="content">
		  						
				<!-- ACCUEIL -->
				<div id="accueil" align="center" style="height: 530px;">
					<!-- LEFT PART -->
					<div style="position: absolute; top: 70px; margin-left: 50px;">
						<div class="title">myMed se joint à votre réseau social préféré <br />pour ajouter de nouvelles fonctionnalités&nbsp;!</div><br />
						<img alt="" src="style/img/NetworkView.png" width="380px;" />
					</div>
					<!-- RIGHT PART -->
					<div style="position: absolute; top: 70px; margin-left: 500px;">
				
						<form action="" method="post" name="inscriptionForm" id="inscriptionForm">
							<div class="title" style="text-align:left;">
								<input type="hidden" name="inscription" value="true" />
								<p style="margin:0;">Vous n'êtes pas encore abonnés à un réseau social&nbsp;?</p>
								<p style="margin:0;">myMed en fourni un pour vous&nbsp;!</p>
							</div>
							<div class="table" style="width:100%;margin-top:2em;">
								<div><label for="nom">Nom</label><input type="text" name="nom" id="nom" /></div>
								<div><label for="prenom">Prénom</label><input type="text" name="prenom" id="prenom" /></div>
								<div><label for="email">eMail</label><input type="text" name="email" id="email" /></div>
								<div><label for="password">Mot de pass</label><input type="password" name="password" id="password" /></div>
								<div><label for="confirm">Confirmation</label><input type="password" name="confirm" id="confirm" /></div>
								<div>
									<label for="gender">Je suis</label>
									<select name="gender" id="gender" style="background-color: white; border: none;">
									    <option value="Homme" default="default">Homme</option>
									    <option value="Femme">Femme</option>
									    <option value="Autre">Autre</option>
								    </select>
								</div>
							</div>
							<div style="text-align: center;margin:2em;">
								<img src="style/img/inscription.png" style="position: relative; top: 6px;" onclick="document.inscriptionForm.submit()"  onmouseover="document.body.style.cursor='pointer'" onmouseout="document.body.style.cursor='default'" />
							</div>
							<hr />
							<div>
								<img alt="" src="style/img/logo-mymed.jpg" width="200px;" /><br />
								<a href="http://www.mymed.fr" class="title" style="font-style: italic;">Réseau Social Transfrontalier</a>
							</div>
						</form>
					</div>
				</div> 	
	    	</div>
			<div id="footer">
				<div style="position:relative; text-align: left; top:10px;">
				 	<a href="#"><b>Francais</b></a> |
				 	<a href="#" style="color: gray;">English</a> | 
				 	<a href="#" style="color: gray;">Italiano</a>
			 	</div>
			 	<div style="position:relative; top:20px; height: 1px; background-color: #b0c0e1;"></div>
			 	<div style="position:relative; top:35px;">"Ensemble par-delà les frontières"</div>
			 	<!-- LOGOs  -->
			 	<table style="position:relative; top:40px;">
				  <tr>
				    <td style="padding: 5px;"><img alt="" src="style/img/logos/alcotra" style="width: 100px;" /></td>
				    <td style="padding: 5px;"><img alt="" src="style/img/logos/europe" style="width: 50px;" /></td>
				    <td style="padding: 5px;"><img alt="" src="style/img/logos/cg06" style="width: 100px;" /></td>
				 	<td style="padding: 5px;"><img alt="" src="style/img/logos/regione" style="width: 100px;" /></td>
				 	<td style="padding: 5px;"><img alt="" src="style/img/logos/PACA" style="width: 100px;" /></td>
				 	<td style="padding: 5px;"><img alt="" src="style/img/logos/pref" style="width: 70px;" /></td>
				 	<td style="padding: 5px;"><img alt="" src="style/img/logos/inria" style="width: 100px;" /></td>
				  </tr>
				</table>
			</div>
		</div>
		<?php $this->scriptTags();?>
		<?php if(defined('DEBUG')&&DEBUG){?>
		<div id="debug">
			<?php printTraces();?>
		</div>
		<?php }?>
	</body>
</html>

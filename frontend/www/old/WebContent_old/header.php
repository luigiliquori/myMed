<div id="header">

	<!-- MENU -->
 	<table>
	  <tr>
	    <td><img alt="" src="img/title.png" height="30" /></td>
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
	
	<div style="position: relative; width: 900px; text-align: left; margin-top: 10px; font-size:30px;">
		<?php if ($user->name) { ?>
		      	 myMed home page: <?= $user->name ?> 
		  <?php } else { ?>
		  		<!-- Connection -->
		  		<table id="connexion" style="font-size: 15px; text-align: left;">
				  <tr>
				    <td class="title" style="text-align: left;">Connectez-vous avec votre compte:</td>
				    <td>
				    	<img alt="" src="img/loginwith1" style="position: relative; top: 2px;" onmouseover="document.body.style.cursor='pointer'" onmouseout="document.body.style.cursor='default'" onclick="showLoginView()" />
				    </td>
				    <td>
				    	<img src="socialNetworkAPIs/twitter/lib/twitteroauth/images/lighter.png" alt="Sign in with Twitter" onclick="window.location='socialNetworkAPIs/twitter/redirect.php'" onmouseover="document.body.style.cursor='pointer'" onmouseout="document.body.style.cursor='default'" style="position: relative; top: 2px;" />
				      
				    </td>
				    <td><fb:login-button>facebook</fb:login-button></td>
				    <td><img alt="" src="img/loginwith" style="position: relative; top: 2px;" /></td>
				  </tr>
				  <tr>
				    <td class="title">Ou simplement en tant que:</td>
				    <td colspan="4">
						<img src="img/freeTour2.png" onclick="window.location='index.php?try=true'" onmouseover="document.body.style.cursor='pointer'" onmouseout="document.body.style.cursor='default'" style="position: relative; top: 2px;" />
				    </td>
				  </tr>
				</table>
				
				<!-- login box -->
				<form action="" method="post" name="loginForm">
					<input type="hidden" name="login" value="true" />
					<table id="login" style="text-align: left; display:none;">
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
					    	<img src="img/connexion" style="position: relative; top: 6px;" onclick="document.loginForm.submit()" onmouseover="document.body.style.cursor='pointer'" onmouseout="document.body.style.cursor='default'" />
					    	<img alt="" src="img/annuler" style="position: relative; top: 6px;" onmouseover="document.body.style.cursor='pointer'" onmouseout="document.body.style.cursor='default'" onclick="hideLoginView()" />
					    </td>
					  </tr>
					</table>
				</form>
		  <?php } ?>
	</div>
</div>
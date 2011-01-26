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
		<?php if ($user->name) { ?>
		      	 myMed home page: <?= $user->name ?> 
		  <?php } else { ?>
		  		<!-- Connection -->
		  		<table style="font-size: 15px; text-align: center;">
				  <tr>
				    <td colspan="2" class="title" style="text-align: left;">Connectez-vous avec votre compte:</td>
				    <td><img alt="" src="img/loginwith1" style="position: relative; top: 2px;"></td>
				    <td><fb:login-button>facebook</fb:login-button></td>
				    <td><img alt="" src="img/loginwith" style="position: relative; top: 2px;"></td>
				    <td><a id="twitter" href="http://mymed2.sophia.inria.fr/mymed_v1.0_alpha/socialNetworkAPIs/twitter/redirect.php" style="position: relative; top: 2px;">
				    	<img src="http://mymed2.sophia.inria.fr/mymed_v1.0_alpha/socialNetworkAPIs/twitter/twitteroauth/images/darker.png" alt="Sign in with Twitter"/>
				    </a></td>
				  </tr>
				  <tr>
				    <td class="title">ou simplement</td>
				    <td>
						<a href="index.php?try=true"><img src="img/freeTour.png" /></a>
				    </td>
				  </tr>
				</table>
		  <?php } ?>
	</div>
</div>
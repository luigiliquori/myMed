<div id="header">

	<!-- FACEBOOK AUTHENTICATION -->
	<?php if ($cookie) { 
		$user = json_decode(file_get_contents(
   			   'https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
	} ?>
	
	<!-- MENU -->
 	<table>
	  <tr>
	    <td><img alt="" src="img/title.png" height="30"></td>
	     <td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a></td>
	     <td><a href="http://mymed2.sophia.inria.fr:4242/">Forum</a></td>
	     <td><a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a></td>
	     <td><a href="http://www.mymed.fr">Blog</a></td>
	     <td style="width: 400px;"></td>
	     <td><span class="st_sharethis" displayText="ShareThis"  style="width: 200px;"></span></td>
	     <?php if ($user) { ?>
		     <td>
		    	<div id="logout"><a href="." onclick="FB.logout();">Déconnexion</a></div>
		     </td>
	     <?php } ?>
	  </tr>
	</table>
	
	<!-- CONNECTION -->
	<div style="position: relative; width: 900px; text-align: left; margin-top: 10px; font-size:30px;">
		<?php if ($user) { ?>
		      	 myMed home page: <?= $user->name ?> 
		  <?php } else { ?>
		  		<!-- Connection -->
		  		<table style="font-size: 15px; text-align: center;">
				  <tr>
				    <th colspan="2" style="text-align: left;">Connectez-vous avec:</th>
				  </tr>
				  <tr>
				    <td><fb:login-button></fb:login-button></td>
				    <td><img alt="" src="img/loginwith" style="position: relative; top: 5px;"></td>
				  </tr>
				</table>
		  <?php } ?>
	</div>
</div>
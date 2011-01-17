<div id="header">

	<!-- FACEBOOK AUTHENTICATION -->
	<?php if ($cookie) { 
		$user = json_decode(file_get_contents(
   			   'https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
	}  else if($_COOKIE["try"] == "1" || $_GET["try"] == 1) {
		$user = json_decode('{
		   "id": "",
		   "name": "Visiteur",
 		  "first_name": "Un",
 		  "last_name": "Known",
		   "link": "http://www.facebook.com/profile.php?id=007",
		   "hometown": {
 		     "id": "",
 		     "name": null
 		  },
 		  "gender": "something",
		   "timezone": 1,
		   "locale": "somewhere",
		   "verified": true,
		   "updated_time": "now"
		}');
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
		   		<div id="logout"><a href="." onclick="FB.logout(); Delete_Cookie('try', '', ''); Delete_Cookie('fbs', '/', '');">Déconnexion</a></div>
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
				    <td colspan="2" class="title" style="text-align: left;">Connectez-vous avec votre compte:</td>
				    <td><img alt="" src="img/loginwith1" style="position: relative; top: 2px;"></td>
				    <td><fb:login-button>facebook</fb:login-button></td>
				    <td><img alt="" src="img/loginwith" style="position: relative; top: 2px;"></td>
				    <td><img alt="" src="img/loginwith2" style="position: relative; top: 2px;"></td>
				  </tr>
				  <tr>
				    <td class="title">ou simplement</td>
				    <td>
						<form action="#" method="get">
						    <input type="hidden" name="try" value="1" />
						    <input type="submit" value="Faites un essais libre">
					    </form>
				    </td>
				  </tr>
				</table>
		  <?php } ?>
	</div>
</div>
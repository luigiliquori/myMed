<div id="accueil"align="center" style="height: 530px;">

	<!-- LEFT PART -->
	<div style="position: absolute; top: 70px; margin-left: 50px;">
		<div class="title">myMed se joint à votre réseau social préféré <br>pour ajouter de nouvelles fonctionnalités!</div><br>
		<img alt="" src="img/NetworkView.png" width="380px;">
	</div>
	
	<!-- RIGHT PART -->
	<div style="position: absolute; top: 70px; margin-left: 500px;">
		<form action="" method="post" name="inscriptionForm">
			<input type="hidden" name="inscription" value="true">
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
				    <td><input type="text" name="email"></td>
				  </tr>
				  <tr>
				    <td>Mot de pass</td>
				    <td><input type="password" name="password"></td>
				  </tr>
				  <tr>
				    <td>Confirmation</td>
				    <td><input type="password" name="confirm"></td>
				  </tr>
				  <tr>
				    <td>Je suis</td>
				    <td>
				    	<select name="gender" style="background-color: white; border: none;">
						    <option value="Homme" default>Homme</option>
						    <option value="Femme">Femme</option>
						    <option value="Autre">Autre</option>
					    </select>
					</td>
				  </tr>
				  <tr>
				    <td colspan="2" style="text-align: center;"><br>
					    <img src="img/inscription" style="position: relative; top: 6px;" onclick="document.inscriptionForm.submit()"  onmouseover="document.body.style.cursor='pointer'" onmouseout="document.body.style.cursor='default'" />
					    <br><br>
				    </td>
				  </tr>
				  <tr>
				    <td colspan="2"><hr><br></td>
				  </tr>
				  <tr>
					<td colspan="2" style="text-align: center;"><img alt="" src="img/logo-mymed.jpg" width="200px;" /><br>
					<a href="http://www.mymed.fr" class="title" style="font-style: italic;">Réseau Social Transfrontalier</a>
					</td>
				  </tr>
			</table>
		</form>
	</div>
</div>

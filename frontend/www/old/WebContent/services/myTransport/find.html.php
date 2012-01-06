	
	<!-- SEARCH -->
	<div id="myTransportContainer1" class="appContainer" >
		<div style="height: 90px;">
			<form id="searchTrip" method="get" action="">
				<input name="service" value="myTransport" type="hidden" />
				<input name="search" type="hidden" />
				<h2>Trouvez votre covoiturage</h2>
				<table>
				  <tr>
				    <th>Ville de départ :</th><th>Ville d'arrivée :</th><th>Date :</th>
				  </tr>
				  <tr>
				    <td><input id="from1" name="from" type="text" size="15" /></td>
				    <td><input id="to1" name="to" type="text" size="15" /></td>
				    <td>
				    	<input id="theDate1" type="date" value="<?php echo date('Y-m-d');?>" name="theDate" size="10" />
				    </td>
				    <th><input type="submit" value="Rechercher" /></th>
				  </tr>
				</table>
			</form>
			</div>
			
			<!-- RESULT -->
<?php if(isset($found)) : ?>
			<div style="background-color: #415b68; color: white; height:360px; width: 100%; overflow: auto;">
				<div>
<?php 	if($found):?>
					<img style="margin-left:10px;width:200px;float:left;" alt="profile picture" src="<?= $res->profilePicture ?>" />
					<div style="margin-left:220px;">
					    <h1><?= $res->name ?></h1>
					    <ul>
							<li><?= $res->gender=='F'?'Femme':'Homme' ?></li>
							<li><?= $res->birthday ?></li>
						</ul>
					</div>
<?php 	else:?>
					Introuvable
<?php 	endif?> 
				</div>
				<div style="clear:both;padding:10px;"><a href="?service=myTransport" style="color:#8080ff;text-decoration:underline;">&lt; Retour</a></div>
			</div>
<?php endif?> 
	</div>
	


	<!-- PUBLISH -->
	<div id="myTransportContainer2" class="appContainer">
		<div style="height: 90px;">
			<form id="publishTrip" method="post" action="#">
				<input name="code" type="hidden" value="publish"/>
				<h2>Enregistrez votre covoiturage</h2>
				<table>
				  <tr>
				    <th>Ville de départ :</th><th>Ville d'arrivée :</th><th>Date :</th>
				  </tr>
				  <tr>
				    <td><input id="from2" name="from" type="text" size="15" /></td>
				    <td><input id="to2" name="to" type="text" size="15" /></td>
				    <td>
				    	<input id="theDate2" name="theDate" type="date" value="<?php echo date('Y-m-d');?>" size="10" />
				    </td>
				    <th><input type="submit" value="Publiez" /></th>
				  </tr>
				</table>
			</form>
		</div>
	</div>
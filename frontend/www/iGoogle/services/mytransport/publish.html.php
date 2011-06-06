
	<!-- PUBLISH -->
	<div id="myTransportContainer2" class="appContainer">
		<div style="position: relative; width: 100%; height: 90px; background-color: #415b68; color: white;">
			<form id="publishTrip" method="post" action="">
				<input name="code" type="hidden" value="publish"/>
				<h2>Enregistrez votre covoiturage</h2>
				<div style="float:left;">
					<label style="display:block;font-weight:bold;" for="from1">Ville de départ&#160;:</label>
					<input style="display:block;" id="from2" name="from" type="text" size="15" />
				</div>
				<div style="float:left;">
					<label style="display:block;font-weight:bold;" for="to1">Ville d'arrivée&#160;:</label>
					<input style="display:block;" id="to2" name="to" type="text" size="15" />
				</div>
				<div style="float:left;">
					<label style="display:block;font-weight:bold;" for="from1">Date&#160;:</label>
					<input style="display:block;" id="theDate2" type="date" value="<?php echo date('Y-m-d');?>" name="theDate" size="10" />
				</div>
				<div style="float:left;">
					<input style="margin-top:1.3em;display:block;" type="submit" value="Publiez" />
				</div>
			</form>
		</div>
	</div>
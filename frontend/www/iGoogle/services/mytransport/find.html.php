	
	<!-- SEARCH -->
	<div id="myTransportContainer1" class="appContainer" >
		<div style="position: relative; width: 100%; background-color: #415b68; color: white;">
			<form id="searchTrip" method="get" action="">
				<input name="service" value="MyTransport" type="hidden" />
				<input name="search" type="hidden" />
				<h2>Trouvez votre covoiturage</h2>
				<div style="float:left;">
					<label style="display:block;font-weight:bold;" for="from1">Ville de départ&#160;:</label>
					<input style="display:block;" id="from1" name="from" type="text" size="15" />
				</div>
				<div style="float:left;">
					<label style="display:block;font-weight:bold;" for="to1">Ville d'arrivée&#160;:</label>
					<input style="display:block;" id="to1" name="to" type="text" size="15" />
				</div>
				<div style="float:left;">
					<label style="display:block;font-weight:bold;" for="from1">Date&#160;:</label>
					<input style="display:block;" id="theDate1" type="date" value="<?php echo date('Y-m-d');?>" name="theDate" size="10" />
				</div>
				<div style="float:left;">
					<input style="margin-top:1.3em;display:block;" type="submit" value="Rechercher" />
				</div>
			</form>
		</div>
			
			<!-- RESULT -->
			<?php if(@$search) { ?>
				<div style="background-color: #415b68; color: white; height:360px; width: 100%; overflow: auto;">
					<? $res = json_decode(file_get_contents(trim(BACKEND_URL.'ProfileRequestHandler?act=1&id='.urlencode($id)))); ?>
					<div>
						<img style="margin-left:10px;width:200px;float:left;" alt="profile picture" src="<?= $res->profile_picture ?>" />
						<div style="margin-left:220px;">
						    <h1><?= $res->name ?></h1>
						    <ul>
								<li><?= $res->gender ?></li>
								<li><?= $res->locale ?></li>
							</ul>
						</div>
					</div>
					<div style="clear:both;padding:10px;"><a href="?service=MyTransport" style="color:#8080ff;text-decoration:underline;">&lt; Retour</a></div>
				</div>
			<?php } ?> 
	</div>
	
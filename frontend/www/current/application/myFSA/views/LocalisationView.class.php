<?php
class LocalisationView {
	
	
	public /*String*/ function getDetails() { ?>
		<div id="roadMap" data-role="page" data-theme="b" >
		<!-- header -->
		<div data-role="header" data-theme="b" data-position="fixed">
			<h1>myFSA</h1>
			<a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="power" data-iconpos="notext">Deconnexion</a>
		</div>		
<!-- 			<div data-role="header" data-theme="a"> -->
<!-- 				<h1>Feuille de route</h1> -->
<!-- 				<a href="#Map" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a> -->
<!-- 			</div> -->
			<!-- end of header -->
			
		<div data-role="content">
			<div id="itineraire">
				<!-- <ul id="itineraireContent" data-role="listview" data-theme="a" ></ul>  -->
				<a id="ceparou06" style="position: absolute;bottom: -40px;right: 20px;z-index: 10;" href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="../img/logos/ceparou06.png" style="max-height:35px;max-width:100px;" /></a>
			</div>
		</div>
		
		<!-- footer -->
		<div data-role="footer" data-position="fixed" data-theme="a">
			<div data-role="navbar">
			<ul>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=main" data-transition="none" data-back="true" data-icon="home">Page d'acceuil</a></li>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=social" data-icon="star" >Social</a></li>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=ExtendedProfile" data-icon="profile" >Profil</a></li>
			</ul>
			</div>
		</div>
		<!-- endOffooter -->
		</div>
		<?php }
	
		
	public /*String*/ function getPosition() { ?>
			<div id="Map" data-role="page" data-theme="b" >
			
			
		<!-- header -->
		<div data-role="header" data-theme="b" data-position="fixed">
			<h1>myFSA</h1>
			<a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="power" data-iconpos="notext">Deconnexion</a>
			<a href="#Search" data-icon="search" data-iconpos="right" class="ui-btn-right">Rechercher</a>
		</div>		
		<!-- end of header -->
			
			<div data-role="content" style="padding: 0px;">
			
				<!-- MAP -->
				<div id="myFSAMap"></div>
				<div id="steps" data-role="controlgroup" data-type="horizontal">
					<a id="prev-step" data-role="button" data-icon="arrow-l" style="opacity:.8;">&nbsp;</a>
					<a href="#roadMap" data-role="button" style="opacity:.8;">Détails</a>
					<a id="next-step" data-role="button" data-iconpos="right" data-icon="arrow-r"  style="opacity:.8;">&nbsp;</a>
				</div>
			</div>
			
		<!-- footer -->
		<div data-role="footer" data-position="fixed" data-theme="a">
			<div data-role="navbar">
			<ul>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=main" data-transition="none" data-back="true" data-icon="home">Page d'acceuil</a></li>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=social" data-icon="star" >Social</a></li>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=ExtendedProfile" data-icon="profile" >Profil</a></li>
			</ul>
			</div>
		</div>
		<!-- endOffooter -->
			</div>
		<?php }

	public /*String*/ function getSearching() { 
		$filterList = array(
				"Banques",
				"Bibliotheque",
				"Cimetieres",
				"colleges",
				"Eglises",
				"Forts_militaires",
				"IUT",
				"Jardins",
				"Mairie",
				"Maisons_Retraites",
				"Maternelles",
				"Monuments",
				"OfficeDeTourisme",
				"Pizza_Emporter",
				"Police_municipale",
				"Ports",
				"POSTES",
				"Primaire",
				"Restaurants",
				"STADES",
				"Travail_Temporaire",
		);
		if ($handle = opendir('../img/pois')) {
			$pois = "";
			while (false !== ($file = readdir($handle))) {
				$pois .= $file . ",";
			}
			echo "<input id='poiIcon' type='hidden' value='" . $pois . "' />";
		}
		?>
			<div id="Search" data-role="page" data-theme="b" >
			
		<!-- header -->
		<div data-role="header" data-theme="b" data-position="fixed">
			<h1>myFSA</h1>
			<a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="power" data-iconpos="notext">Deconnexion</a>
		</div>
		<!-- end of header -->
		
		<div id="Itin" data-theme="b">
			<form action="" name="<?= APPLICATION_NAME ?>FindForm"
				id="<?= APPLICATION_NAME ?>FindForm">
				<input type="hidden" name="application"	value="<?= APPLICATION_NAME ?>" /> 
				<input type="hidden" name="method" value="find" /> 
				<input type="hidden" name="numberOfOntology" value="4" />
		
				<!-- FROM -->
				Départ :
				<input data-theme="b" type="text" id="depart" name="Depart" />
				<br />
				
				<!-- TO -->
				Arrivée :
				<input data-theme="b" type="text" id="arrivee" name="Arrivee" />
				<!-- FRIENDS -->
				<?php if(isset($_SESSION['friends'])) {
					$knownFriends =  array(); 
					foreach ($_SESSION['friends'] as $friend ) {
						if (isset($friend["position"])) {
							array_push($knownFriends, $friend); 
						}
					}?>
					<?php if(count($knownFriends) > 0) { ?>
						<select id="selectarrivee" data-iconpos="notext" data-icon="plus"
						name="enum-1" onChange="changeEndMarkerIcon(); changeDestination()">
						<option></option>
						<?php foreach ($knownFriends as $friend ) { ?>
								<option value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
									<?= $friend["name"] ?>
								</option>
						<?php } ?>
						</select>
					<?php } ?>
				<?php } ?>
				
				<!-- DATE -->
				<br />
				<div id="date">
					<?php
					$now = getdate();
					$months = array('janvier', 'février', 'mars', 'avril','mai',
									'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
					?>
					le : 
					<fieldset data-role="controlgroup" data-type="horizontal"
						style="position: relative; top:0px; display: inline; margin: 5px;">
		
						<select name="select-day" id="select-day">
						<?php for ($i = 1; $i <= 31; $i++) {?>
							<option value=<?=$i?>   
							<?php if ($i==$now['mday']){?> selected="selected" 
							<?php } ?>>
								&nbsp;<?= $i?>&nbsp;
							</option>
						<?php } ?>
						</select> 
						<select name="select-month" id="select-month">
						<?php for ($i = 0; $i <= 11; $i++) {?>
							<option value=<?=$i+1?>
							<?php if ($i+1==$now['mon']){?> selected="selected"
							<?php } ?>>
								  &nbsp;<?=$months[$i]?>&nbsp;
							</option>
						<?php } ?>
						</select>
						<select name="select-year" id="select-year">
						<?php for ($i = 2012; $i <= 2016; $i++) {?>
							<option value=<?=$i?>  
							<?php if ($i==$now['year']){?> selected="selected"
							<?php } ?>>
								&nbsp;<?=$i?>&nbsp;
							</option>
						<?php } ?>
						</select>
					</fieldset>
					à : 
					<fieldset data-role="controlgroup" data-type="horizontal"
						style="position: relative; top:0px; display: inline; margin: 5px;">
						<select name="select-hour" id="select-hour">
						<?php for ($i = 0; $i <= 23; $i++) {?>
							<option value=<?=$i?>   <?php if ($i==$now['hours']){?> selected="selected"
							<?php } ?>>
								&nbsp;<?=sprintf('%02d',$i)?>h&nbsp;
							</option>
						<?php } ?>
						</select>
						<select name="select-minute" id="select-minute">
								<?php for ($i = 0; $i <= 59; $i++) {?>
							<option value=<?=$i?>   <?php if ($i==$now['minutes']){?> selected="selected"
							<?php } ?>>
								&nbsp;<?=sprintf('%02d',$i)?>&nbsp;
							</option>
						<?php } ?>
						</select>
					</fieldset>
				</div>
		
				<br />
				
				<!-- Option Avancée -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c">
					<h3>Options Avancées</h3>
					
					<h3>Rayon de recherche</h3>
					<input type="range" name="slider-radius" id="slider-radius" value="<?= TARGET == "mobile" ? "5" : "10" ?>00" min="100"
					max="5000" data-highlight="true" /> <span style="display: inline;">mètres</span>
					
					<h3>Points d'interêts</h3>
					<div id="<?= APPLICATION_NAME ?>Filter" class="ui-grid-a">
						<?php $i=0; ?>
						<?php foreach ($filterList as $filter) { ?>
							<?php if($i%2==0) { ?>
								<div class="ui-block-a">
							<?php }  else { ?>
								<div class="ui-block-b">
							<?php } ?>
								<input type="checkbox" name="<?= $filter ?>" id="<?= $filter ?>" class="custom" checked="checked"/> 
								<label for="<?= $filter ?>" style="font-size: 9pt;"><?= $filter ?></label>
							</div>
							<?php $i++; ?>
						<?php } ?>
					</div>
					
					<h3>Persistence des points d'intérêts</h3>
					<fieldset id="flip-persistence" data-role="controlgroup" style="width:200px;">	
						<select name="flip-per" id="flip-per" data-role="slider" onchange="if(!$(this).val()) {clearMarkers();}">
							<option value=''>non</option>
							<option value='1'>oui</option>
						</select>
					</fieldset>
					
					<h3>Type de Trajet</h3>
					<div  id="cityway-search">
						<fieldset data-role="controlgroup" >
							<input type="radio" name="radio-choice" id="radio-choice1" value="fastest" checked="checked" />
							<label for="radio-choice1">le plus rapide</label>
							<input type="radio" name="radio-choice" id="radio-choice2" value="lessChanges" />
							<label for="radio-choice2">le moins de changement</label>
						</fieldset>
						<fieldset data-role="controlgroup">
							<input type="checkbox" name="checkbox" id="checkbox0"	checked="checked" /><label for="checkbox0">Bus</label>
							<input type="checkbox" name="checkbox" id="checkbox2" checked="checked" /><label for="checkbox2">Car</label>
							<input type="checkbox" name="checkbox" id="checkbox3" checked="checked" /><label for="checkbox3">Train</label>
							<input type="checkbox" name="checkbox" id="checkbox4"	checked="checked" /><label for="checkbox4">Tram</label>
							<input type="checkbox" name="checkbox" id="checkbox5" checked="checked" /><label for="checkbox5">Ter</label>
							<input type="hidden" name="checkbox" id="checkbox17" checked="checked" /><label style="display:none;" for="checkbox17">Nav_élec</label>
							<input type="hidden" name="checkbox" id="checkbox19" checked="checked" /><label	style="display:none;" for="checkbox19">Tgv</label>
						</fieldset>
					</div>
					
				</div>
		
				<!-- SUBMIT - ToDO validate before submit-->
				
					<a href="#Map" id="trouver" data-role="button" rel="external"
						data-icon="search" data-theme="b"
						onclick="setTimeout(validateIt, 500);" style="margin:auto;width:150px;">Rechercher</a>
				
				
	
				 <a href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="../img/logos/ceparou06.png" style="max-height:35px;max-width:100px;float: right;margin-top: -40px;" /></a>
				
				
		
			</form>
		</div>
					<!-- footer -->
	<div data-role="footer" data-position="fixed" data-theme="b">
		<div data-role="navbar">
			<ul>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=main" data-transition="none" data-back="true" data-icon="home">Page d'acceuil</a></li>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=social" data-icon="star" >Social</a></li>
				<li><a href="http://mymed21.sophia.inria.fr/application/myFSA/index.php?action=ExtendedProfile" data-icon="profile" >Profil</a></li>
			</ul>
		</div>
	</div>
		<!-- endOffooter -->
		</div>

	<?php }
	
}
?>
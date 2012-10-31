
<div id="search" data-role="page">

<? include("header-bar.php"); ?>

	<div id="Itin" data-theme="c">
		<form action="" name="<?= APPLICATION_NAME ?>FindForm"
			id="<?= APPLICATION_NAME ?>FindForm">
			<input type="hidden" name="application"
				value="<?= APPLICATION_NAME ?>" /> <input type="hidden"
				name="method" value="find" /> <input type="hidden"
				name="numberOfOntology" value="4" />

			<!-- FROM -->
			<div data-role="fieldcontain">
				<label for="depart" ><?= _("Départ") ?>: </label>
				<input data-theme="d" type="text" id="depart" name="Depart" />
			</div>
			<div data-role="fieldcontain">
				<label for="arrivee" ><?= _("Arrivée") ?>: </label>
				<input data-theme="d" type="text" id="arrivee" name="Arrivee" />
			</div>

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
	
			<br />
			<!-- SUBMIT - ToDO validate before submit-->
			<a href="#Map" id="trouver" data-role="button" rel="external"
				data-icon="search" data-theme="b"
				onclick="setTimeout(validateIt, 500);" style="margin:auto;width:150px;">Rechercher</a>
			<a href="http://www.ceparou06.fr/" title="ceparou 06"><img alt="ceparou 06" src="<?= MYMED_URL_ROOT ?>system/img/logos/ceparou06.png" style="width:80px;float: right;margin-top: -40px;" /></a>
			
			<br />
			<!-- Option Avancée -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d" data-content-theme="c">
				<h3>Options Avancées</h3>
				
				<h3>Date de départ</h3>
				<input id="date" type="date" min="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d', time()+3600*24*365); ?>" value="<?= date('Y-m-d'); ?>" data-mini="true" style="width: 130px;display: inline-block;"/>
				<span style="padding-left: 10px;">à:</span>
				<input id="time" type="time" value="<?= date('H:i'); ?>" data-mini="true" style="width: 130px;display: inline-block;"/>
				
				<h3>Rayon de recherche</h3>
				<input type="range" name="slider-radius" id="slider-radius" value="500" min="100"
				max="5000" data-highlight="true" /> <span style="display: inline;">mètres</span>
				
				<h3>Points d'interêts</h3>
				<div id="<?= APPLICATION_NAME ?>Filter" class="ui-grid-a">
					<?php $i=0; ?>
					<?php foreach ($this->filterList as $key => $value) { ?>
						<?php if($i%2==0) { ?>
							<div class="ui-block-a">
						<?php }  else { ?>
							<div class="ui-block-b">
						<?php } ?>
							<?php $filters = "" ?>
							<?php foreach ($value as $filter) { ?>
								<?php $filters .= $filter . "," ?>
							<?php } ?>
							<?php $trimKey = str_replace(' ', '', $key); ?>
							<input type="hidden" id="<?= $trimKey . "Filters" ?>" value="<?= $filters ?>" />
							<input type="checkbox" name="<?= $trimKey ?>" id="<?= $trimKey ?>" class="custom" checked="checked"/> 
							<label for="<?= $trimKey ?>" style="font-size: 9pt;"><?= $key ?></label>
						</div>
						<?php $i++; ?>
					<?php } ?>
				</div>
				
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
			
		</form>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="#Map" data-transition="none" data-back="true" data-icon="home">Carte</a></li>
				<li><a href="#search" data-transition="none" data-icon="search" class="ui-btn-active ui-state-persist">Rechercher</a></li>
				<li><a href="#option" data-transition="none" data-icon="gear">Option</a></li>
			</ul>
		</div>
	</div>

</div>

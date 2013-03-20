<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->

<div id="search" data-role="page">

	<!-- Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="?action=main" data-icon="arrow-l" data-ajax="false"/><?= _("Back")?></a>
		<h1><?php echo APPLICATION_NAME." v1.0 alpha"?></h1>
	</div>
	
	<div id="Itin" style="margin-top: 50px;">
		<form name="<?= APPLICATION_NAME ?>FindForm"
			id="<?= APPLICATION_NAME ?>FindForm">

			<!-- FROM -->
			<div data-role="fieldcontain">
				<label for="depart" ><?= _("Départ") ?>: </label>
				<input data-theme="d" id="depart">
			</div>
			<div data-role="fieldcontain">
				<label for="arrivee" ><?= _("Arrivée") ?>: </label>
				<input data-theme="d" id="arrivee">
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
					onChange="changeEndMarkerIcon(); changeDestination()">
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
			<div style="text-align: center;">
				<a href="#Map" id="trouver" type="submit" onclick="validateIt();" data-inline="true" data-theme="e" data-role="button" data-icon="search">Rechercher</a>
			</div>
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
				<input type="range" id="slider-radius" value="500" min="100"
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
							<input type="checkbox" id="<?= $trimKey ?>" class="custom" checked="checked"/> 
							<label for="<?= $trimKey ?>" style="font-size: 9pt;"><?= $key ?></label>
						</div>
						<?php $i++; ?>
					<?php } ?>
				</div>
				
				<h3>Type de Trajet</h3>
				<div  id="cityway-search">
					<fieldset data-role="controlgroup" >
						<input type="radio" id="radio-choice1" name="radio-choice" value="fastest" checked="checked" />
						<label for="radio-choice1">le plus rapide</label>
						<input type="radio" id="radio-choice2" name="radio-choice" value="lessChanges" />
						<label for="radio-choice2">le moins de changement</label>
					</fieldset>
					<fieldset data-role="controlgroup">
						<input type="checkbox" id="checkbox0" checked="checked" /><label for="checkbox0">Bus</label>
						<input type="checkbox" id="checkbox2" checked="checked" /><label for="checkbox2">Car</label>
						<input type="checkbox" id="checkbox3" checked="checked" /><label for="checkbox3">Train</label>
						<input type="checkbox" id="checkbox4" checked="checked" /><label for="checkbox4">Tram</label>
						<input type="checkbox" id="checkbox5" checked="checked" /><label for="checkbox5">Ter</label>
						<input type="hidden" id="checkbox17" checked="checked" /><label style="display:none;" for="checkbox17">Nav_élec</label>
						<input type="hidden" id="checkbox19" checked="checked" /><label	style="display:none;" for="checkbox19">Tgv</label>
					</fieldset>
				</div>
				
			</div>
			
		</form>
	</div>
	
</div>
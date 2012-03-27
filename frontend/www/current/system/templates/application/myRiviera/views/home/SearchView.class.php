<?php

require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class SearchView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String[]*/ $filterList;
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Default constructor
	*/
	public function __construct() {
		parent::__construct("Search");
		$this->filterList = array(
				"ADAPEI",
				"ADERF",
				"ASSEDIC",
				"Bibliotheque",
				"CCAS",
				"EPCI",
				"EqptPublic",
				"Mairie", 
				"Eglises",
				"Forts_militaires",
				"Fourriere",
				"FoyerRural",
				"GARES_SUD",
				"Jardins",
				"Maisons_Retraites",
				"Monuments",
				"OfficeDeTourisme",
				"Police_municipale",
				"Ports",
				"POSTES",
				"STADES"
		);
	}
	
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="b">
			<h1>Itinéraire</h1>
			<a href="#Map" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<div id="Itin" data-theme="c">
			<form action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm"
				id="<?= APPLICATION_NAME ?>FindForm">
				<input type="hidden" name="application"	value="<?= APPLICATION_NAME ?>" /> 
				<input type="hidden" name="method" value="find" /> 
				<input type="hidden" name="numberOfOntology" value="4" />
		
				<!-- FROM -->
				Départ :
				<input data-theme="d" type="text" id="depart" name="Depart"/>
				<br />
				
				<!-- TO -->
				Arrivée :
				<input data-theme="d" type="text" id="arrivee" name="Arrivee" />
				
				<!-- DATE -->
				<div id="date">
					<?php
					$now = getdate();
					$months = array('janvier', 'février', 'mars', 'avril','mai',
									'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
					?>
					le : 
					<fieldset data-role="controlgroup" data-type="horizontal"
						style="position: relative; top:10px; display: inline; margin: 5px;">
		
						<select name="select-day" id="select-day">
						<?php for ($i = 1; $i <= 31; $i++) {?>
							<option value=<?=$i?>   
							<?php if ($i==$now['mday']){?> selected="selected" 
							<?php } ?>>
								&nbsp;<?= $i?>
							</option>
						<?php } ?>
						</select> 
						<select name="select-month" id="select-month">
						<?php for ($i = 0; $i <= 11; $i++) {?>
							<option value=<?=$i+1?>
							<?php if ($i+1==$now['mon']){?> selected="selected"
							<?php } ?>>
								  <?=$months[$i]?>
							</option>
						<?php } ?>
						</select>
						<select name="select-year" id="select-year">
						<?php for ($i = 2012; $i <= 2016; $i++) {?>
							<option value=<?=$i?>  
							<?php if ($i==$now['year']){?> selected="selected"
							<?php } ?>>
								<?=$i?>
							</option>
						<?php } ?>
						</select>
					</fieldset>
					à : 
					<fieldset data-role="controlgroup" data-type="horizontal"
						style="position: relative; top:10px; display: inline; margin: 5px;">
						<select name="select-hour" id="select-hour">
						<?php for ($i = 0; $i <= 23; $i++) {?>
							<option value=<?=$i?>   <?php if ($i==$now['hours']){?> selected="selected"
							<?php } ?>>
								<?=sprintf('%02d',$i)?>h
							</option>
						<?php } ?>
						</select>
						<select name="select-minute" id="select-minute">
								<?php for ($i = 0; $i <= 59; $i++) {?>
							<option value=<?=$i?>   <?php if ($i==$now['minutes']){?> selected="selected"
							<?php } ?>>
								<?=sprintf('%02d',$i)?>
							</option>
						<?php } ?>
						</select>
					</fieldset>
				</div>
		
				<br />
				
				<!-- Option Avancée -->
				<div data-role="collapsible" data-collapsed="true" data-theme="d" data-content-theme="c">
					<h3>Options Avancées</h3>
					
					<h3>Points d'interêts</h3>
					<div id="<?= APPLICATION_NAME ?>Filter" class="ui-grid-a">
						<?php $i=0; ?>
						<?php foreach ($this->filterList as $filter) { ?>
							<?php if($i%2==0) { ?>
								<div class="ui-block-a">
							<?php }  else { ?>
								<div class="ui-block-b">
							<?php } ?>
								<input type="checkbox" name="<?= $filter ?>" id="<?= $filter ?>" class="custom" checked="checked" /> 
								<label for="<?= $filter ?>"><?= $filter ?></label>
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
					
					<h3>Rayon de recherche</h3>
					<input type="range" name="slider-radius" id="slider-radius" value="<?= TARGET == "mobile" ? "3" : "5" ?>00" min="100"
					max="1000" data-theme="b" /> <span style="display: inline;">mètres</span>
					
					<h3>Type de Trajet Cityway</h3>
					<div  id="cityway-search">
						<fieldset data-role="controlgroup" >
							<input type="radio" name="radio-choice" id="radio-choice1" value="fastest" checked="checked" />
							<label for="radio-choice1">le	+ rapide</label>
							<input type="radio" name="radio-choice" id="radio-choice2" value="lessChanges" />
							<label for="radio-choice2">le - de chgt</label>
						</fieldset>
						<fieldset data-role="controlgroup">
							<input type="checkbox" name="checkbox" id="checkbox0"	checked="checked" /><label for="checkbox0">Bus</label>
							<input type="checkbox" name="checkbox" id="checkbox2" checked="checked" /><label for="checkbox2">Car</label>
							<input type="checkbox" name="checkbox" id="checkbox3" checked="checked" /><label for="checkbox3">Train</label>
							<input type="checkbox" name="checkbox" id="checkbox4"	checked="checked" /><label for="checkbox4">Tram</label>
							<input type="checkbox" name="checkbox" id="checkbox5" checked="checked" /><label for="checkbox5">Ter</label>
							<input type="checkbox" name="checkbox" id="checkbox17" checked="checked" /><label for="checkbox17">Nav_élec</label>
							<input type="checkbox" name="checkbox" id="checkbox19" checked="checked" /><label	for="checkbox19">Tgv</label>
						</fieldset>
					</div>
					
				</div>
		
				<!-- SUBMIT - ToDO validate before submit-->
				<center>
					<a href="#Map" id="trouver" data-role="button" rel="external"
						data-icon="search" data-theme="b" data-inline="true"
						onclick="setTimeout(validateIt, 500);">Rechercher</a>
				</center>
		
			</form>
		</div>

	<?php }
	
}
?>
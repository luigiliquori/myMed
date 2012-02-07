<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class EditDialog extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-theme="b" data-role="header">
			<h1>Edit</h1>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<div data-theme="b">
			<div id="Itin">
				<form action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm">

					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="find" />
					<input type="hidden" name="numberOfOntology" value="4" />
					<input type="hidden" name="mapos" id="mapos" value="" />
		
		
					<div data-role="fieldcontain">
						<!-- FROM -->
		
		
						<div>
		
							<input data-theme="d" type="text" id="depart" name="Départ"
								placeholder="Ma position" onclick="$(this).select();" />
						</div>
		
						<!-- TO -->
		
						<div id="divarrivee">
							<input data-theme="d" type="text" id="arrivee" name="Arrivée"
								placeholder="Ma destination" onclick="$(this).select();" /> 
								
							<select	id="selectarrivee" data-iconpos="notext" name="enum"
								onclick=" changeDestination('arrivee')">
		
								<!-- USER -->	
								<?php if (isset($_SESSION['position'])) {?>
									<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
								<?php } ?>
								
								<option value="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png&&antibes">Antibes </option>
			
								<!-- FRIENDS -->
								<?php
								if(isset($_SESSION['friends'])) {
									foreach ($_SESSION['friends'] as $friend ) { ?>
										<?php if ($friend["position"]->formattedAddress != "") {?>
												<option
													value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
													<?= $friend["name"] ?>
												</option>
										<?php }
									}
								} ?>
		        	</select>
		
						</div>
		
		
		
		
						<!-- DATE -->
		
						<div data-role="fieldcontain">
							<fieldset id="dates" data-role="controlgroup"
								data-type="horizontal">
		
		
								<select name="select-choice-day" id="select-choice-day">
									<option>Jour</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7" style="display: none">7</option>
								</select> <select name="select-choice-month"
									id="select-choice-month">
									<option>Mois</option>
									<option value="jan">Janvier</option>
									<option value="fev">Février</option>
									<option value="mar">Mars</option>
									<option value="apr">Avril</option>
									<option value="may">Mai</option>
									<option value="jun">Juin</option>
									<option value="jul">Juillet</option>
									<option value="aug">Août</option>
									<option value="sep">September</option>
									<option value="oct">Octobre</option>
									<option value="nov">Novembre</option>
									<option value="dec">Decembre</option>
								</select> <select name="select-choice-year"
									id="select-choice-year">
									<option>Year</option>
									<option value="2014">2014</option>
									<option value="2013">2013</option>
									<option value="2012">2012</option>
									<option value="2011">2011</option>
									<option value="2010">2010</option>
									<option value="2009">2009</option>
									<option value="2008">2008</option>
									<option value="2007">2007</option>
									<option value="2006">2006</option>
									<option value="2005">2005</option>
									<option value="2004">2004</option>
								</select>
							</fieldset>
		
							<fieldset id="times" data-role="controlgroup"
								data-type="horizontal">
								<select name="select-choice-hour" id="select-choice-hour">
									<option>Heure</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7" style="display: none">7</option>
								</select> <select name="select-choice-minute"
									id="select-choice-minute">
									<option>Min</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7" style="display: none">7</option>
								</select>
							</fieldset>
						</div>
		
		
						<input name="date" type="date" data-role="datebox" id="date"
							data-options='{"useInline": true, "useInlineHideInput":true, "noSetButton":true, "mode": "flipbox", "dateFormat":"GG:ii DD/MM/YYYY", "fieldsOrderOverride":["y","m","d","h","i"] }' />
						<!--
			 					 <input data-theme="d" name="date" id="date" type="date" data-role="datebox"
										data-options='{ "disableManualInput": true, "mode": "slidebox", "dateFormat":"GG:ii dd/mm/YYYY", "fieldsOrderOverride":["h","i","d","m","y"]}'>
			 					 -->
		
		
		
		
					</div>
		
					<!-- SUBMIT -->
					<a href="#" data-role="button"  rel="external" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()">Trouver</a>	
				</form>
				
			</div>
			
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="Edit" data-role="page">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
}
?>

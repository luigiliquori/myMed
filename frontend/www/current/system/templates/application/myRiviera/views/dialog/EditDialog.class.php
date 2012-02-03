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
					
					
																
					<div data-role="fieldcontain">
						<!-- FROM -->
			
						
						<div>
							
							<input data-theme="d" type="text" id="depart" name="Départ"
								value="Ma position" onclick="$('#depart').select();" 
								onchange="$('#depart').css('color', $('.ui-body-d').css('color'));" 
								onblur="if ($('#depart').val()=='') {$('#depart').css('color','LightBlue');$('#depart').val('Ma position')}"/>
						</div>
			
					 	 <!-- TO -->
					  	
					  <div>
						  <input data-theme="d" type="text" id="arrivee" name="Arrivée"
							  value="Ma destination" onclick="$('#arrivee').select();"
							  onchange="$('#arrivee').css('color', $('.ui-body-d').css('color')); $('#arrivee').css('background-image', '');"/>
								
								<select id="selectarrivee" data-iconpos="notext" name="enum" onclick=" changeDestination('arrivee')">
					
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
	 					
		   				<input data-theme="d" name="date" id="date" type="date" data-role="datebox"
								data-options='{ "disableManualInput": true, "mode": "slidebox", "dateFormat":"GG:ii dd/mm/YYYY", "fieldsOrderOverride":["h","i","d","m","y"]}'>
					 
					
					</div>
					
					<!-- SUBMIT -->
					<a href="#" data-role="button"  rel="external" onclick=" if ($('#depart').val()=='Ma position'){
						console.log('put lat lon instead'); 
						if (myMedPos != ''){
							$('#depart').val(myMedPos.coords.latitude+'&'+myMedPos.coords.longitude);
						}
					};
					 document.<?= APPLICATION_NAME ?>FindForm.submit()">Trouver</a>	
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

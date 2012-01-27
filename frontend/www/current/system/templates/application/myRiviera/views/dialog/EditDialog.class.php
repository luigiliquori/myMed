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
		<div data-role="header" data-theme="b">
			<h1>Edit</h1>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div data-role="content">
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm1" id="<?= APPLICATION_NAME ?>FindForm1">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="4" />
				
					
				<!-- FROM -->
				<div>
					<img Style="position: absolute; left: 4%; width:7%; height:7%;" id="departpicture" alt="thumbnail" src="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png" /><br />
					<div Style="position: relative; left: 15%; width:80%; text-align: left;"  >Commune, Adresse, Lieu public, Arrêt :</div>
					<input type="text" id="depart"  name="Départ" Style="position: relative; left: 15%; width:80%;"  >	
					<select id="selectdepart" name="enum" onchange="changeDestination('depart')">
                        <!-- DEFAULT -->                      
						<option value="nullpart&&007">Lieux Disponibles</option>
	
						<!-- USER -->
						<?php if (isset($_SESSION['position'])) {?>
							<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
						<?php } ?>
	
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

				<!-- SEPARATOR -->
				<div >
					<br /><br />
				</div>

				<!-- TO -->
				<div>
					<img Style="position: absolute; left: 4%; width:7%; height:7%;" id="arriveepicture" alt="thumbnail" src="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png" width="80" height="80" style="" /><br /> 
					<div Style="position: relative; left: 15%; width:80%; text-align: left;"  >Commune, Adresse, Lieu public, Arrêt :</div>
					<input type="text" id="arrivee"  name="Arrivée" Style="position: relative; left: 15%; width:80%;"  >	
					<select id="selectarrivee" name="enum" onchange="changeDestination('arrivee')">

                        <!-- DEFAULT -->                      
						<option value="nullpart&&007">Lieux Disponibles</option>
	
						<!-- USER -->
						<?php if (isset($_SESSION['position'])) {?>
							<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
						<?php } ?>
	
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
				
				<!-- SEPARATOR -->
				<div >
					<br /><br />
				</div>
				
				<!-- DATE -->
				<div data-role="fieldcontain">
					<label for="date" >Partir à :</label>
					<input name="date" id="date" type="date" data-role="datebox" data-options='{"noButtonFocusMode": true, "disableManualInput": true, "mode": "slidebox", "dateFormat":"HH:ii le DD/MM/YYYY  ", "fieldsOrderOverride":["h","i","d","m","y"]}'>
				</div>
				<!-- SEPARATOR -->
				<div >
					<br /><br />
				</div>
				
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm1.submit()">Calculer l'itinéraire</a>	
			</form>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="Edit" data-role="page" data-theme="b">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
}
?>

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
		<div data-theme="b" data-role="content">
			<div style="text-align: left;">
				<form action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="find" />
					<input type="hidden" name="numberOfOntology" value="4" />
																
					<!-- FROM -->
					<img id="departpicture" alt="thumbnail" src="system/templates/application/myRiviera/img/flag_green.png" height="32"/>
					<input data-theme="d" type="text" id="depart"  name="Départ" value="Ma position" Style="color: #5e87b0;" onclick="$('#depart').css('color', 'black'); $('#depart').val('');" />
												
					<!-- TO -->
					<img id="arriveepicture" alt="thumbnail" src="system/templates/application/myRiviera/img/flag_finish.png"  height="32"/>
					<input data-theme="d" type="text" id="arrivee"  name="Arrivée" />	
												
					<!-- DATE -->
					<img id="arriveepicture" alt="thumbnail" src="system/templates/application/myRiviera/img/calendar.png"  height="32"/><br />
					<input data-theme="d" name="date" id="date" type="date" data-role="datebox"  data-options='{"noButtonFocusMode": true, "disableManualInput": true, "mode": "slidebox", "dateFormat":"HH:ii le DD/MM/YYYY  ", "fieldsOrderOverride":["h","i","d","m","y"]}'>
					
					<br /><br />
					
					<!-- SUBMIT -->
					<a href="#" data-role="button"  rel="external" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()">Find</a>	
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

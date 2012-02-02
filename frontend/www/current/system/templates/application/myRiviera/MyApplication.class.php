<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class MyApplication extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $activeFooter;
	private /*MyTransportHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*String*/ $id) {
		parent::__construct($id, APPLICATION_NAME);
		$this->activeFooter = $id;
		
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
			<!-- EDIT -->
			<div data-role="collapsible" data-theme="b" data-content-theme="c" style="position: relative; left:20px;">
				<h1>Edit</h1>
				<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm1" id="<?= APPLICATION_NAME ?>FindForm1">
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
					
					<img id="arriveepicture" alt="thumbnail" src="system/templates/application/myRiviera/img/calendar.png"  height="32" style="display: inline; vertical-align: middle;"/>
					<label for="date" style="display: inline; vertical-align: middle;">&nbsp; à</label>
					<input data-theme="d" name="date" id="date" type="date" data-role="datebox"  data-options='{"noButtonFocusMode": true, "disableManualInput": true, "mode": "slidebox", "dateFormat":"HH:ii le DD/MM/YYYY  ", "fieldsOrderOverride":["h","i","d","m","y"]}' style="display: inline; vertical-align: middle;">
					
					<br>
					
					<!-- 
					<fieldset data-role="controlgroup">
						<input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" />
						<label for="checkbox-1">Lieux Public</label>
				    </fieldset>
				     -->
				</form>
			</div>
			
			<a href="#Edit" data-rel="dialog" class="ui-btn-right">Départ</a>
		</div>
	<?php }
	

	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER_PERSITENT-->
		<div data-role="footer" data-position="fixed" data-theme="b">
			<div data-role="navbar">
				<ul>
					<li><a href="#Find" data-back="true" <?= $this->activeFooter == "Find" ? 'class="ui-btn-active ui-state-persist"' : ''; ?> >Carte</a></li>
					<li><a href="#Trip" <?= $this->activeFooter == "Trip" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Itineraire</a></li>
				</ul>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="d">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
		</div>
	<?php }
}
?>

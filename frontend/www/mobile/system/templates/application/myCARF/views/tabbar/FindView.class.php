<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView extends MyApplication {
	
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
		parent::__construct("Find");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="4" />
				
				Départ<br />
				Commune, Adresse, Lieu public, Arrêt :<br />
				<input type="text" name="Départ" value=""  data-inline="true"/>
				<br />
				
				<!-- GPS -->
				Arrivée<br />
				Commune, Adresse, Lieu public, Arrêt :<br />
				<input type="text" name="Arrivée" value=""  data-inline="true"/>
				<br /><br />
				
				<!-- DATE -->
				Partir le :<br />
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' data-theme="a"/>
				
				<br /><br />
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()">Find</a>	
			</form>
		</div>
	<?php }
	
}
?>
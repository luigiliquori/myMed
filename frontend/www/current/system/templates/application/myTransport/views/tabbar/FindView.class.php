<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

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
		parent::__construct("Rechercher");
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
				<input type="hidden" name="numberOfOntology" value="3" />
				
				<!-- DATE -->
				Date :<br />
				<input type="date" name="Date" data-role="datebox" data-options='{"mode": "calbox"}' data-theme="c"/>
				<?php $date = new MDataBean("Date", null, DATE); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($date)); ?>">
				<br />
				
				<!-- GPS -->
				Ville de départ :<br />
				<input type="text" name="Depart" value=""  data-inline="true" data-theme="c"/>
				<?php $gps = new MDataBean("Depart", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- GPS -->
				Ville d'arrivée :<br />
				<input type="text" name="Arrivée" value=""  data-inline="true" data-theme="c"/>
				<?php $gps = new MDataBean("Arrivée", null, GPS); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($gps)); ?>">

				<br /><br />
				<a href="#" data-role="button"onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()">Find</a>	
			</form>
		</div>
	<?php }
	
}
?>
<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once '../../lib/dasp/beans/MDataBean.class.php';

/**
 *
 * Represent the template
 * @author lvanni
 *
 */
class DetailsView extends MyApplication {

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
		parent::__construct("roadMap");
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="b">
			<h1>Feuille de route</h1>
			<a href="#Map" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
	<!-- CONTENT -->
	<div data-role="content">
		<div id="itineraire">
			<!-- <ul id="itineraireContent" data-role="listview" data-theme="c" ></ul>  -->
		</div>
	</div>
	
	<?php }
	
}
?>


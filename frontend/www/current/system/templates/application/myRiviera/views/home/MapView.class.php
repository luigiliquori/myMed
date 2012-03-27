<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 *
 * Represent the template
 * @author lvanni
 *
 */
class MapView extends MyApplication {

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
		parent::__construct("Map");
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="b">
			<h1><?= TARGET == "mobile" ? " " : APPLICATION_NAME ?></h1>
		
			<a href="#Option" class="ui-btn-right" data-icon="gear">Options</a>
			
			<!-- ITINERAIRE POPUP -->
			<a href="#Search" data-icon="search" data-iconpos="right" class="ui-btn-left">Rechercher</a>
			
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div data-role="content" style="padding: 0px;">
		
			<!-- MAP -->
			<div id="<?= APPLICATION_NAME ?>Map"></div>
		
			<script type="text/javascript">var mobile = '<?php echo TARGET ?>';</script>
		
			<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="c" style="width: <?= TARGET == "mobile" ? "85" : "35" ?>%;">
				<h3>Feuille de route</h3>
				<div id="itineraireContent" data-role="collapsible-set" data-theme="b" data-content-theme="d" ></div>
			</div>
			
			<div id="steps" data-role="controlgroup" data-type="horizontal">
				<a id="prev-step" data-role="button" data-theme="b" data-icon="arrow-l"	>&nbsp;</a>
				<a id="next-step" data-role="button" data-theme="b" data-iconpos="right" data-icon="arrow-r">&nbsp;</a>
			</div>

		</div>
	<?php }

}
?>
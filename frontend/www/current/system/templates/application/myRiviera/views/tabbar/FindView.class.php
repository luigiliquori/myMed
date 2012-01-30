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
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/ $handler) {
		parent::__construct("Find");
		$this->handler = $handler;
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="b">
			<a href="#Edit" data-role="button" data-rel="dialog" class="ui-btn-left	" data-icon="search">Itinéraire</a> 
			<h1>myRiviera</h1>
			<a href="#Option" data-role="button" data-rel="dialog" class="ui-btn-right" data-icon="gear">Options</a> 
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			
			<div id="myRivieraMap"></div>
			
			<?php if ($this->handler->getSuccess()) {
				// PRINT THE TRIP (kml format) FROM CITYWAY
				$kmlCityWay = $this->handler->getSuccess()->kml;
			} else if($this->handler->getError() == "1") { ?> 
				<!-- CITYWAY NOT AVAILABLE TRY WITH GOOGLE API -->
				<input id="start" type="hidden" value="<?= $_POST['Départ'] ?>">
				<input id="end" type="hidden" value="<?= $_POST['Arrivée'] ?>">
				<script type="text/javascript">setTimeout("calcRoute()", 500);</script>
			<?php } ?>
		</div>
	<?php }
	
}
?>

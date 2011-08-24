<?php

require_once dirname(__FILE__).'/MyTransport.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Find extends MyTransport {
	
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
		parent::__construct("myTransport_find");
		$this->title = "myTransport: Find";
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" data-role="content">

		</div>
	<?php }
	
}
?>
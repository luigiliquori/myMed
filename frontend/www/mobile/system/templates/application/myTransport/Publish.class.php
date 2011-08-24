<?php

require_once dirname(__FILE__).'/MyTransport.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Publish extends MyTransport {
	
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
		parent::__construct("myTransport");
		$this->title = "myTransport: Publish";
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
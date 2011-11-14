<?php

require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Notification extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Attribute */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("notification", "notification");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div style="background-color: #545454; color: white; width: 200px; font-size: 15px; font-weight: bold;">
			Notifications
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
		
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {?>
		<!-- CONTENT -->
		<div style="position:relative; height: 400px; width: 200px; overflow: auto; background-color: #f0f0f0; top:0px;">
		
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div style="position: absolute; left: 70.5%; top:385px;">
			<?php 
			$this->getHeader();
			$this->getContent();
			$this->getFooter();
			?>
		</div>
	<?php }
}
?>



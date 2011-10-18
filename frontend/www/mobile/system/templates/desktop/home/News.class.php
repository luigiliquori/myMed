<?php

require_once 'system/templates/AbstractTemplate.class.php';;
require_once dirname(__FILE__).'/handler/UpdateProfileHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class News extends AbstractTemplate {
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
		parent::__construct("news", "news");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div style="background-color: #415b68; color: white; width: 200px; font-size: 15px; font-weight: bold;">
			Actualités
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
		<div style="position:relative; height: 200px; width: 200px; overflow: auto; background-color: #edf2f4; top:0px;">
		
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div style="position: absolute; left: 71%; top:210px;">
			<?php 
			$this->getHeader();
			$this->getContent();
			$this->getFooter();
			?>
		</div>
	<?php }
}
?>



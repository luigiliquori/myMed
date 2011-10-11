<?php
require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Contact extends MyApplication {
	
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
		parent::__construct("contact", "Contact");
		
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="b">
			<h2>Contacter par mail</h2>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
	
	/**
	* Print the Template
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" data-role="content" data-theme="b">
			<h1><a href="mailto:<?= $_SESSION['producer']  ?>"><?= $_SESSION['producer']  ?></a></h1>
		</div>
	<?php }
}
?>
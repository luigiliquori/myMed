<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class MyAngel extends AbstractTemplate {
	
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
		parent::__construct("myAngel", "myAngel");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header">
			<a href="?application=0" rel="external" data-role="button" data-theme="r">Fermer</a>
			<h1><?= $this->title ?></h1>
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div class="content">
			<br><br>
			<img alt="button" src="system/templates/application/myAngel/button.png" onclick="alert('ALERT SENT!');">
		</div>
	<?php }
	
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getFooter() {
	}
}
?>
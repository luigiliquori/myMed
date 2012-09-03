<?php

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class AbstractView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $id;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*String*/ $id) {
		$this->id = $id;
		
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="b">
			<a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r">Deconnexion</a>
			<h1><?= APPLICATION_NAME ?></h1>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public abstract /*String*/ function getContent();
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() {  }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="b">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
}
?>

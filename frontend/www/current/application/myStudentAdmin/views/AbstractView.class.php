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
	protected /*String*/ $id;
	
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
		<img alt="logo" src="img/logo.gif">
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public abstract /*String*/ function getMenu();
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public abstract /*String*/ function getContent();
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { ?>
		<div data-role="navbar" data-iconpos="left">
			<ul>
				<li><a href="/application/myStudent" data-icon="gear" target="blank">Back</a></li>
			</ul>
		</div><!-- /navbar -->
		<?php include('../../system/views/logos.php'); ?>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="d">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getId(){
		return 	$this->id;
	}
}
?>

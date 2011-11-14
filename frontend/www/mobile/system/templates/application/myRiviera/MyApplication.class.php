<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class MyApplication extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $activeFooter;
	private /*MyTransportHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*String*/ $id) {
		parent::__construct($id, APPLICATION_NAME);
		$this->activeFooter = $id;
		
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="a">
			<a href="?application=0" rel="external" data-role="button" data-theme="r">Close</a>
			<h1><?= $this->title ?></h1>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER_PERSITENT-->
		<div data-role="footer" data-position="fixed" data-theme="a">
			<div data-role="navbar">
				<ul>
				<li><a href="#Find1" <?= $this->activeFooter == "Find1" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Mes Amis</a></li>
				<li><a href="#Find2" <?= $this->activeFooter == "Find2" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Mes Lieux</a></li>
				</ul>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="a">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
}
?>

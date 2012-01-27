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
		<div data-role="header" data-theme="b">
			<a href="#Edit" data-rel="dialog" data-icon="gear" class="ui-btn-left" >Edit</a>
			<h1><?= $this->title ?></h1>
			<a href="index.html" class="ui-btn-right">Départ</a>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER_PERSITENT-->
		<div data-role="footer" data-position="fixed" data-theme="b">
			<div data-role="navbar">
				<ul>
					<li><a href="#Find" data-back="true" <?= $this->activeFooter == "Find" ? 'class="ui-btn-active ui-state-persist"' : ''; ?> >Carte</a></li>
					<li><a href="#Trip" <?= $this->activeFooter == "Trip" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Itineraire</a></li>
				</ul>
			</div>
		</div>
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
}
?>

<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';
require_once dirname(__FILE__).'/handler/MyTransportHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class MyTransport extends AbstractTemplate {
	
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
		parent::__construct($id, $id);
		$this->activeFooter = $id;
		$this->handler = new MyTransportHandler();
		$this->handler->handleRequest();
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
			<!-- NOTIFICATION -->
			<?php if($this->handler->getError()) { ?>
				<h1 style="color: red;">
					<?= $this->handler->getError(); ?>
				</h1>
			<?php } else if($this->handler->getSuccess()) { ?>
				<h1 style="color: #12ff00;">
					<?= $this->handler->getSuccess(); ?>
				</h1>
			<?php } ?>
			<h1><?= $this->title ?></h1>
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
				<li><a href="#Carte" <?= $this->activeFooter == "Carte" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Carte</a></li>
				<li><a href="#Publier" data-back="true" <?= $this->activeFooter == "Publier" ? 'class="ui-btn-active ui-state-persist"' : ''; ?> >Publier</a></li>
				<li><a href="#Rechercher" <?= $this->activeFooter == "Rechercher" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Rechercher</a></li>
				<li><a href="?application=0" rel="external">Fermer</a></li>
				</ul>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { 
		?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="b">
			<?php $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
}
?>
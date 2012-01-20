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
	
	protected /*Array*/ $channelCategory = array("myMed", "logNet", "other");
	
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
			<?php if(TARGET == "desktop") { ?>
				<a href="#" onClick="window.close();" data-role="button" data-theme="r" target="_top">Fermer</a>
			<?php } else { ?>
				<a href="?application=0" rel="external" data-role="button" data-theme="r">Close</a>
			<?php } ?>
			<h1><?= $this->title ?><?= !CONNECTED ? " : Select your channel" : "" ?></h1>
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
				<li><a href="#Chat" data-back="true" <?= $this->activeFooter == "Chat" ? 'class="ui-btn-active ui-state-persist"' : ''; ?> >chat</a></li>
				<li><a href="#Find" <?= $this->activeFooter == "Find" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>channel</a></li>
				<?php if( $_SESSION["user"]->id == "MYMED_laurent.vanni@gmail.com") { ?>
					<li><a href="#Add" <?= $this->activeFooter == "Add" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>add</a></li>
				<?php } ?>
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
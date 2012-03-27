<?php

require_once 'system/templates/AbstractLogin.class.php';
require_once 'system/templates/application/' . APPLICATION_NAME . '/handler/InscriptionHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Login extends AbstractLogin {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("loginView", new InscriptionHandler());
		$this->handler->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Print the logo fo the application
	 */
	public /*void*/ function printLogo() { ?>
		<img id="logo" alt="title" src="system/templates/application/<?= APPLICATION_NAME ?>/views/login/desktop/img/title.png" height="30" />
	<?php }
	
	/**
	 * Print the left part of the main page
	 */
	public /*void*/ function printLeftPart() { ?>
		<div class="ui-block-a" Style="text-align: left;">
			<h3>Profitez d'une plateforme extensible, <br> programmable et ouverte à tous</h3>
			<img src="system/templates/application/<?= APPLICATION_NAME ?>/views/login/desktop/img/devices.png" height="320" /><br>
			<div>
				L'application myMed est bientôt disponible sur iPhone, iPad, Android, et plus encore...
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="a">
			<?php $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
}
?>
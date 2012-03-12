<?php

require_once 'system/templates/ITemplate.php';
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
		<img id="logo" alt="title" src="system/templates/application/<?= APPLICATION_NAME ?>/img/icon.png" height="50"  style="position: absolute; top: 0px;" />
		<span style="position: absolute; left: 60px; top:10px;"><span style="font-size: 16pt; font-weight: bold;">myRiviera</span> - v1.0 beta</span>
	<?php }
	
	/**
	 * Print the left part of the main page
	 */
	public /*void*/ function printLeftPart() { ?>
		<div class="ui-block-a" Style="text-align: left;">
			<h3>Le réseau social pour découvrir et animer<br />le territoire de la Riviera!</h3>
			<img src="system/templates/application/<?= APPLICATION_NAME ?>/img/login.png" height="320" /><br>
			<div>
				Aidez-nous à mieux valoriser ses richesses
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="b">
			<?php $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
}
?>
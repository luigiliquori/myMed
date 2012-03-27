<?php

require_once 'system/templates/AbstractTemplate.class.php';
require_once 'system/templates/application/' . APPLICATION_NAME . '/handler/InscriptionHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Login extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*InscriptionHandler*/ $handler;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("loginView", "loginView");
		$this->handler = new InscriptionHandler();
		$this->handler->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="b">
			<div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
				<a href="#inscription" data-role="button" data-inline="true" >inscription</a>
			</div>
		</div>		
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {
		?>
		<!-- CONTENT -->
		<div class="content" data-theme="b">
			<br /><br />
				<img id="logo" alt="title" src="system/templates/application/<?= APPLICATION_NAME ?>/img/icon.png" height="50"  style="position: absolute; margin-left: -140px; margin-top: 18px;" />
				<h1 style="position: relative;"><?= APPLICATION_NAME ?></h1>
			
			<!-- NOTIFICATION -->
			<?php if($this->handler->getError()) { ?>
				<div id="loginError" style="color: red;">
					<?= $this->handler->getError(); ?>
				</div>
			<?php } else if(isset($_SESSION['error'])) { ?>
				<div id="loginError" style="color: red;">
					<?= $_SESSION['error']; ?>
					<?php $_SESSION['error'] = null; ?>
				</div>
			<?php } else if($this->handler->getSuccess()) { ?>
				<div style="color: #12ff00;">
					<?= $this->handler->getSuccess(); ?>
				</div>
			<?php } ?>

			<br />
			<form action="#" method="post" name="singinForm" id="singinForm">
				<input type="hidden" name="singin" value="1" />
			    <input type="text" name="login" id="login" value="email"  data-theme="c"/><br />
			    <input type="password" name="password" id="password" value="Mot de passe"  data-theme="c"/><br />
			    <a href="#" onclick="document.singinForm.submit()" data-role="button" data-inline="true" data-theme="b">Connexion</a><br />
			    <a href="#socialNetwork">Connexion Via Réseau Social</a>
			</form>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		parent::printTemplate();
		include('system/templates/application/' . APPLICATION_NAME . '/views/dialog/inscription.php');
		include('system/templates/application/' . APPLICATION_NAME . '/views/dialog/socialNetwork.php');
	}
}
?>
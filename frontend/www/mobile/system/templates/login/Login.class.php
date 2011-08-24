<?php

require_once 'system/templates/login/AbstractLogin.class.php';
require_once dirname(__FILE__).'/handler/LoginHandler.class.php';

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
		parent::__construct("login");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {
		$loginHandler = new LoginHandler();
		$loginHandler->handleRequest();
		?>
		<!-- CONTENT -->
		<div class="content">
			<!-- LOGO -->
			<br /><br />
			<img alt="mymed" src="img/logo-mymed.png" width="200px;">
			<br />
			
			<!-- NOTIFICATION -->
			<?php if($loginHandler->getError()) { ?>
				<div style="color: red;">
					<?= $loginHandler->getError(); ?>
				</div>
			<?php } else if($loginHandler->getSuccess()) { ?>
				<div style="color: #12ff00;">
					<?= $loginHandler->getSuccess(); ?>
				</div>
			<?php } ?>
			
			<br />
			<form action="#" method="post" name="singinForm" id="singinForm">
				<input type="hidden" name="singin" value="1" />
			    <span>eMail:</span>
			    <input type="text" name="login" id="login" value="" /><br />
			    <span>Password:</span>
			    <input type="password" name="password" id="password" value="" />
				<br />
				<a href="#home" data-role="button" data-inline="true" onclick="document.singinForm.submit()">Connexion</a>
				<a href="#inscription" data-role="button" data-inline="true" data-rel="dialog">Inscription</a>
			</form>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		parent::printTemplate();
		include('inscription.php');
	}
}
?>
<?php

require_once 'system/templates/login/AbstractLogin.class.php';
require_once dirname(__FILE__).'/handler/InscriptionHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Login extends AbstractLogin {
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
		parent::__construct("login");
		$this->handler = new InscriptionHandler();
		$this->handler->handleRequest();
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
		?>
		<!-- CONTENT -->
		<div class="content">
			<!-- LOGO -->
			<br />
			<img alt="mymed" src="img/logo-mymed.png" width="200px;">
			<br />
			<br />
<!-- 			<a href="#socialNetwork" data-role="button" data-rel="dialog">Connexion Via Réseau Social</a> -->
			
			<!-- NOTIFICATION -->
			<?php if($this->handler->getError()) { ?>
				<div style="color: red;">
					<?= $this->handler->getError(); ?>
				</div>
			<?php } else if(isset($_SESSION['error'])) { ?>
				<div style="color: red;">
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
			    <span>eMail:</span><br />
			    <input type="text" name="login" id="login" value="" /><br />
			    <span>Mot de passe:</span><br />
			    <input type="password" name="password" id="password" value="" />
				<br />
				<input type="submit" data-role="button" data-inline="true" onclick="document.singinForm.submit()" value="Connexion"  data-theme="a"/>
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
		include('socialNetwork.php');
	}
}
?>
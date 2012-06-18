<?php

require_once MYMED_ROOT . 'system/controllers/InscriptionController.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Login extends AbstractView {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*InscriptionController*/ $inscriptionController;
	private /*InscriptionController*/ $loginController;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($loginController) {
		parent::__construct("loginView", "loginView");
		$this->inscriptionController = new InscriptionController();
		$this->inscriptionController->handleRequest();
		$this->loginController = $loginController;
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
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" data-theme="b">
			
			<h1><?= APPLICATION_NAME ?></h1>
			
			<!-- NOTIFICATION -->
			<?php 
			if($this->inscriptionController->getError() || $this->inscriptionController->getSuccess()) {
				$handler = $this->inscriptionController;
			} else {
				$handler = $this->loginController;
			}
			if($handler->getError()) {
				echo '<div id="loginError" style="color: red;">';
					$handler->getError();
				echo '</div>';
			} else if(isset($_SESSION['error'])) {
				echo '<div id="loginError" style="color: red;">';
					$_SESSION['error'];
					$_SESSION['error'] = null;
				echo '</div>';
			} else if($handler->getSuccess()) {
				echo '<div style="color: #12ff00;">';
					 $handler->getSuccess();
				echo '</div>';
			} 
			?>

			<br />
			<form action="#" method="post" name="signinForm" id="signinForm">
				<input type="hidden" name="signin" value="1" />
			    <input type="text" name="login" id="login" value="email"  data-theme="c"/><br />
			    <input type="password" name="password" id="password" value="Mot de passe"  data-theme="c"/><br />
				<a href="http://www-sop.inria.fr/lognet/MYMED/" target="blank">A propos</a><br />
 			    <a href="#" onclick="document.signinForm.submit()" data-role="button" data-inline="true" data-theme="b" rel="external">Connexion</a>
			</form>
			
			<?php include(MYMED_ROOT . 'system/views/logos.php'); ?>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		parent::printTemplate();
		include('views/dialog/inscription.php');
		include('views/dialog/socialNetwork.php');
		include('views/dialog/condition.php');
	}
}
?>
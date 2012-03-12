<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class AbstractLogin extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id) {
		parent::__construct($id);
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header">
			<div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
				<a href="#inscription" data-role="button" data-inline="true" data-rel="dialog" >inscription</a>
			</div>
		</div>		
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {
		?>
		<!-- CONTENT -->
		<div class="content">
			<br /><br />
			<h1>myMed</h1>
			
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
			    <input type="text" name="login" id="login" value="email" /><br />
			    <input type="password" name="password" id="password" value="Mot de passe" /><br />
			    <a href="#" onclick="document.singinForm.submit()"><span style="color:white; text-decoration: none;">Connexion</span></a>
			</form>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		parent::printTemplate();
		include('views/inscription.php');
		include('views/socialNetwork.php');
	}
}
?>
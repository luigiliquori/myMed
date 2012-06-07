<?php

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
	private /*InscriptionHandler*/ $handler;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($handler) {
		parent::__construct("loginView");
		$this->handler = $handler;
		$this->printTemplate();
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
			<br /><br />
			<div style="position: absolute; left: 50%; top: 90px;">
				<img alt="title" src="img/icon.png" height="50"  style="position: relative; margin-left: -150px;" />
			</div>
			<div style="position: absolute; left: 50%; top: 73px;">
				<h1 style="position: relative; left: -40px;"><?= APPLICATION_NAME ?></h1>
			</div>
			<br /><br />
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
			<form action="#" method="post" name="signinForm" id="signinForm">
				<input type="hidden" name="signin" value="1" />
			    <input type="text" name="login" id="login" value="email"  data-theme="c"/><br />
			    <input type="password" name="password" id="password" value="Mot de passe"  data-theme="c"/><br />
			    <a href="#" onclick="document.signinForm.submit()" data-role="button" data-inline="true" data-theme="b">Connexion</a><br />
			</form>
			
			<div class="innerContent" style="position: relative; top:50px; color: black;">
				<h4>myMed - INTERREG IV - Alcotra</h4>
				<img alt="Alcotra" src="../../system/img/logos/alcotra"
					style="width: 100px;" /> <img alt="Europe"
					src="../../system/img/logos/europe" style="width: 50px;" /> <img
					alt="Conseil Général 06" src="../../system/img/logos/cg06"
					style="width: 100px;" /> <img alt="Regine Piemonte"
					src="../../system/img/logos/regione" style="width: 100px;" /> <img
					alt="Région PACA" src="../../system/img/logos/PACA" style="width: 100px;" />
				<img alt="Prefecture 06" src="../../system/img/logos/pref"
					style="width: 70px;" /> <img alt="Inria"
					src="../../system/img/logos/inria.jpg" style="width: 100px;" />
				<p>"Ensemble par-delà les frontières"</p>
			</div>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		parent::printTemplate();
		include(dirname(__FILE__).'/../../dialog/inscription.php');
		include(dirname(__FILE__).'/../../dialog/socialNetwork.php');
	}
}
?>
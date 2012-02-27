<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';
require_once 'system/templates/handler/InscriptionHandler.class.php';

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
	 * Print the logo fo the application
	 */
	public /*void*/ function printLogo() { ?>
		<img id="logo" alt="title" src="system/templates/container/desktop/login/img/title.png" height="30" />
	<?php }
	
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div id="loginHeader" align="center">
			<div id="loginHeaderContainer">
				<?php $this->printLogo() ?>
				<form action="#" method="post" name="singinForm" id="singinForm">
					<input type="hidden" name="singin" value="1"/>
					<div data-role="fieldcontain">
						<div id="loginEmail">
							<label for="name">Adresse électronique:</label><br>
						    <input type="text" name="login" id="login" value="" size="45" data-theme="c"/><br>
						    <a href="#socialNetwork" data-rel="dialog" >Connexion Via Réseau Social</a>
					    </div>
					    <div id="loginPassword">
						    <label for="name" >Mot de passe:</label><br>
						    <input type="password" name="password" id="password" value="" size="45" data-theme="c"/><br>
						    <a href="#retrievePassword" >Mot de passe oublié ?</a>
					    </div>
					    <div id="loginConnexion">
						    <a data-role="button" data-inline="true" onclick="document.singinForm.submit()">Connexion</a>
					    </div>
					</div>	
				</form>
			</div>
		</div>
	<?php }
	
	/**
	 * Print the left part of the main page
	 */
	public /*void*/ function printLeftPart() { ?>
		<div class="ui-block-a" Style="text-align: left;">
			<h3>Profitez d'une plateforme extensible, <br> programmable et ouverte à tous</h3>
			<img src="system/templates/container/desktop/login/img/devices.png" height="320" /><br>
			<div>
				L'application myMed est bientôt disponible sur iPhone, iPad, Android, et plus encore...
			</div>
		</div>
	<?php }
	
	/**
	* Print the right part of the main page
	*/
	public /*void*/ function printRightPart() { ?>
		<div class="ui-block-b">
				<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
					<div data-role="fieldcontain" >
						<h3 >Inscription:</h3>
						<!-- NOTIFICATION -->
						<?php if($this->handler->getError()) { ?>
							<div id="loginError" style="position: absolute; left:100px; top:0px; color: red;">
								<?= $this->handler->getError(); ?>
							</div>
						<?php } else if(isset($_SESSION['error'])) { ?>
							<div id="loginError" style="position: absolute; left:100px; top:0px; color: red;">
								<?= $_SESSION['error']; ?>
								<?php $_SESSION['error'] = null; ?>
							</div>
						<?php } else if($this->handler->getSuccess()) { ?>
							<div style="position: absolute; left:100px; top:0px; color: #12ff00;">
								<?= $this->handler->getSuccess(); ?>
							</div>
						<?php } ?>
						<hr>
						<div data-role="fieldcontain">
							<input type="hidden" name="inscription" value="1" />
							<label>Prénom : </label><br /><input type="text" name="prenom" data-theme="d"/><br>
							<label>Nom : </label><br /><input type="text" name="nom" data-theme="d"/><br>
							<label>eMail : </label><br /><input type="text" name="email" data-theme="d"/><br>
							<label>Password : </label><br /><input type="password" name="password" data-theme="d"/><br>
							<label>Confirm : </label><br /><input type="password" name="confirm" data-theme="d"/><br>
							<label>Date de naissance : </label><br /><input type="text" name="birthday" data-theme="d"/>
							<input type="checkbox" name="checkCondition" style="position: absolute; top: 25px;"/><br>
							<span style="position: relative; left: 50px;">J'accepte les <a href="#">conditions d'utilisation</a></span>
						</div>
						<hr>
						<div align="center">
							<a href="#" data-role="button" data-inline="true" onclick="window.document.getElementById('inscriptionForm').submit()">Création</a>
						</div>
					</div>
				</form>
			</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div id="loginContent" align="center">
			<div id="loginContainer">
				<div class="ui-grid-a"> 
					<?php $this->printLeftPart() ?>
					<?php $this->printRightPart() ?>
				</div>
			</div>
		</div>
	<?php }
	
	/**
	* Footer
	*/
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER -->
		<div id="loginFooter" align="center">
			<div id="loginFooterContainer">
				<div style="position: absolute; left:0px;">
					<a href="#" >Francais</a>
					<a href="#" >English</a>
					<a href="#" >Italiano</a>
				</div>
				<hr style="position: absolute; top:15px; width: 100%;">
				<div style="position: absolute; top:30px; left:0px;">
					<span>myMed - INTERREG IV - Alcotra</span>
				</div>
				<div style="position: absolute; top:25px; right:0px;">
					<a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static1/projet">Documentation</a>
					<a href="http://www.mymed.fr">News</a>
					<a href="http://www-sop.inria.fr/teams/lognet/MYMED/index.php?static4/join">Contact</a>
				</div>
			</div>
		</div>
	<?php }
	
}
?>
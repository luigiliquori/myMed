<?php

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
	protected /*InscriptionHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id, $handler) {
		parent::__construct($id, $id);
		$this->handler = $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Print the logo fo the application
	 */
	abstract public /*void*/ function printLogo();
	
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div id="loginHeader" align="center">
			<div id="loginHeaderContainer">
				<?php $this->printLogo() ?>
				<form action="#" method="post" name="signinForm" id="signinForm">
					<input type="hidden" name="signin" value="1"/>
					<div data-role="fieldcontain">
						<div id="loginEmail">
							<label for="name">Adresse électronique:</label><br>
						    <input type="text" name="login" id="login" value="" size="45" data-theme="c"/><br>
						    <a href="#socialNetwork" data-rel="dialog" >Connexion Via Réseau Social</a>
					    </div>
					    <div id="loginPassword">
						    <label for="name" >Mot de passe:</label><br>
						    <input type="password" name="password" id="password" value="" size="45" data-theme="c"/><br>
<!-- 						    <a href="#retrievePassword" >Mot de passe oublié ?</a> -->
					    </div>
					    <div id="loginConnexion">
						    <a data-role="button" data-inline="true" onclick="document.signinForm.submit()">Connexion</a>
					    </div>
					</div>	
				</form>
			</div>
		</div>
	<?php }
	
	/**
	 * Print the left part of the main page
	 */
	abstract public /*void*/ function printLeftPart();
	
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
							<label>Mot de passe : </label><br /><input type="password" name="password" data-theme="d"/><br>
							<label>Confirmation : </label><br /><input type="password" name="confirm" data-theme="d"/><br>
							<input type="checkbox" name="checkCondition" style="position: absolute; top: 25px;"/><br>
							<span style="position: relative; left: 50px;">J'accepte les <a href="#condition">conditions d'utilisation</a></span>
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
				<div class="innerContent" style="position: relative; top:50px; color: black;">
					<h4>myMed - INTERREG IV - Alcotra</h4>
					<img alt="Alcotra" src="system/img/logos/alcotra"
						style="width: 100px;" /> <img alt="Europe"
						src="system/img/logos/europe" style="width: 50px;" /> <img
						alt="Conseil Général 06" src="system/img/logos/cg06"
						style="width: 100px; height: 30px;" /> <img alt="Regine Piemonte"
						src="system/img/logos/regione" style="width: 100px;" /> <img
						alt="Région PACA" src="system/img/logos/PACA" style="width: 100px;" />
					<img alt="Prefecture 06" src="system/img/logos/pref"
						style="width: 70px;" /> <img alt="Inria"
						src="system/img/logos/inria.jpg" style="width: 100px;" />
					<p>"Ensemble par-delà les frontières"</p>
				</div>
			</div>
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
	
}
?>
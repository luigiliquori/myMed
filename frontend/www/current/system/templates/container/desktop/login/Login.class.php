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
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div align="center" style="position: absolute; top:0px; left: 0px; width: 100%; height: 100px; background-color: #2d2d2d;">
			<div style="position: relative; width: 1024px;">
				<img Style="position: absolute; top:17px; left: 0px;" alt="title" src="system/templates/container/desktop/login/img/title.png" height="30" />
				<form action="#" method="post" name="singinForm" id="singinForm">
					<input type="hidden" name="singin" value="1"/>
					<div data-role="fieldcontain">
						<div style="position: absolute; left:380px; top:-10px; text-align: left;">
							<label for="name" style="font-size: 9pt;">Adresse électronique:</label><br>
						    <input type="text" name="login" id="login" value=""  Style="width: 200px;"/><br>
						    <a href="#socialNetwork" data-rel="dialog" style="font-size: 9pt;">Connexion Via Réseau Social</a>
					    </div>
					    <div style="position: absolute; left:600px;  top:-10px; text-align: left;">
						    <label for="name" style="font-size: 9pt;">Mot de passe:</label><br>
						    <input type="password" name="password" id="password" value=""   Style="width: 200px;"/><br>
						    <a href="#retrievePassword" style="font-size: 9pt;">Mot de passe oublié ?</a>
					    </div>
					    <div style="position: absolute; left:820px; top: 0px; text-align: left;">
						    <a data-role="button" data-inline="true" onclick="document.singinForm.submit()" data-theme="a">Connexion</a>
					    </div>
					</div>	
					<br />
				</form>
			</div>
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {
		?>
		<!-- CONTENT -->
		<div  align="center" Style="position: absolute; top: 100px; left:0px; width: 100%; height:470px; center; background-image: url('system/templates/container/desktop/login/img/background.png');">
			<div style="position: relative; width: 1024px;">
				<div class="ui-grid-a">
					<div class="ui-block-a" Style="text-align: left;">
						<h3  style="color: black;">Profitez d'une plateforme extensible, <br> programmable et ouverte à tous</h3>
						<img src="system/templates/container/desktop/login/img/devices.png" height="320" /><br>
						<div style="position: relative; font-size: 9pt; color: black; top:20px;">
							L'application myMed est bientôt disponible sur iPhone, iPad, Android, et plus encore...
						</div>
					</div>
					<div class="ui-block-b">
						<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
							<div data-role="fieldcontain" style="text-align: left; font-size: 9pt; color: black;">
								<h3  style="color: black;">Inscription:</h3>
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
								<input type="hidden" name="inscription" value="1" />
								<span>Prénom : </span><br /><input type="text" name="prenom" /><br />
								<span>Nom : </span><br /><input type="text" name="nom" /><br />
								<span>eMail : </span><br /><input type="text" name="email" /><br />
								<span>Password : </span><br /><input type="password" name="password" /><br />
								<span>Confirm : </span><br /><input type="password" name="confirm" /><br />
								<span>Date de naissance : </span><br /><input type="text" name="birthday" /><br />
								<input type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
								<span style="position: relative; left: 50px;">J'accepte les <a href="#">conditions d'utilisation</a></span><br />
								<hr>
								<a Style="position: relative; left: 100px;" href="#" data-role="button" data-inline="true" onclick="window.document.getElementById('inscriptionForm').submit()">Création</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php }
	
	/**
	* Footer
	*/
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER -->
		<div align="center" Style="position: absolute; top: 570px; left:0px; width: 100%; height:50px;">
			<div style="position: relative; width: 1024px; left:-40px; font-size: 9pt;">
				<div style="position: absolute; left:0px;">
					<a href="#" style="font-size: 9pt;">Francais</a>
					<a href="#" style="font-size: 9pt;">English</a>
					<a href="#" style="font-size: 9pt;">Italiano</a>
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
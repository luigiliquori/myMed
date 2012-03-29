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
			<h1><?= APPLICATION_NAME ?></h1>
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
			<h1>Maintenance en cours</h1>
			<p>
				Nous Réalisons une opération de maintenance de notre site afin d'améliorer la qualité du service proposé.<br />
				Il sera momentanément indisponible durant cette période<br />
			</p>
			<p>
				<h2>myRiviera v1.0 beta</h2>
				<h3>myMed - INTERREG IV - Alcotra</h3>
				<div class="innerContent">
					<img alt="Alcotra" src="system/img/logos/alcotra"
						style="width: 100px;" /> <img alt="Europe"
						src="system/img/logos/europe" style="width: 50px;" /> <img
						alt="Conseil Général 06" src="system/img/logos/cg06"
						style="width: 100px;" /> <img alt="Regine Piemonte"
						src="system/img/logos/regione" style="width: 100px;" /> <img
						alt="Région PACA" src="system/img/logos/PACA" style="width: 100px;" />
					<img alt="Prefecture 06" src="system/img/logos/pref"
						style="width: 70px;" /> <img alt="Inria"
						src="system/img/logos/inria" style="width: 100px;" />
					<p>"Ensemble par-delà les frontières"</p>
				</div>
			</p>
			
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		parent::printTemplate();
	}
}
?>
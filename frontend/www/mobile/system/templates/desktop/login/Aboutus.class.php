<?php

require_once dirname(__FILE__).'/AbstractLogin.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Aboutus extends AbstractLogin {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("aboutus");
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
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<h1 class="title">Nous Contacter</h1>
			<p>
			Pour toute information et/ou pour s'associer au projet myMed:
			<br>
			envoyez une mail à
			<a href="mailto:infomymed@mymed.fr">infomymed@mymed.fr</a>
			ou contactez le chef de file du projet Luigi Liquori au +33 6 78 35 80 88
			<br>
			</p>
		</div>
	<?php }
}
?>
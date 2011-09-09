<?php

require_once dirname(__FILE__).'/MyTransport.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Publish extends MyTransport {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Publier");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
		<!-- <form  action="http://mymed2.sophia.inria.fr:8080/mymed_backend/PubSubRequestHandler" method="post" name="publishForm" id="publishForm"> -->
			<form  action="#" method="get" name="publishForm" id="publishForm">
				<input type="hidden" name="application" value="myTransport" />
				<input type="hidden" name="publish" value="1" />
				Date :
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/><br /><br />
				Ville de départ :
				<input type="text" name="start" value=""  data-inline="true"/><br />
				Ville d'arrivée :
				<input type="text" name="end" value=""  data-inline="true"/><br />
				Type de vehicule: 
				<select name="type" data-theme="a">
					<option value="Compacte"> Compacte - Berline - Coupé</option>
					<option value="Cabriolet">Cabriolet</option>
					<option value="Break">Break - Monospace</option>
					<option value="4x4">4x4</option>
					<option value="Sportive">Sportive</option>
					<option value="Collection">De collection</option>
					<option value="Utilitaires">Utilitaires - véhicules de société</option>
					<option value="Sans">Véhicules sans permis</option>
				</select>
				<br />
				Commentaires :
				<textarea name="comment"></textarea><br />
				<a href="#" data-role="button" onclick="document.publishForm.submit()">Publier</a>
			</form>
		</div>
	<?php }
	
}
?>
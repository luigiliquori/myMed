<?php

require_once dirname(__FILE__).'/MyGod.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Publish extends MyGod {
	
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
			<form  action="#" method="get" name="publishForm" id="publishForm">
				<input type="hidden" name="application" value="myTransport" />
				<input type="hidden" name="publish" value="1" />
				Date :
				<input type="date" name="date" value="<?php echo date('Y-m-d');?>"  data-inline="true"/><br /><br />
				Ville de départ :
				<input type="text" name="start" value=""  data-inline="true"/><br />
				Ville d'arrivée :
				<input type="text" name="end" value=""  data-inline="true"/><br />
				<a href="#" data-role="button"onclick="document.publishForm.submit()">Publier</a>
			</form>
		</div>
	<?php }
	
}
?>
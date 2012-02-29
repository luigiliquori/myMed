<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Validate extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct( $id = "Validate") {
		parent::__construct($id);
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="a">
			<h1>Créer Application</h1>
		</div>
	<?php }

	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*void*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" data-role="content">
			<input type="text" name="name" value="Nom de l'application" id="basic" data-mini="true" />
			<center>
				<a href="#" data-role="button" data-theme="g" data-inline="true">Valider</a>
			</center>
		</div>
	<?php }
	
}
?>
<?php

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class AbstractView {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	protected /*String*/ $id;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id) {
		$this->id = $id;
	}
	
	/* --------------------------------------------------------- */
	/* Define a tempalte */
	/* --------------------------------------------------------- */
	abstract protected /*void*/ function getHeader();
	
	abstract protected /*void*/ function getContent();
	
	protected /*void*/ function getFooter() {}
	
	public /*void*/ function printTemplate() {
		echo '<div id="' . $this->id . '" data-role="page" data-theme="b" >';
			$this->getHeader();
			$this->getContent();
			$this->getFooter();
		echo '</div>';
	}
	
	/* --------------------------------------------------------- */
	/* Init a tempalte */
	/* --------------------------------------------------------- */
	public static function initializeTemplate(/*String*/ $applicationName) {
		// NAME OF THE APPLICATION
		
		// LOAD GOOGLE MAP
		?>
		
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
		<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>
		
		<?php 
		// IMPORT THE HANDLER


		// DISCONNECT FORM
		
	}
	
}
?>
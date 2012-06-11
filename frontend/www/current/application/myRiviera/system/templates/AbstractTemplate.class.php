<?php

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class AbstractTemplate {
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
		define('APPLICATION_NAME', $applicationName);
	
		?>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

		<script type="text/javascript">
		$(document).bind("mobileinit", function(){
			$.mobile.loadingMessageTextVisible = true;
		});
		</script>
		<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
		<script src="../../lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>
		<link href="../../lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />
		

		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key='. Google_APP_SECRET .'&sensor=true&libraries=places"></script>
		<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>

		<script src='../../lib/dasp/javascript/dasp.js'></script>
		
		<?php 
		// LOAD APPLICATION JAVASCRIPT
		if ($handle = opendir("system/templates/application/" . APPLICATION_NAME . '/javascript')) {
			while (false !== ($file = readdir($handle))) {
				if($file != "." && $file != ".." && $file != ".DS_Store"){
			    		echo '<script src="system/templates/application/' . APPLICATION_NAME . '/javascript/' . $file . '"></script>';
					}
			    } 
		}
		echo '<script type="text/javascript">setTimeout("initialize()", 1000);</script>';
		
		// LOAD THE CSS OF THE APPLICATION
		if ($handle = opendir("system/templates/application/" . APPLICATION_NAME . '/css')) {
			while (false !== ($file = readdir($handle))) {
				if($file != "." && $file != ".." && $file != ".DS_Store"){
			    	echo '<link href="system/templates/application/' . APPLICATION_NAME . '/css/' . $file . '" rel="stylesheet" />';
				}
			} 
		} 
	
		// IMPORT THE HANDLER
		require_once "system/templates/application/" . APPLICATION_NAME . '/handler/MenuHandler.class.php';
		$menuHandler = new MenuHandler();
		$menuHandler->handleRequest();
		require_once "system/templates/application/" . APPLICATION_NAME . '/handler/UpdateProfileHandler.class.php';
		$updateHandler = new UpdateProfileHandler();
		$updateHandler->handleRequest();

		// DISCONNECT FORM
		echo '<form action="?application=' . APPLICATION_NAME . '" method="post" name="disconnectForm" id="disconnectForm">';
		echo '<input type="hidden" name="disconnect" value="1" /></form>';
	}
	
}
?>
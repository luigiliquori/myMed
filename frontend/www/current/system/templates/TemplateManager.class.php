<?php

/**
 * 
 * Manage all the template
 * @author lvanni
 *
 */
class TemplateManager {
	
	/** the selected template */
	private /*string*/ $template;
	
	/**
	 * Default constructor 
	 * @param unknown_type $template
	 */
	public function __construct(/*string*/ $template="mobile/login") {
		$this->template	= $template;
	}
	
	/**
	 * Print the http header
	 */
	public function getHeader(){ ?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

		<head> 
		<title>myMed | Réseaux Social Transfrontalier</title> 
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, <?= TARGET == "mobile" ? "user-scalable=0" : "" ?>" /> 
		
		<!-- JQUERY -->
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css" />
		<link type="text/css" href="http://dev.jtsage.com/cdn/datebox/latest/jquery.mobile.datebox.min.css" rel="stylesheet" /> 
		<link type="text/css" href="http://dev.jtsage.com/jQM-DateBox/css/demos.css" rel="stylesheet" /> 
		
		<!-- NOTE: Script load order is significant! -->
		
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script> 
		<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>
	
		<script type="text/javascript" src="http://dev.jtsage.com/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jquery.mobile.datebox.min.js"></script>
		<script type="text/javascript" src="http://dev.jtsage.com/gpretty/prettify.js"></script>
		<link type="text/css" href="http://dev.jtsage.com/gpretty/prettify.css" rel="stylesheet" />
		
		<!-- MAP -->
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?= Google_APP_SECRET ?>&sensor=true"> </script>
		
		<!-- DATEBOX replaced by datebox jtusage -->
		<!--  <script src="lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>
		<link href="lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />   -->
		
		<!-- Common javascript -->
		<script src="system/javascript/common.js"></script>
		
		<!-- Common css -->
		<link href="system/css/style.css" rel="stylesheet" />

		
		<!-- LOAD DYNAMICALLY THE CSS FOR EACH TEMPLATE -->
		<?php 
		if ($handle = opendir('system/templates/'. $this->template . '/css')) {
		    while (false !== ($file = readdir($handle))) {
		    	if($file != "." && $file != ".." && $file != ".DS_Store"){ ?>
			    	<link href='system/templates/<?=  $this->template ?>/css/<?= $file ?>' rel="stylesheet" />
				<?php }
		    } 
		} 
		?>
		
		<!-- LOAD DYNAMICALLY THE JAVASCRIPT FOR EACH TEMPLATE -->
		<?php 
		if ($handle = opendir('system/templates/'. $this->template . '/javascript')) {
		    while (false !== ($file = readdir($handle))) {
		    	if($file != "." && $file != ".." && $file != ".DS_Store"){ ?>
		    		<script src='system/templates/<?=  $this->template ?>/javascript/<?= $file ?>'></script>
				<?php }
		    } 
		} 
		?>
		
		</head>
		
		<body onload="initialize();">
	<?php }
	
	/**
	 * 
	 * Print the http footer
	 */
	public function getFooter(){ ?>
		</body>
		</html>
	<?php }

	/**
	 * Methode to select template for the current page
	 * @param string $name	name of the template's file without .php
	 */
	public /*void*/ function selectTemplate(/*string*/ $name) {
		$this->template = $name;
	}
	
	/**
	 * Methode to call template for the current page
	 * @param string $name	name of the template's folder if not exists use current Template
	 */
	public /*void*/ function callTemplate(/*string*/ $name=null) {
		if($name!==null) {
			$this->selectTemplate($name);
		}
		
		$this->getHeader();
		require(dirname(__FILE__).'/' . $this->template . '/main.php');
		$this->getFooter();
	}
}
?>
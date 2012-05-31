<?php

require_once dirname(__FILE__).'/../../lib/dasp/request/Request.class.php';
require_once dirname(__FILE__).'/../../system/config.php';

class TemplateManager {
	
	/**
	 * Print header
	 */
	
	public function getHeader(){ ?>
	<!DOCTYPE html>
		<html manifest="">
		<head>
			<meta charset="utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1" />
			<title>myMemory</title>
			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
			<link rel="stylesheet" href="my.css" />
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
			<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
			<script src="app.js"></script>
		    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    	</head>
    	<body>
	<?php }

	
	/*
	 * Print Footer
	 */
	public function getFooter(){
		echo "</body>";
		echo "</html>";		
	}
	
}
?>
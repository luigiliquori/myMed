<?php

class TemplateManager {
	
	/**
	 * Print head
	 */
	
	public function getHead(){ ?>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>myTemplate</title>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
		<link rel="stylesheet" href="my.css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
		<script src="app.js"></script>
	    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<?php }
	
	public function getContent( $file=null ){ ?>
		<!doctype html>
		<html manifest="">
		<head>
			<?php $this->getHead();?>
		</head>
		<body>
			<?php if ($file) include($file);?>
		</body>
		</html>
	<?php }
}
?>
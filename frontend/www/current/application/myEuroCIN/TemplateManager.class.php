<?php

class TemplateManager {
	
	/**
	 * Print the http header
	 */
	public function getHeader(){ ?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

		<head> 
			<title>myRivieraAdmin | Réseaux Social Transfrontalier</title> 
			
			<meta http-equiv="content-type" content="text/html;charset=utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
			
			<!-- JQUERY -->
			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
			<link rel="stylesheet" href="lib/jquery/jquery.mobile.actionsheet.css" />
			<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
			<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>
			<script type="text/javascript" src="../lib/jquery/jquery.mobile.actionsheet.js"></script>
			<script src="../lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>
			<link href="../lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />
			
			<!-- APP JS -->
			<script src="javascript/system.js"></script>
			
			<!-- APP css -->
			<link href="css/style.css" rel="stylesheet" />
			
		</head>
		
		<body onload="hideLoadingBar();">
		<!-- Loading dialog -->
		<div id="loading" style="display:block;"><center><span>Chargement en cours...</span></center></div>
		<!-- Disconnect form -->
		<form action="?application='<?= APPLICATION_NAME ?>'" method="post" name="disconnectForm" id="disconnectForm">
			<input type="hidden" name="disconnect" value="1" />
		</form>
	<?php }
	
	/**
	 * 
	 * Print the http footer
	 */
	public function getFooter(){ ?>
		</body>
		</html>
	<?php }
}
?>
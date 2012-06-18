<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

		<head> 
			<title>myRivieraAdmin | Réseaux Social Transfrontalier</title> 
			
			<meta http-equiv="content-type" content="text/html;charset=utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
			
			<!-- JQUERY -->
			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
			<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
			<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>
			
			<!-- APP JS -->
			<script src="javascript/system.js"></script>
			
			<!-- APP css -->
			<link href="css/style.css" rel="stylesheet" />
			
			<!-- GOOGLE MAP -->
			<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&libraries=places"></script>
			<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>
			
			<!-- DASP -->
			<script src='../../lib/dasp/javascript/dasp.js'></script>
			
		</head>
		
		<body onLoad="initialize()">
		
		<!-- Disconnect form -->
		<form action="?application='<?= APPLICATION_NAME ?>'" method="post" name="disconnectForm" id="disconnectForm">
			<input type="hidden" name="disconnect" value="1" />
		</form>
<?php
	//define('MYMED_ROOT', '../../');
	require_once '../../../lib/dasp/beans/MPositionBean.class.php';	
	require_once '../../../system/config.php';
	require_once '../../../lib/dasp/beans/MDataBean.class.php';
	//include dirname(__FILE__).'/../../system/config.php';
	session_start();

	
?>

		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

		<head> 
		<meta charset="utf-8"  name="viewport" content="width=device-width, initial-scale=1" />
		
		<!-- Google Analytics -->
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-31172274-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		
		<!-- Common javascript -->
		<script src="../javascript/common.js"></script>
		
		</head>
		
		<body onload="initialize();">
		
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

		<script type="text/javascript">
		$(document).bind("mobileinit", function(){
			$.mobile.loadingMessageTextVisible = true;
		});
		</script>
		<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
		
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&libraries=places"></script>
		
		<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>

		<script type="text/javascript" src='../../../lib/dasp/javascript/dasp.js'></script>
		<link href="../css/style.css" rel="stylesheet" />
		<script type="text/javascript" src="../javascript/myRivieraCopy.js"></script>
		

<?php 

	define('APPLICATION_NAME', "myFSA");
		
		// IMPORT THE MAIN VIEW
		require_once dirname(__FILE__).'/../views/LocalisationView.class.php';
		
		// BUILD THE VIEWs		
		$localisate = new LocalisationView();
		$localisate->getPosition();
		$localisate->getSearching();
		$localisate->getDetails();
		
		// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
		// TODO REMOVE THIS
		echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
	
?>
		</body>
		</html>


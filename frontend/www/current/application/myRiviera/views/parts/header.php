<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 

<head> 

	<title><?= empty($TITLE) ? APPLICATION_NAME : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!--  Extra icons for jquery -->
	<link rel="stylesheet" href="/lib/jquery/jqm-icon-pack-2.1.2-fa.css" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="/lib/jquery/jquery.mobile-1.2.0.css" />
	<script src="/lib/jquery/jquery-1.8.2.min.js"></script>
	<script src="/lib/jquery/jquery.mobile-1.2.0.js"></script>
	
	<!-- Cleeditor -->
	<link rel="stylesheet" type="text/css" href="jquery/CLEeditor/jquery.cleditor.css" />
    <script type="text/javascript" src="jquery/CLEeditor/jquery.cleditor.min.js"></script>
    <script type="text/javascript" src="jquery/CLEeditor/startCLE.js"> </script>
    
	<!-- APP css -->
	<link href="css/style.css" rel="stylesheet" />
	
	<!-- MYMED css -->
	<link href="/system/css/common.css" rel="stylesheet" />	
	
	<!-- APP JS -->
	<script type="text/javascript" src="<?= APP_ROOT ?>/javascript/common.js"></script>
	<script type="text/javascript" src="<?= APP_ROOT ?>/javascript/myRiviera.js"></script>
	<script type="text/javascript" src="<?= MYMED_URL_ROOT ?>/lib/dasp/javascript/dasp.js"></script>
	
	<!-- GOOGLE MAP -->
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&amp;libraries=places"></script>
	<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>
	
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
	
	</head>
		
<body <?= isset($_SESSION['user']) ? 'onload="initialize();"' : ''?> >

<?php 
//no html input should be inserted there in dom
?>
<input type='hidden' id='userID' value='<?= $_SESSION['user']->id ?>' />
<input type='hidden' id='applicationName' value='<?= APPLICATION_NAME ?>' />
<input type='hidden' id='accessToken' value='<?= $_SESSION['accessToken'] ?>' />





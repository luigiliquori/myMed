<? include_once('utils.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

<head> 

	<title><?= empty($TITLE) ? APPLICATION_NAME : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="../../lib/jquery/jquery.mobile-1.1.0.min.css" />
	<script src="../../lib/jquery/jquery-1.6.4.min.js"></script>
	<script src="../../lib/jquery/jquery.mobile-1.1.0.min.js"></script>
	
	<!-- DateBox -->
	<script src="../../lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>
	<link href="../../lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />
	
	<!-- MYMED JS -->
	<script src="../myMed/javascript/app.js"></script>
	<!-- APP JS -->
	<script src="javascript/app.js"></script>
	
	<!-- MYMED css -->
	<link href="../myMed/css/style.css" rel="stylesheet" />
	<!-- APP css -->
	<link href="css/style.css" rel="stylesheet" />
	
	<!-- GOOGLE MAP -->
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&libraries=places"></script>
	<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>
	
	<!-- DASP -->
	<script src='../../lib/dasp/javascript/dasp.js'></script>

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
		
<body>

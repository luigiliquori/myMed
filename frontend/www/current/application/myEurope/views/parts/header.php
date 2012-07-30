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
	
	<!-- add This or Share This -->
	<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
	<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-500da6e72a4d4d2d"></script>
	
	<!-- MYMED JS -->
	<script src="../myMed/javascript/app.js"></script>
	<!-- APP JS -->
	<script src="javascript/app.js"></script>
	
	<!-- MYMED css -->
	<link href="../myMed/css/style.css" rel="stylesheet" />
	<!-- APP css -->
	<link href="css/app.css" rel="stylesheet" />
	
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

<? // ================== Switch to active tab on load ==========================================?>
<? if (!empty($TAB)) :?>
	<script type="text/javascript">
		$(document).ready(function() {
			$.mobile.changePage("#<?= $TAB ?>", {transition:"none"})
		});
	</script>
<? endif ?>


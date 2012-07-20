<? include_once('view-utils.php'); ?>
<!doctype html>
<html 
 lang="fr"
 <? if (defined("DEMO")) print 'manifest="cache.manifest"' ?>
> 

<head> 

	<title><?= empty($TITLE) ? "My Bénévolat" : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="../../lib/jquery/jquery.mobile-1.1.0.min.css" />
	<script src="../../lib/jquery/jquery-1.6.4.min.js"></script>
	<script src="../../lib/jquery/jquery.mobile-1.1.0.min.js"></script>
	
	<!-- DateBox -->
	<script src="../../lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>
	<link href="../../lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />
	
	<!-- Keyfilter  -->
	<script src="../../lib/jquery/jquery.keyfilter.js" ></script>
	
	<!--  SimpleDialog -->
	<script src="../../lib/jquery/simpledialog/jquery.mobile.simpledialog2.min.js"></script>
	<link href="../../lib/jquery/simpledialog/jquery.mobile.simpledialog.min.css" rel="stylesheet" />
	
	<!-- MYMED JS -->
	<script src="../myMed/javascript/common.js"></script>
	<!-- APP JS -->
	<script src="javascript/app.js"></script>
	
	<!-- MYMED css -->
	<link href="../myMed/css/style.css" rel="stylesheet" />
	
	<!-- APP css -->
	<link href="css/style.css" rel="stylesheet" />
	
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


<? include_once('header-bar-light.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

<head> 

	<title><?= empty($TITLE) ? APPLICATION_NAME : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!-- load css before scripts to stop sort of flash effect  -->
	
	
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="../../lib/jquery/jquery.mobile.css" />
	<!-- APP css -->
	<link href="<?= APP_ROOT ?>/css/style.css" rel="stylesheet" />
	<!-- MYMED css -->
	<link href="<?= MYMED_URL_ROOT ?>/system/css/common.css" rel="stylesheet" />
	
	
	
	<script src="../../lib/jquery/jquery-1.7.2.min.js"></script>
	<script src="../../lib/jquery/jquery.mobile.js"></script>
	

	<!-- jQuery icons pack -->
	<!-- <link href="<?= MYMED_URL_ROOT ?>/lib/jquery/jQuery-Mobile-Icon-Pack/original/jqm-icon-pack-2.0-original.css" rel="stylesheet" /> -->
	<!-- <link href="<?= MYMED_URL_ROOT ?>/lib/jquery/jQuery-Mobile-Icon-Pack/font-awesome/jqm-icon-pack-2.1.2-fa.css" rel="stylesheet" /> -->
	
	<!--  SimpleDialog -->
	<!-- <script src="<?= MYMED_URL_ROOT ?>/lib/jquery/simpledialog/jquery.mobile.simpledialog2.min.js"></script> -->
	<!-- <link href="<?= MYMED_URL_ROOT ?>/lib/jquery/simpledialog/jquery.mobile.simpledialog.min.css" rel="stylesheet" /> -->
	

	<!-- APP JS -->
	<script src="<?= APP_ROOT ?>/javascript/app.js"></script>
	
	
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
		
<body onload="hideLoadingBar()">

<? // ================== Switch to active tab on load ==========================================?>
<? if (!empty($TAB)) :?>
	<script type="text/javascript">
		$(document).ready(function() {
			$.mobile.changePage("#<?= $TAB ?>", {transition:"none"})
		});
	</script>
<? endif ?>


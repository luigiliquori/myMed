<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

<head> 

	<title><?= empty($TITLE) ? APPLICATION_NAME : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="<?= MYMED_ROOT ?>lib/jquery/jquery.mobile-1.1.0.min.css" />
	<script src="<?= MYMED_ROOT ?>/lib/jquery/jquery-1.6.4.min.js"></script>
	<script src="<?= MYMED_ROOT ?>/lib/jquery/jquery.mobile-1.1.0.min.js"></script>
	
	<!-- DateBox -->
	<script src="<?= MYMED_ROOT ?>/lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>
	<link href="<?= MYMED_ROOT ?>/lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />
	
	<!--  SimpleDialog -->
	<script src="<?= MYMED_ROOT ?>/lib/jquery/simpledialog/jquery.mobile.simpledialog2.min.js"></script>
	<link href="<?= MYMED_ROOT ?>/lib/jquery/simpledialog/jquery.mobile.simpledialog.min.css" rel="stylesheet" />
	
	<!-- APP JS -->
	<script src="<?= APP_ROOT ?>/javascript/app.js"></script>
	<!-- APP css -->
	<link href="<?= APP_ROOT ?>/css/style.css" rel="stylesheet" />
	
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
	
	<!-- Share this -->
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "f0ca88be-be8a-427a-983a-f670821d7ad2"}); </script>
			
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


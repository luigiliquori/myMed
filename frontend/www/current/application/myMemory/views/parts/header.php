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
	
	<!-- APP JS -->
	<script src="../../system/javascript/common.js"></script>
	<script src="javascript/app.js"></script>
	
	<!-- APP css -->
	<link href="../../system/css/style.css" rel="stylesheet" />
	
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

<? if (!empty($this->error)): ?>
<div id="mm-error-box" data-role="navbar" data-theme="e" class="ui-bar-e error-box" >
	<h3><?= _("Error") ?></h3>
	<p><?= $this->error ?></p>
	<a href=".error-box" data-icon="delete" data-action="close"><?= _("Close") ?></a>
</div>
<? endif ?>

<? if (!empty($this->success)): ?>
<div id="mm-success-box" data-role="navbar" data-theme="g" class="ui-bar-g success-box">
	<h3><?= _("Message") ?></h3>
	<p><?= $this->success ?></p>
	<a href=".success-box" data-icon="delete" data-action="close"><?= _("Close") ?></a>
</div>
<? endif ?>

<? // Switch to active tab on load
if (!empty($TAB)) :?>
<script type="text/javascript">
	$(document).ready(function() {
		$.mobile.changePage("#<?= $TAB ?>", {transition:"none"})
	});
</script>
<? endif ?>
<? include_once('view-utils.php'); ?>
<!doctype html>
<html lang="fr" <? if (defined("DEMO")) print 'manifest="cache.manifest"' ?>> 

<head> 

	<title><?= empty($TITLE) ? "My Bénévolat" : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!--  Extra icons for jquery 
	<link rel="stylesheet" href="../../lib/jquery/jqm-icon-pack-2.1.2-fa.css" />-->
	
	<!-- JQUERY (Beta)  -->
	<link rel="stylesheet" href="/lib/jquery/jquery.mobile-1.2.0-beta.1.css" />
	<script src="/lib/jquery/jquery-1.8.1.min.js"></script>
	<script src="/lib/jquery/jquery.mobile-1.2.0-beta.1.js"></script>
	
	<!-- MYMED css -->
	<link href="/system/css/common.css" rel="stylesheet" />	
	
	<!-- APP css -->
	<link href="css/style.css" rel="stylesheet" />
	
	<!-- APP JS -->
	<script src="javascript/app.js"></script>
	
	<!-- DateBox -->
	<script src="/lib/jquery/datebox/jqm-datebox-1.1.0.core.min.js"></script>
	<script src="/lib/jquery/datebox/jqm-datebox-1.1.0.comp.datebox.min.js"></script>
	<script src="/lib/jquery/datebox/jquery.mobile.datebox.i18n.fr.utf8.js"></script>
	<link href="/lib/jquery/datebox/jqm-datebox-1.1.0.min.css" rel="stylesheet" />
	
	<!-- Keyfilter  -->
	<script src="/lib/jquery/jquery.keyfilter.js" ></script>
	
	<!--  SimpleDialog -->
	<script src="/lib/jquery/simpledialog/jquery.mobile.simpledialog2.min.js"></script>
	<link href="/lib/jquery/simpledialog/jquery.mobile.simpledialog.min.css" rel="stylesheet" />
	
	<!-- Localization -->
	<script type="text/javascript">
		msg={};
		msg.FORM_ERROR = "<?= _("Il y a des erreurs dans le formulaire, merci de les corriger.") ?>";
	</script>
	
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

<?php 

function tab_bar_main($activeTab, $opts=1) {
	if(!isset($_SESSION['user'])){
		$arrayProfile =array("?action=extendedProfile","Inscription","user");
	}
	else{
		$arrayProfile=array("?action=extendedProfile","Profil","user");
	}
	//it can be useful one day: NICE_BENEVOLAT
	if($_SESSION[EXTENDED_PROFILE]  instanceof ProfileBenevole){ 
		$arrayCandidat = array("?action=candidature","Candidater","user");
	}
	elseif ($_SESSION[EXTENDED_PROFILE]  instanceof ProfileAssociation){
		$arrayCandidat=array("?action=candidature","Déposer une annonce","user");
	}
	else{
		$arrayCandidat = array("?action=candidature","Candidater/Déposer une annonce","user");
	}
	tabs($activeTab, array(
			array("?action=main", "Liste des annonces", "tags"),
			$arrayProfile,
			$arrayCandidat,
			array("?action=about","A propos","user")
	), $opts);
}

define('SEP', '<span style="opacity: 0.7; font-size: 80%;"> &gt; </span>');

function tabs($activeTab, $tabs, $buttonLeft = false) {

	$reverse = true;
	$tabsStr = "";
	foreach ($tabs as $i){
		$tabsStr .=
		'<li><a href="'. $i[0] .'" data-transition="none" data-icon="'. $i[2].'" '.($reverse? 'data-direction="reverse"' : '')
		.($i[0][0]!='#'?'rel="external"':'')
		.($activeTab == $i[0] ? 'class="ui-btn-down-c ui-state-persist"' : '').'>'. _($i[1])
		.'</a></li>';
		if ($i[0] == $activeTab) {
			$reverse = false;
		}
	}
	?>
	<div data-role="header" data-theme="b" data-position="fixed">
  		<? if ($buttonLeft & 1): ?>
			<a href="?action=logout" style="position: absolute; margin-top: -3px; left:5px;" data-role="button" rel="external" data-icon="off" data-iconpos="notext" data-theme="r">Déconnexion</a>
		<? endif ?>
		<? if ($buttonLeft & 2): ?>
			<a data-rel="back" data-icon="arrow-left" style="max-width: 15%;"><?= _("Back") ?></a>
		<? endif ?>
  		<h1>
  			<a href="./" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?> <span style="font-size: 80%;"><?= APPLICATION_LABEL ?></span></a>
  		</h1>
		<span style="position: absolute;right: 3px;top: -3px;opacity: 0.6;">
			<a class="social" style="background-position: -33px 0px;" href="https://plus.google.com/u/0/101253244628163302593/posts" title="myBenevolat on Google+"></a>
			<a class="social" style="background-position: -66px 0px;" href="http://www.facebook.com/pages/myEurope/274577279309326" title="myBenevolat on Facebook"></a>
			<a class="social" style="background-position: 0px 0px;" href="https://twitter.com/my_europe" title="myBenevolat on Twitter"></a>
		</span>
  	</div>
	
	<div data-role="footer" data-theme="d" data-position="fixed">
		<div data-role="navbar">
			<ul><?= $tabsStr ?></ul>
		</div>
	</div>
	<?
}

?>




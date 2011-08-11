<?php 
if(defined('MIMETYPE_XHTML')&&MIMETYPE_XHTML)
	header("Content-Type:application/xhtml+xml; charset=utf-8");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="viewport" content="width=device-width"><!-- for mobile devices-->
		<title>myMed<?php for($i=0 ; $this->getTitle($i, ' > ') ; $i++)?></title>
		<script type="text/javascript">
		//<![CDATA[
			window.isMobileDesign	= function(){ return window.innerWidth<<?=MOBILESWITCH_WIDTH?>;}
		//]]>
		</script>
		<!--bloquer le style pour les vieux IE-->
		<!--[if gt IE 7]><!-->
		<!-- load fonts-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/font.css" />
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/style.css" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/mobile/style.css" media="(max-width: <?=MOBILESWITCH_WIDTH-1?>px)" />
		<!-- define design of website -->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design.css" media="(min-width: <?=MOBILESWITCH_WIDTH?>px)" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/mobile/design.css" media="(max-width: <?=MOBILESWITCH_WIDTH-1?>px)" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design-animation.css" media="(min-width: <?=MOBILESWITCH_WIDTH?>px)" />
		<!-- ><![endif]!-->
		<!--[if IE 8]>
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design.css" media="screen" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design-animation.css" media="screen" />
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> 
		<![endif]-->
		
		<!--JQuery CORE -->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery/dist/jquery.js"></script>
		<!-- JQuery HTML5 compatibility-->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.textPlaceholder.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.form.min.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.form.fr.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.form.config.js"></script>
		
		<!--Mobile's JS-->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/MobileFixedElements.js"></script>
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/jquery.mobilepopup.js"></script>
		
		<!-- JS IE's version'-->
		<!--[if IE]>
		<script type="text/javascript">window.ieVersion=parseFloat(navigator.appVersion.split("MSIE")[1]);</script>
		<![endif]-->
		
<?php 	$this->headTags();?>
	</head>
	<body>
		<script type="text/javascript">document.body.className = "javascript";</script>
<?php 	printError();?>
		<header>
			<div id="header">
				<div class="innerContent">
					<a href="<?=ROOTPATH?>" id="mainTitleLink">
						<h1><img src="<?=ROOTPATH?>style/img/logo-mymed_68x37.png" alt="myMed" /></h1>
						<p>Ensemble par-delà les frontières</p>
					</a>
					<nav>
						<ul><!--
							--><li class="desktop"><a href="<?=ROOTPATH?>application"><span>myApps</span></a></li><!--
							--><li class="store"><a href="<?=ROOTPATH?>application/myStore"><span>myStore</span></a></li><!--
							--><li class="profil"><a href="<?=ROOTPATH?>openid/idpage"><span>myProfile</span></a></li><!--
						--></ul>
					</nav>
					<div class="tools">
						<span class="vertiAligner"></span>
						<form id="language" method="post" action="<?=$_SERVER['REQUEST_URI']?>">
							<div>
								<span class="vertiAligner"></span>
								<select name="language" onchange="this">
									<option value="fr">français</option>
									<option value="en" disabled="disabled">english</option>
									<option value="it" disabled="disabled">italiano</option>
								</select><noscript><button type="submit">ok</button></noscript>
							</div>
						</form>
<?php if(USER_CONNECTED):?>
						<form id="logout" method="post" action="<?=$_SERVER['REQUEST_URI']?>">
							<div>
								<button type="submit" name="logout">Déconnexion</button>
							</div>
						</form>
<?php else :?>
						<div id="login">
							<span class="vertiAligner"></span>
<?php 	global $PATH_INFO;
		if($PATH_INFO[0] !== 'home'):?>
							<a class="label" href="<?=ROOTPATH?>">Inscription</a>
<?php 	endif;?>
							<span class="label" onclick="$('#login div.list')[0].open()">Connexion</span>
							<div class="list mobile_popup">
								<div class="mobile_popup_close" onclick="$('#login div.list')[0].close()"></div>
<?php							$this->connexions->button();?>
							</div>
						</div>
<?php endif;?>
					</div>
				</div>
			</div>
		</header>
		<div id="titlesContentContainer" class="mobile_open">
			<div id="titles">
				<div class="innerContent">
					<h1><?php $this->getTitle();?></h1>
				</div>
			</div>
			<div id="content">
<?php			$this->content();?>
			</div>
			<footer id="footer">
				<div class="innerContent">
					<nav>
						<ul>
							<li><a href="http://www.mymed.fr">Blog</a></li>
							<li><a href="<?=ROOTPATH?>doxygen/">Développeurs</a></li>
							<li><a href="javascript:alert('Non disponible')">Conditions d'utilisation</a></li>
							<li><a href="javascript:alert('Non disponible')">Aide</a></li>
						</ul>
					</nav>
					<details>
						<summary>Liste des fianceurs</summary>
						<ul>
							<li><a href="http://www.cg06.fr" title="Conseil Général de Alpes Maritimes"><img src="<?=ROOTPATH?>style/img/financeurs/cg06.png" alt="Conseil Général de Alpes Maritimes" /></a></li>
							<li><a href="http://www.interreg-alcotra.org" title="Union Européenne"><img src="<?=ROOTPATH?>style/img/financeurs/europe.png" alt="Union Européenne" /></a></li>
							<li><a href="http://www.inria.fr" title="Institut National de Recherche en Informatique et Automatique"><img src="<?=ROOTPATH?>style/img/financeurs/inria.png" alt="INRIA" /></a></li>
							<li><a href="http://www.regionpaca.fr" title="Provence Alpes Côte d'Azur"><img src="<?=ROOTPATH?>style/img/financeurs/PACA.png" alt="PACA" /></a></li>
							<li><a href="http://www.alpes-maritimes.pref.gouv.fr" title="Préfecture des Alpes Maritimes"><img src="<?=ROOTPATH?>style/img/financeurs/pref.png" alt="Préfecture des Alpes Maritimes" /></a></li>
							<li><a href="http://www.regione.piemonte.it" title="Région Piemontaise"><img src="<?=ROOTPATH?>style/img/financeurs/regione.png" alt="Regione Piemonte" /></a></li>
						</ul>
					</details>
				</div>
			</footer>
		</div>

<?php	$this->scriptTags();?>

		<!-- JQuery HTML5 compatibility's initialization'-->
		<script type="text/javascript">
		$("[placeholder]").textPlaceholder();
		$(":date").dateinput();
		$(".mobile_popup").mobilePopup($("#titlesContentContainer")[0]);
		//window.mobileFixedElements.addElement(document.getElementById("login").getElementsByTagName("div")[0]);
		window.mobileFixedElements.addElement(document.getElementById("header").getElementsByTagName("ul")[0]);
		</script>
		
		<!-- DEBUG -->
<?php 	if(defined('DEBUG')&&DEBUG):?>
		<div id="debug">
			<a href="?debug=0" class="close">close</a>
			<?php printTraces();?>
		</div>
<?php 	endif;?>
	</body>
</html>

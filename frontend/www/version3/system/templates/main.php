<?php 
if(defined('MIMETYPE_XHTML')&&MIMETYPE_XHTML)
	header("Content-Type:application/xhtml+xml; charset=utf-8");
else
	header("Content-Type:text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0" />
		<link rel="apple-touch-icon" href="<?=ROOTPATH?>apple-touch-icon.png" />
		<link rel="apple-touch-icon-precomposed" href="<?=ROOTPATH?>apple-touch-icon-precomposed.png" />
		<!--[if !IE]><--><link rel="icon" type="image/x-icon" href="<?=ROOTPATH?>favicon.ico" /><!--><![endif]-->
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="<?=ROOTPATH?>favicon.ico" /><![endif]-->
		<title>myMed<?php for($i=0 ; $this->getTitle($i, ' > ') ; $i++)?></title>
		<script type="text/vbscript">
		Const ROOTPATH = "<?=ROOTPATH?>"
		</script>
		<script type="text/javascript">
		if (typeof ROOTPATH == 'undefined')
			const ROOTPATH = "<?=ROOTPATH?>";
		</script>
		<script type="text/javascript">
		//<![CDATA[
			window.isMobileDesign	= function(){ return window.innerWidth<<?=MOBILESWITCH_WIDTH?>;};
			window.isMaxWidthMobileDesign	= function(){ return window.screen.width<<?=MOBILESWITCH_WIDTH?>;};
		//]]>
		</script>
		<!--bloquer le style pour les vieux IE-->
		<!--[if gt IE 7]><!-->
		<!-- load fonts-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/font.min.css" media="all"/>
		<!-- define styles of type of elements (ex:h1, p, p.myclass...)-->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/style.min.css" media="all"/>
		<link rel="stylesheet" href="<?=ROOTPATH?>style/mobile/style.min.css" media="only screen and (max-width: <?=MOBILESWITCH_WIDTH-1?>px)" />
		<!-- define design of website -->
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design.min.css" media="only screen and (min-width: <?=MOBILESWITCH_WIDTH?>px)" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/mobile/design.min.css" media="only screen and (max-width: <?=MOBILESWITCH_WIDTH-1?>px)" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design-animation.min.css" media="only screen and (min-width: <?=MOBILESWITCH_WIDTH?>px)" />
		<!-- ><![endif]!-->
		<!--[if IE 8]>
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design.min.css" media="screen" />
		<link rel="stylesheet" href="<?=ROOTPATH?>style/desktop/design-animation.min.css" media="screen" />
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> 
		<![endif]-->
		
		<!--JQuery CORE -->																  <!-- JQuery HTML5 compatibility-->												   <!--Mobile's JS-->
		<script type="text/javascript" src="<?=ROOTPATH?>javascript/loader.js.php?f=jquery/dist/jquery,jquery.textPlaceholder,jquery.form,jquery.form.fr,jquery.form.config,MobileFixedElements,jquery.mobilepopup"></script>
		
		<!-- JS IE's version'-->
		<!--[if IE]>
		<script type="text/javascript">window.ieVersion=parseFloat(navigator.appVersion.split("MSIE")[1]);</script>
		<![endif]-->
		
<?php 	$this->headTags();?>
	</head>
	<body class="noscript">
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
							--><li class="profil"><a href="<?=ROOTPATH?>profil"><span>myProfile</span></a></li><!--
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
								<div class="mobile_popup_close" onclick="this.parentNode.close()"></div>
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
		</div>
		<footer id="footer">
			<div class="innerContent">
				<div id="navigation">
					<span class="label" onclick="$('#navigation nav.list')[0].open()"></span>
					<nav class="list mobile_popup">
						<div class="mobile_popup_close" onclick="this.parentNode.close()"></div>
						<ul>
							<li><a href="http://www.mymed.fr">Blog</a></li>
							<li><a href="<?=ROOTPATH?>doxygen/">Développeurs</a></li>
							<li><a href="#" onclick="alert('Non disponible');return false;">Conditions d'utilisation</a></li>
							<li><a href="#" onclick="alert('Non disponible');return false;">Aide</a></li>
						</ul>
					</nav>
				</div>
				<div id="partnerList">
					<span class="label" onclick="$('#partnerList div.list')[0].open()">Liste des financeurs</span>
					<div class="list mobile_popup">
						<div class="mobile_popup_close" onclick="this.parentNode.close()"></div>
						<ul>
							<li><a href="http://www.cg06.fr" title="Conseil Général de Alpes Maritimes"><img src="<?=ROOTPATH?>style/img/financeurs/cg06.png" alt="Conseil Général de Alpes Maritimes" /></a></li>
							<li><a href="http://www.interreg-alcotra.org" title="Union Européenne"><img src="<?=ROOTPATH?>style/img/financeurs/europe.png" alt="Union Européenne" /></a></li>
							<li><a href="http://www.inria.fr" title="Institut National de Recherche en Informatique et Automatique"><img src="<?=ROOTPATH?>style/img/financeurs/inria.png" alt="INRIA" /></a></li>
							<li><a href="http://www.regionpaca.fr" title="Provence Alpes Côte d'Azur"><img src="<?=ROOTPATH?>style/img/financeurs/PACA.png" alt="PACA" /></a></li>
							<li><a href="http://www.alpes-maritimes.pref.gouv.fr" title="Préfecture des Alpes Maritimes"><img src="<?=ROOTPATH?>style/img/financeurs/pref.png" alt="Préfecture des Alpes Maritimes" /></a></li>
							<li><a href="http://www.regione.piemonte.it" title="Région Piemontaise"><img src="<?=ROOTPATH?>style/img/financeurs/regione.png" alt="Regione Piemonte" /></a></li>
						</ul>
					</div>
				</div>
			</div>
		</footer>

<?php	$this->scriptTags();?>

		<!-- JQuery HTML5 compatibility's initialization'-->
		<script type="text/javascript">
		//<![CDATA[
		$("[placeholder]").textPlaceholder();
		$(":date").dateinput();
		$(".mobile_popup").mobilePopup($("#titlesContentContainer")[0]);
		//window.mobileFixedElements.addElement(document.getElementById("login").getElementsByTagName("div")[0]);
		window.scrollTo(0, 1);
		window.mobileFixedElements.addElement(document.getElementById("header").getElementsByTagName("ul")[0]);
		(function($){
			function refreshSize()
			{
				var menu	= $("#header nav ul")[0];
				if(window.isMobileDesign()&&(window.innerWidth < window.innerHeight))
				{
					menu.style.bottom	= "auto";
					menu.style.top		= (window.innerHeight-menu.offsetHeight)+"px";
				}
				else
				{
					menu.style.bottom	= "";
					menu.style.top		= "";
				}
			}
			$(window).bind("resize", refreshSize);
			refreshSize();
		})(jQuery);
		//]]>
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

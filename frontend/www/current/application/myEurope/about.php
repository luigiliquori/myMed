
<?php
require_once 'Template.php';
Template::init();
?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>
<body>
<div id="About" data-role="page">
	<div data-role="popup" id="popupShare2" data-overlay-theme="b" data-theme="c" class="ui-corner-all">
		<div style="text-align:center;">
			<span class="st_googleplus_large"></span>
			<span class="st_facebook_large"></span>
			<span class="st_twitter_large"></span>
			<span class="st_sharethis_large"></span>
			<span class="st_email_large"></span>
		</div>
	</div>
	<div data-role="header" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="d" data-iconpos="left">
			<ul>
				<li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" rel="external" data-icon="delete" data-iconpos="notext">myMed</a></li>
				<li><a href="about" data-icon="info" class="ui-btn-active ui-state-persist"><?= _('About') ?></a></li>
				<li><a href="home"  data-icon="home" data-transition="slidefade"><?= _('Home') ?></a></li>
				<li><a href="#popupShare2" data-icon="star" data-rel="popup">Partager</a></li>
				<li><a href="option" data-icon="profile" data-transition="slidefade"><?= _('Profil') ?></a></li>
			</ul>
		</div>
	</div>
	<?php 
	if ($_SESSION['profile']->permission > 1){
	?>
	<div data-role="footer" data-theme="c" data-position="fixed">
		<div data-role="navbar" data-theme="c" data-iconpos="left">
			<ul>
				<li><a href="admin" data-icon="gear" rel="external" data-theme="d" data-transition="slidefade">Admin</a></li>
			</ul>
		</div>
	</div>
	<?php 
	}
	?>
	
	<!-- CONTENT -->
	<div data-role="content">
		<h1 style="text-align:center;">
			<a href="./" style="text-decoration: none;"><?= Template::APPLICATION_NAME ?></a>
		</h1>

		<p>
		<?= _("AboutContent") ?>
		</p>
		<p style="text-align:center;">
			<a href="mailto:mymeddev@gmail.com" data-role="button" data-inline="true" data-mini="true">contact</a>
		</p>
		<div class="footer" >
			<h4  style="margin: 10px;">myMed - INTERREG IV - Alcotra</h4>
			<img alt="Alcotra" src="/system/img/logos/alcotra" />
			<img alt="Conseil Général 06" src="/system/img/logos/cg06" />
			<img alt="Regine Piemonte" src="/system/img/logos/regione"/>
			<img alt="Europe" src="/system/img/logos/europe" />
			<img alt="Région PACA" src="/system/img/logos/PACA" />
			<img alt="Prefecture 06" src="/system/img/logos/pref" />
			<img alt="Inria" src="/system/img/logos/inria.png" />
			<p style="margin: 8px; font-weight: normal;">"Ensemble par-delà les frontières"</p>
		</div>
	</div>

</div>	

</body>
</html>

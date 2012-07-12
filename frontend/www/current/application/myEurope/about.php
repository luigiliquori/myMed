
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
<!-- HEADER -->
	<div id="header" data-role="header" data-theme="c" style="max-height: 38px;">
		<a data-icon="back" data-rel="back">Retour</a>
		<h2>
			<a href="" style="text-decoration: none;"><?= _('About') ?></a>
		</h2>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
	<h1 style="text-align: center;"><?= Template::APPLICATION_NAME ?></h1>
		<p>
		<?= _("AboutContent") ?>
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
	<?= Template::footer(0); ?>
</div>	

</body>
</html>

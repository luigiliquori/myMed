<div id="roadMap" data-role="page">

	<!-- Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="" data-rel="back" data-icon="arrow-l"/><?= _("Back")?></a>
		<h1><?php echo APPLICATION_NAME." v1.0 alpha"?></h1>
		<a href="?action=main#search" data-transition="none" data-icon="search">Rechercher</a>
	</div>
	
	<!-- CONTENT -->
	<div data-role="content" style="padding: 0px; margin-top: 0px;">
	
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c">
			</ul>
		</div>
		<a id="ceparou06" style="right: 20px;z-index: 10;" href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="<?= MYMED_URL_ROOT ?>system/img/logos/ceparou06.png" style="width:80px;" /></a>
	</div>
	
</div>
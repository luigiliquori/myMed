<div id="roadMap" data-role="page">

	<!-- HEADER BAR-->
	<div data-role="header" data-theme="b" data-position="fixed">
		<h1><?= _("Get directions details")?></h1>
		<a href="?action=localise#searchRoad" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
		<br>
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c"></ul>
		</div>
		<a id="ceparou06" style="bottom: -40px;right: 20px;z-index: 10;" href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="<?= MYMED_URL_ROOT ?>system/img/logos/ceparou06.png" style="width:80px;" /></a>
	</div>

</div>
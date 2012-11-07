<div id="roadMap" data-role="page">

<? include("header-bar.php"); ?>

	<!-- CONTENT -->
	<div data-role="content" style="padding: 0px;margin-top: 38px;">
	
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c">
			</ul>
		</div>
		<a id="ceparou06" style="bottom: -40px;right: 20px;z-index: 10;" href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="<?= MYMED_URL_ROOT ?>system/img/logos/ceparou06.png" style="width:80px;" /></a>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="?action=main#Map" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist">Carte</a></li>
				<li><a href="?action=main#search" data-transition="none" data-icon="search">Rechercher</a></li>
				<li><a href="?action=option" data-transition="none" data-icon="gear">Option</a></li>
			</ul>
		</div>
	</div>

</div>
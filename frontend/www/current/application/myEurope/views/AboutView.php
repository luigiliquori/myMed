<? require_once('notifications.php'); ?>

<div data-role="page">
 <? $title = _("Credits");
	print_header_bar(true, false, $title); ?>
	
	<div data-role="content" class="content">
		<!-- <a href="/application/myMed/" rel="external"><img alt="myMed" src="/application/myMed/img/logo-mymed-250c.png" style="margin-top: -15px;"/></a>-->
		<img alt="<?= APPLICATION_NAME ?>" src="img/icon.png" style="height: 50px; margin-top: -15px;" />
		<h1 style="display: inline-block;vertical-align: 20%;"><?= APPLICATION_NAME ?></h1>
		<!-- <h3 style="margin-top: -5px;"><?= _(APPLICATION_LABEL) ?></h3>-->
		<br>
		<div data-role="collapsible" data-theme="d" data-collapsed="false" data-content-theme="d">
		<!-- <ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">-->
			<!-- <li>-->
			<!-- <p>-->
			<?= _("<b>MyEurope</b> is a social network which is based on the meta-social network <b><em>myMed</em></b>, available for City Halls, institutions or economic realities (industrial, tourism industry...) of the French South-East areas (PACA, Rhone-Alpes) and the three Italian North-Western Regions (Liguria, Piemonte, Valle d'Aosta), i.e. the areas eligible to the Alcotra Program.") ?>
			<?= _("This application will help the City Hall of the Alps-Mediterranean Euroregion to find partners, among those who joined the social network, in order to create projects together, within European Programs.\n The main targets of <b><em>myMed</em></b> are :\n \t<ul>\n \t\t<li>Help, through the mechanism of myMed's \"matchmaking\", to gather ideas and resources for European project submission or obtain European funds.</li>\n \t\t<li>Exchange practices and common cross-border interests in the area of European project creation.</li>\n \t\t<li>Inform users about different European calls.</li>\n \t</ul>") ?>
			<!-- </p>-->
			<!-- </li>-->
		<!--  </ul>-->
		<p>
		<a href="http://www-sop.inria.fr/teams/lognet/MYMED/" target="_blank"><?= _("More informations") ?></a>
		</p>
		<p>
			<?= _("If you find an error you can report it at this address: ")?><a href="http://mymed22.sophia.inria.fr/bugreport/index.php" target="_blank"> http://mymed22.sophia.inria.fr/bugreport/index.php </a>
		</p>
		
		<br>
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		</div>
	</div>
</div>

<? include("header.php"); ?>
<? include("notifications.php")?>
<script>
getLocation();
</script>

<div id="RoadSheet" data-role="page">

	<div data-role="header" data-position="inline">
		<a href="?action=GoingBack" data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?> </a>
		<h1><?= _("RoadSheet"); ?></h1>
		<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?> </a>
	</div>
	
	<div data-role="content" class="ui-content" role="main">
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c"></ul>
			<a id="ceparou06"
				style="position: absolute; bottom: -40px; right: 20px; z-index: 10;"
				href="http://www.ceparou06.fr/"><img alt="ceparou 06"
				src="img/logos/ceparou06.png"
				style="max-height: 35px; max-width: 100px;" /> </a>
		</div>
	</div>
	


	<!-- Footer -->
	<div data-role="footer" data-id="myFooter" data-position="fixed">
		<a href="?action=ExtendedProfile" data-role="button" data-theme="b" data-icon="profile"><?= _("Profile"); ?></a>
	</div>

</div>	
<? include("footer.php"); ?>
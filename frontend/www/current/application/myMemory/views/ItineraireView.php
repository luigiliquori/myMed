<? include("header.php"); ?>
<? include("notifications.php")?>
<div id="RoadSheet" data-role="page">
<!-- 	<div data-role="header" data-theme="a"> -->
<!-- 		<h1>Feuille de route</h1> -->
<!-- 		<a href="?action=main" data-role="button" class="ui-btn-left" data-icon="back" >Back</a> -->
<!-- 	</div> -->
	<div data-role="content" class="ui-content" role="main">
		<div id="itineraire">
			<ul id="itineraireContent" data-role="listview" data-theme="c" ></ul>
			<a id="ceparou06" style="position: absolute;bottom: -40px;right: 20px;z-index: 10;" href="http://www.ceparou06.fr/"><img alt="ceparou 06" src="img/logos/ceparou06.png" style="max-height:35px;max-width:100px;" /></a>
		</div>
	</div>
</div>

<div data-role="footer" data-position="fixed">
	<div data-role="navbar" data-iconpos="top" >
		<ul>
			<li><a href="?action=GoingBack" data-icon="back" ><?= _('Back'); ?></a></li>
			<li><a href="?action=ExtendedProfile" data-icon="profile" ><?= _('Profile'); ?></a></li>
			
		</ul>
	</div>
</div>
<? include("footer.php"); ?>
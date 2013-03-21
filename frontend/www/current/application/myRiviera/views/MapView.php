
<div id="Map" data-role="page" class="page-map">

	<!-- Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="?action=main" data-icon="arrow-l" data-ajax="false"/><?= _("back")?></a>
		<h1><?php echo APPLICATION_NAME." v1.0 alpha"?></h1>
	</div>

	<!-- CONTENT -->
	<div data-role="content" style="padding: 0px; margin-top: 0px;">
		
		<!-- MAP -->
		<div id="myRivieraMap"></div>
		
		<div id="steps" data-role="controlgroup" data-type="horizontal" >
			<a id="prev-step" data-role="button" data-icon="arrow-l" title="précédent"></a>
			<a href="#roadMap" data-role="button">Détails</a>
			<a id="next-step" data-role="button" data-icon="arrow-r" data-iconpos="right" title="suivant"></a>
		</div>

	</div>

	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar" data-iconpos="left" >
			<ul>
				<li><a href="?action=main#search" data-transition="none" data-icon="search">New search</a></li>
			</ul>
		</div>
	</div>
</div>
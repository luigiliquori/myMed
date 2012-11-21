<? include("header.php"); ?>

<div id="Map" data-role="page" class="page-map">

	<? include("header-bar.php"); ?>

	<!-- CONTENT -->
	<div data-role="content" class="content-map" style="padding: 0px; margin-top: -20px;">
		
		<!-- MAP -->
		<div id="myRivieraMap"></div>
		
		<div id="steps" data-role="controlgroup" data-type="horizontal" >
			<a id="prev-step" data-role="button" data-icon="arrow-l" title="précédent"></a>
			<a href="#roadMap" data-role="button">Détails</a>
			<a id="next-step" data-role="button" data-icon="arrow-r" data-iconpos="right" title="suivant"></a>
		</div>

	</div>
	
<? include("footer.php"); ?>
</div>
</body>
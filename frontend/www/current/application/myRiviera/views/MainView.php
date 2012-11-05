<? include("header.php"); ?>

<div id="Map" data-role="page" class="page-map">

	<? include("header-bar.php"); ?>

	<!-- CONTENT -->
	<div data-role="content" style="padding: 0px; margin-top: -20px;">
		
		<!-- MAP -->
		<div id="myRivieraMap"></div>
		
		<div id="steps" data-role="controlgroup" data-type="horizontal" >
			<a id="prev-step" data-role="button" data-icon="arrow-l" title="précédent"></a>
			<a href="#roadMap" data-role="button">Détails</a>
			<a id="next-step" data-role="button" data-icon="arrow-r" data-iconpos="right" title="suivant"></a>
		</div>

	</div>
	
	<div data-role="footer" style="position:absolute;bottom:0;width:100%" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="?action=main#Map" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist">Carte</a></li>
				<li><a href="?action=main#search" data-transition="none" data-icon="search">Rechercher</a></li>
				<li><a href="?action=option" data-transition="none" data-icon="gear">Option</a></li>
			</ul>
		</div>
	</div>

</div>

<?  include("SearchView.php"); ?>
<? 
/* roadMap  */ include("DetailsView.php"); 
?>

<? include("footer.php"); ?>
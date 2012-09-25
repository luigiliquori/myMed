<? include("header.php"); ?>

<div id="Map" data-role="page">

<? include("header-bar.php"); ?>

	<!-- CONTENT -->
	<div data-role="content" style="padding: 0px;">
	
		<!-- MAP -->
		<div id="<?= APPLICATION_NAME ?>Map"></div>
	
		<script type="text/javascript">var mobile = '<?php echo TARGET ?>';</script>
	
		<div id="steps" data-role="controlgroup" data-type="horizontal">
			<a id="prev-step" data-role="button" data-icon="arrow-l" style="opacity:.8;">&nbsp;</a>
			<a href="#roadMap" data-role="button" style="opacity:.8;">Détails</a>
			<a id="next-step" data-role="button" data-iconpos="right" data-icon="arrow-r"  style="opacity:.8;">&nbsp;</a>
		</div>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#Map" data-transition="none" data-back="true" data-icon="home" class="ui-btn-active ui-state-persist">Carte</a></li>
				<li><a href="#search" data-transition="none" data-icon="search">Rechercher</a></li>
				<li><a href="#option" data-transition="none" data-icon="gear">Option</a></li>
			</ul>
		</div>
	</div>

</div>

<? include("OptionView.php"); ?>
<? include("SearchView.php"); ?>
<? include("DetailsView.php"); ?>

<? include("footer.php"); ?>
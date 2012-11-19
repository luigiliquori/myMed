<? include("header.php"); ?>
<div data-role="page" id="Search" data-theme="b">
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
	
<? include("footer.php"); ?>
</div>
</body>
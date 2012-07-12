<? include("header.php"); ?>
<? include("notifications.php")?>

<div data-role="page" id="NeedHelp">
<script type="text/javascript">
	$("#NeedHelp").live('pageinit', function() {
		initialize_map();

	});
</script>


	<div class="ui-bar">
		<a href="?action=StopEmergency" data-rel="dialog" data-transition="pop" data-role="button" data-inline="false" style="width:100%;" data-theme="r" data-icon="delete">Stop Au Secours!</a>
	</div>
	<div data-role="content" data-theme="a">
		<div id="myMap"></div>
		<br />
		<div>
			<p>MyMemory appelle en main-libre les personnes suivante :</p>
			<ol data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-inset="true">
				<?php foreach($_SESSION['ExtendedProfile']->callingList as $entry) {?>
				<li class="ui-li ui-li-static ui-body-c"><?= $entry["name"]; ?></li>
				<?php }?>
			</ol>
			<p>Parlez devant le micro SVP!</p>
		</div>
	</div>
<? include("footer.php"); ?>	
</div>

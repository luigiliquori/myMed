<? include("header.php"); ?>
<? include("notifications.php")?>

<div data-role="page" id="NeedHelp">
<script type="text/javascript">
	$("#NeedHelp").live('pageinit', function() {
		initialize_map();

	});

	setTimeout(function() {needHelp();},2000);
</script>


	<div class="ui-bar">
		<a href="?action=StopEmergency" data-rel="dialog" data-transition="pop" data-role="button" data-inline="false" style="width:100%;" data-theme="r" data-icon="delete">Stop Au Secours!</a>
	</div>
	<div data-role="content" data-theme="a">
		<input type="hidden" id="username" value="<?=$_SESSION['user']->name; ?>" />
		<input type="hidden" id="howmany" value="<?= sizeof($_SESSION['ExtendedProfile']->callingList); ?>" />
		<input type="hidden" id="current_street" value="32 Rue Jean Jaurès, Cannes, France" />
		<input type="hidden" id="current_lat" value="43.55353" />
		<input type="hidden" id="current_lng" value="7.02199" />
		<div id="myMap"></div>
		<br />
		<div>
			<p>MyMemory appelle en main-libre les personnes suivante :</p>
			<ul data-role="listview" data-inset="true" data-theme="c">
				<?php 
				$i = 1;
				foreach($_SESSION['ExtendedProfile']->callingList as $entry) {
					echo '<li id="line'.$i.'">';
					echo '<h3>'.$i.' - '.$entry["name"] .'</h3>';
					echo '<p id="num'.$i.'">'.$entry['phone'].'</li>'; 
					$i++;
				}?>
			</ul>
			<p>Parlez devant le micro SVP!</p>
		</div>
	</div>
<? include("footer.php"); ?>	
</div>

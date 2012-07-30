<? include("header.php"); ?>
<? include("notifications.php")?>

<div data-role="page" id="NeedHelp">
<script type="text/javascript">
	$("#NeedHelp").live('pageinit', function() {
		initialize_map();

	});

	<?php
	if ($_SESSION['isMobile']){
		
		$str_numbers = '';
		// Récupération des numéros a appeler
		$k = count($_SESSION['ExtendedProfile']->callingList);
		for($i=0; $i < $k; $i++) {
			$str_numbers .= $_SESSION['ExtendedProfile']->callingList[$i]['phone'];
			if ($i != $k-1) $str_numbers .= '::';
		}	
		
		
		
		echo 'setTimeout(function() {location.href="/application/'.APPLICATION_NAME.'/index.php?action=callEnded&mobile_binary::call::'.$str_numbers.'";},5000);';
	}	
	else
		echo 'setTimeout(function() {sendEmailsAlerts();},2000);';
	
	
	
	
	
	
	?>

</script>


	<div class="ui-bar">
		<a href="?action=StopEmergency" data-rel="dialog" data-transition="pop" data-role="button" data-inline="false" style="width:100%;" data-theme="r" data-icon="delete">Stop Au Secours!</a>
	</div>
	<div data-role="content" data-theme="a">
		<input type="hidden" id="username" value="<?=$_SESSION['user']->name; ?>" />
		<input type="hidden" id="howmany" value="<?= sizeof($_SESSION['ExtendedProfile']->callingList); ?>" />
		<div id="myMap"></div>
		<br />
		<div>
		<?php
		/*
		 * In case of mobile device.
		 */
		if ($_SESSION['isMobile']) {?>
			<p>MyMemory appelle en main-libre les personnes suivante :</p>
			<ul data-role="listview" data-inset="true" data-theme="c">
				<?php 
				$i = 1;
				foreach($_SESSION['ExtendedProfile']->callingList as $entry) {
					echo '<li id="line'.$i.'">';
					echo '<a id="call'.$i.'"href="index.php?action=callEnded&mobile_binary::call" >';
					echo '<h3>'.$i.' - '.$entry["name"] .'</h3>';
					echo '<p id="num'.$i.'">'.$entry['phone'].'</p>';
					echo '</a>';
					echo '</li>'; 
					$i++;
				}?>
			</ul>
			<p>Parlez devant le micro SVP!</p>
		<?php }
		/*
		 * In case of desktop
		 */
		else {?>
		
			<p>MyMemory envoie une alerte par e-mail aux personnes suivantes :</p>
			<ul data-role="listview" data-inset="true" data-theme="c">
			<?php
			$i = 1;
			foreach($_SESSION['ExtendedProfile']->callingList as $entry) {
				echo '<li id="line'.$i.'">';
				echo '<h3>'.$i.' - '.$entry["name"] .'</h3>';
				echo '<p id="mail'.$i.'">'.$entry['email'].'</p>';
				echo '</li>';
				$i++;
			}?>
			</ul>
			<p>Les alertes sont envoyées. Restez où vous êtes!</p>

		
		<?php 	
		}
		?>
		</div>
	</div>
<? include("footer.php"); ?>	
</div>

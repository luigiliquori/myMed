<? include("header.php"); ?>
<? include("notifications.php")?>

<div data-role="page" id="NeedHelp">

<script type="text/javascript">
	<?php
	if ($_SESSION['isMobile']){
		
		$str_numbers = '';
		// Récupération des numéros a appeler
		$k = count($_SESSION['ExtendedProfile']->callingList);
		for($i=0; $i < $k; $i++) {
			$str_numbers .= $_SESSION['ExtendedProfile']->callingList[$i]->phone;
			if ($i != $k-1) $str_numbers .= '::';
		}	
		echo 'setTimeout(function() {location.href="/application/'.APPLICATION_NAME.'/index.php?action=main&mobile_binary::call::'.$str_numbers.'";},5000);';
	}	
	else
		echo 'setTimeout(function() {sendEmailsAlerts();},2000);';
	?>

</script>

	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("NeedHelp"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>	
	
	
	<div data-role="content" data-theme="a">
	
		<div class="description-box">
			<?= _("MyMemory_NeedHelpDesc") ?>
		</div>
	
		<a href="?action=StopEmergency" data-rel="dialog" data-transition="pop" data-role="button" class="mymed-big-button" data-inline="false" data-theme="r" data-icon="delete"><?= _('StopNeedHelp'); ?></a>
		
		
		
		<input type="hidden" id="username" value="<?=$_SESSION['user']->name; ?>" />
		<input type="hidden" id="howmany" value="<?
		if ($_SESSION['isMobile'])
			echo sizeof($_SESSION['ExtendedProfile']->callingList);
		else
			echo sizeof($_SESSION['ExtendedProfile']->callingList) -1; ?>" />

		<div>
		<?php
		/*
		 * In case of mobile device.
		 */
		if ($_SESSION['isMobile']) {?>
			<p><?= _("MyMemory_Calling"); ?></p>
			<ul data-role="listview" data-inset="true" data-theme="c">
				<?php 
				$i = 1;
				foreach($_SESSION['ExtendedProfile']->callingList as $entry) {
					echo '<li id="line'.$i.'">';
					echo '<a id="call'.$i.'"href="index.php?action=callEnded&mobile_binary::call" >';
					echo '<h3>'.$i.' - '.$entry->nickname .'</h3>';
					echo '<p id="num'.$i.'">'.$entry->phone.'</p>';
					echo '</a>';
					echo '</li>'; 
					$i++;
				}?>
			</ul>
			<p><?= _("MyMemory_Microphone"); ?></p>
		<?php }
		/*
		 * In case of desktop
		 */
		else {?>
		
			<p><?= _("MyMemory_Mailling"); ?></p>
			<ul data-role="listview" data-inset="true" data-theme="c">
			<?php
			$i = 1;
			foreach($_SESSION['ExtendedProfile']->callingList as $entry) {
				if ($entry->nickname == "Emergency") continue;
				echo '<li id="line'.$i.'">';
				echo '<h3>'.$i.' - '.$entry->nickname .'</h3>';
				echo '<p id="mail'.$i.'">'.$entry->email.'</p>';
				echo '</li>';
				$i++;
			}?>
			</ul>
			<p><?= _("MyMemory_MailSent"); ?></p>		
		<?php 	
		}
		?>
		</div>
	</div>
	
</div>
<? include("footer.php"); ?>	
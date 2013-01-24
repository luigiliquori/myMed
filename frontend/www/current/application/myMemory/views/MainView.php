<? include("notifications.php")?>

<div data-role="page" id="MainView">

	<?php
	$_SESSION['autocall_active'] = true;
	

			echo '<script type="text/javascript">';
			echo 'setTimeout(function() {location.href="/application/'.APPLICATION_NAME.'/index.php?action=main&mobile_binary::guardian::'.$guardian_params.'";},5000);';
			echo '</script>';

	?>



	<!-- Header -->
	<div data-role="header" data-position="inline">
		<a href="../../index.php" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _("Exit"); ?></a>
	 	<h1><?= _("Home"); ?></h1>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>
	
	<div class="description-box">
	<?= _("MyMemory_HomeDesc") ?>
	</div>
	
	<div>
		<a href="?action=GoingBack" rel="external" data-role="button" data-theme="a" class="mymed-big-button" data-icon="home"><?= _("GoingBack"); ?></a>
		<a href="?action=NeedHelp" rel="external" data-role="button" data-theme="r" class="mymed-big-button" data-icon="alert"><?= _("NeedHelp"); ?></a>
	</div>
		<a href="?action=ExtendedProfile" data-role="button" data-theme="b" data-icon="profile"><?= _("Profile"); ?></a>
		<?php 
		if ($_SESSION['autocall_active'] && $_SESSION['isMobile']){
			echo '<span data-role="button" data-theme="e">Surveillance activée</span>';
		}
		?>
</div>
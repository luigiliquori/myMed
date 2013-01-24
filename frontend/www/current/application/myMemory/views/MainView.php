<? include("notifications.php")?>

<div data-role="page" id="NeedHelp">

<script type="text/javascript">
	<?php
	
	if(($_SESSION['ExtendedProfile']->diseaseLevel == 3) && ($_SESSION['isMobile']) && ($_SESSION['autocall_active'] == false)){
		
		/*
		 * WE ACTIVATE AUTOCALL
		 */
		
		/*
		 * URL SCHEMA :
		*
		* 0 : mobile_binary
		* 1 : guardian
		* 2 : person name		(string)
		* 3 : home 				(string)
		* 5 : perimetre_home	(float)
		* 6 : perimetre_nearby (float)
		* 7 : perimetre_far	(float)
		* 8 : tel_buddy1		(string)	usually caregiver phone
		* 9 : tel_buddy2		(string)	usually doctor
		* 10 : tel_buddy3		(string)	usually samu
		* 11: interval			(int)		in minutes
		*/
		
		$guardian_params = '';
		
		// person name
		$guardian_params .= $_SESSION['user']->name."::";
		// home
		$guardian_params .= $_SESSION['ExtendedProfile']->home."::";
		// perimeter home
		$guardian_params .= $_SESSION['ExtendedProfile']->perimeter_home."::";
		// perimeter nearby
		$guardian_params .= $_SESSION['ExtendedProfile']->perimeter_nearby."::";
		// perimeter far
		$guardian_params .= $_SESSION['ExtendedProfile']->perimeter_far."::";
		// buddy 1
		$guardian_params .= $_SESSION['ExtendedProfile']->callingListAutoCall['0']."::";
		// buddy 2
		$guardian_params .= $_SESSION['ExtendedProfile']->callingListAutoCall['1']."::";
		// buddy 3
		$guardian_params .= $_SESSION['ExtendedProfile']->callingListAutoCall['2']."::";
		// interval
		$guardian_params .= $_SESSION['ExtendedProfile']->autocall_frequency;
		
		$_SESSION['autocall_active'] = true;
		
		echo 'setTimeout(function() {location.href="/application/'.APPLICATION_NAME.'/index.php?action=main&mobile_binary::guardian::'.$guardian_params.'";},5000);';
	}
	?>

</script>

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
		if ($_SESSION['autocall_active'])
			echo '<span data-role="button" data-theme="e">Surveillance activée</span>';
		?>
</div>
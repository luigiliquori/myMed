<!-- ------------------ -->
<!-- App Main View      -->
<!-- ------------------ -->


<!-- Page view -->
<div data-role="page" id="mainView" >


	<!-- Header bar -->
	<? print_header_bar("logout", "mainViewHelpPopup", false); ?>

	 
	<!-- Page content --> 
	<div data-role="content">
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error); ?>
			
		<!-- App description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Welcome") ?></h3>
			<p><?= _("<b>myBenevolat</b> description.") ?></p>	
		</div>
		
		<!-- App Main menu -->
		<?php if ($_SESSION['user']->is_guest) { ?>
			<!-- User not authenticated -->
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=extendedProfile" data-icon="signin" data-role="button" data-ajax="false"><?=_("Sign in")?></a><br />
		<?php } ?>		
		<a href="?action=mySubscription&subscriptions=true" data-icon="star" data-role="button" data-ajax="false" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My subscriptions") ?></a><br />	
		<a href="index.php?action=publish&method=show_user_publications" data-icon="pencil" data-role="button" data-ajax="false"  <?= ($_SESSION['user']->is_guest || !isset($_SESSION['myBenevolat'])) ? " class='ui-disabled'" : "" ?>><?= _("My publications") ?></a><br />
		<a href="?action=Find&search=true" data-role="button" data-icon="search"><?= _("Search publication") ?></a><br />
		<a href="?action=MyCandidature&method=show_candidatures" data-role="button" data-icon="pencil" <?= ($_SESSION['user']->is_guest || !isset($_SESSION['myBenevolat'])) ? " class='ui-disabled'" : "" ?>><?= _("My candidatures") ?></a><br />
		<a href="?action=extendedProfile&method=show_user_profile&user=<?= $_SESSION['user']->id ?>" data-icon="user" rel="external" data-role="button" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My profile") ?></a><br />

		<a href="index.php#aboutView" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
		
		<!-- MainView HELP POPUP -->
		<div data-role="popup" id="mainViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<h3><?= _("How it works") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("<<<<< Help topic >>>>>>") ?></strong></p>
						<p><?= _("<<<<< Help description >>>>>>") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("<<<<< Help topic >>>>>>") ?></strong></p>
						<p><?= _("<<<<< Help description >>>>>>>") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("<<<<< Help topic >>>>>>") ?></strong></p>
						<p><?= _("<<<<< Help description >>>>>>") ?></p>
				</li>
			</ul>
			<br />	
			<center><a href="#" data-role="button" data-icon="ok" data-inline="true" data-theme="e" data-rel="back" data-direction="reverse"><?= _("Ok")?></a></center>
		</div>
		
	</div>	
	<!-- End page content -->
	
</div>

<? include_once 'AboutView.php'; ?>
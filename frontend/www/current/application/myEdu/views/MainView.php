<!-- ------------------ -->
<!-- App Main View      -->
<!-- ------------------ -->

<div id="mainView" data-role="page">

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
			<p><?= _("myEdu is a social network, linking universities, students and companies of the MedAlp region.<br>On this network, you can find offers for academic formation, internships, Ph.D. positions, job opporunities, as well as for the exchange of professors and pedagogical tools.") ?></p>	
		</div>
		
		<!-- App Main menu -->
	<?php if ($_SESSION['user']->is_guest) { ?>
			<!-- User not authenticated -->
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=extendedProfile" data-icon="signin" data-role="button" data-ajax="false"><?=_("Sign in")?></a><br />
	<?php } ?>	
	<?php if (!$_SESSION['user']->is_guest && !isset($_SESSION['myEdu'])) { ?>
			<p Style="text-align: center; font-style:italic;"><?= _("You have to create your extended profile to access all the menu options") ?></p>
	<?php } ?>	
		<a href="?action=myOpportunity&opportunities=true" data-icon="star" data-role="button" data-ajax="false" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My subscriptions") ?></a><br />	
		<a href="index.php?action=publish&method=show_user_publications" data-icon="pencil" data-role="button" data-ajax="false"  <?= ($_SESSION['user']->is_guest || !isset($_SESSION['myEdu'])) ? " class='ui-disabled'" : "" ?>><?= _("My publications") ?></a><br />
		<a href="?action=Find&search=true" data-role="button" data-icon="search"><?= _("Search publication") ?></a><br />
		<a href="?action=MyCandidature&method=show_candidatures" data-role="button" data-icon="pencil" <?= ($_SESSION['user']->is_guest || !isset($_SESSION['myEdu'])) ? " class='ui-disabled'" : "" ?>><?= _("My candidatures") ?></a><br />
		<a href="?action=extendedProfile&method=show_user_profile&user=<?= $_SESSION['user']->id ?>" data-icon="user" rel="external" data-role="button" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My profile") ?></a><br />

		<a href="index.php#aboutView" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
		
		<!-- MainView HELP POPUP -->
		<div data-role="popup" id="mainViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<?php if ($_SESSION['user']->is_guest) { ?>
				<li>
					<img alt="publish" src="img/icons/info.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Sign in") ?></strong></p>
						<p><?= _("Enter your username and password to access the myEdu network.<br>You can access myEdu through other social networks, such as Facebook, Twitter, Google+....<br>If you do not have a login, you can create one.") ?></p>
				</li>
				<? } ?>
				<li>
					<img alt="publish" src="img/icons/publish.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My subscriptions") ?></strong></p>
						<p><?= _("The list of subscriptions myEdu has found according to your criteria.") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/publish.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My publications") ?></strong></p>
						<p><?= _("See your publications.") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/search.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Search publication")?></strong></p>
						<p><?= _("Search for a publication according to your criteria.") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/publish.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My candidatures")?></strong></p>
						<p><?= _("See your candidatures.") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/profile.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My profile") ?></strong></p>
						<p><?= _("Access your myMed and MyEdu profile.") ?></p>
				</li>
			</ul>
			<br />	
		</div>
	</div>	
	<!-- End page content -->
	
</div>

<? include_once 'AboutView.php'; ?>

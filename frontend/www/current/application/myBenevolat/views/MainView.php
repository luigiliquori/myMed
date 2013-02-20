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
			<p><?= _("Main capsule text") ?></p>	
		</div>
		
		<!-- App Main menu -->
	 <? if ($_SESSION['user']->is_guest) { ?>
			<!-- User not authenticated - Sign in -->
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=extendedProfile" data-icon="signin" data-role="button" data-ajax="false"><?=_("Connect")?></a><br />
	 <? } ?>
		
	<? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->details['type'] == 'volunteer')):?>
		<a href="?action=mySubscription&subscriptions=true" data-icon="star" data-role="button" data-ajax="false" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My subscriptions") ?></a><br />
	<? endif;?>
	
	<? if(isset($_SESSION['myBenevolat']) && (($_SESSION['myBenevolat']->details['type'] == 'association') || ($_SESSION['myBenevolat']->details['type'] == 'admin'))): ?>
			<!-- Associations and Admins links -->
			<a href="index.php?action=publish&method=show_user_announcements" data-icon="pencil" data-role="button" data-ajax="false"  <?= ($_SESSION['myBenevolat']->permission == '0') ? " class='ui-disabled'" : "" ?>><?= _("My announcements") ?></a><br />
	<? endif; ?>
		
		
		<!-- Find view -->
		<a href="?action=Find&search=true" data-role="button" data-icon="search"><?= _("Search announcement") ?></a><br />
		
		
	<?  if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->details['type'] == 'volunteer')): ?>
			<!-- Volunteer candidatures -->
			<a href="?action=Candidature&method=show_candidatures" data-role="button" data-icon="pencil"><?= _("My candidatures") ?></a><br />
	<?  endif; ?>
		
	 <? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->permission == '2')): ?>
	 		<a href="?action=Volunteer&method=show_all_volunteers" data-role="button" data-icon="pencil"><?= _("Volunteers list") ?></a><br />
	 <? endif; ?>
		
		<!-- Profile view -->
		<a href="?action=extendedProfile&method=show_user_profile&user=<?= $_SESSION['user']->id ?>" data-icon="user" rel="external" data-role="button" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= isset($_SESSION['myBenevolat']) ? _("My profile") : _("Create a profile") ?></a><br />

		
	<? if(isset($_SESSION['myBenevolat']) && ($_SESSION['myBenevolat']->permission == '2')): ?>
			<!-- Admin links -->
			<h3>ADMINISTRATION:</h3>
			<a href="?action=Candidature&method=show_all_candidatures" data-role="button" data-icon="pencil" <?= ($_SESSION['user']->is_guest) ? " class='ui-disabled'" : "" ?>><?= _("Manage candidatures") ?></a><br />
			<a href="?action=Validation&method=show_all_validations" data-role="button" data-icon="pencil" <?= ($_SESSION['user']->is_guest) ? " class='ui-disabled'" : "" ?>><?= _("Manage validations") ?></a><br />
			<a href="?action=admin" data-role="button" data-icon="pencil" ><?= _("Manage associations") ?></a><br />
	<? endif;?>
		
		<!-- About dialog -->
		<a href="index.php#aboutView" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
		
		<!-- endif -->
		
		<!-- MainView HELP POPUP -->
		<div data-role="popup" id="mainViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Connect") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My subscriptions (volunteer)") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My announcements (association)") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Search announcement") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Volunteers list (admin)") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("My profile") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Manage candidatures (admin)") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Manage validations (admin)") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/help.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Manage associations (admin)") ?></strong></p>
						<p><?= _("description") ?></p>
				</li>
			</ul>
		</div>
	</div>	
	<!-- End page content -->
	
</div>

<? include_once 'AboutView.php'; ?>

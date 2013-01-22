<!-- ------------------    -->
<!-- App Main View         -->
<!-- ------------------    -->
<? require_once('notifications.php'); ?>

<div id="mainView" data-role="page">

	<? print_header_bar(false, "mainViewHelpPopup", "myEdu", true); ?>
	 
	<div data-role="content">
			
		<!-- App description -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Welcome") ?></h3>
			<p><?= _("MyEdu description goes here.") ?></p>	
		</div>
		
		<!-- App Main menu -->
		<?php if ($_SESSION['user']->is_guest) { ?>
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=extendedProfile" rel="external" data-icon="signin" data-role="button" ><?=_("Sign in")?></a><br />
		<?php } ?>
		<a href="?action=Find&search=true" data-role="button" data-icon="search"><?= _("Find") ?></a><br />
		<a href="index.php#myactivities" data-icon="pencil" data-role="button" rel="external" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My Activities") ?></a><br />	
		<a href="" data-icon="user" rel="external" data-role="button" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("My Profile") ?></a><br />
		<a href="index.php#aboutView" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
		
		<!-- MainView HELP POPUP -->
		<div data-role="popup" id="mainViewHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<h3><?= _("How it works") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<li>
					<img alt="publish" src="img/icons/helpimage.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Help topic") ?></strong></p>
						<p><?= _("Help description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/helpimage.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Help topic") ?></strong></p>
						<p><?= _("Help description") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/helpimage.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Help topic") ?></strong></p>
						<p><?= _("Help description") ?></p>
				</li>
			</ul>
			<br />	
			<center><a href="#" data-role="button" data-icon="ok" data-inline="true" data-theme="e" data-rel="back" data-direction="reverse">Ok</a></center>
		</div>
		
	</div>	
</div>

<? include_once 'AboutView.php'; ?>

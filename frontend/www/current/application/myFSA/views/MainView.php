<? include("header.php"); ?>
</head>

<body>

<!-- Page view -->
<div data-role="page" id="mainView" >


	<!-- Header bar -->
	<? include "header-bar.php" ?>

	<!-- Page content --> 
	<div data-role="content" >
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? //print_notification($this->success.$this->error); ?>
			
		<!-- App description 
		<br/>
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("Welcome") ?></h3>
			<p><?= _("Main capsule text") ?></p>	
		</div>
		-->
		<br/>
		
	 <? if(!isset($_SESSION['user']) || $_SESSION['user']->is_guest){ ?>
			<!-- User not authenticated - Sign in -->
			<p Style="text-align: center; font-style:italic;"><?= _("You have to login to access all the menu options") ?></p>
			<a href="index.php?action=login" data-icon="signin" data-role="button" data-ajax="false"><?=_("Connection")?></a><br />
	  <? } ?>
		<a href="?action=Search" data-role="button" data-transition="none" data-icon="search"><?=_("Search")?></a>
		<br/>
		<a href="?action=Publish" data-role="button" data-transition="none" data-icon="pencil" <?= (isset($_SESSION['ExtendedProfile']) && $_SESSION["profileFilled"] != "guest") ? "" : "class='ui-disabled' " ?> ><?=_("Publish")?></a>
		<br/>
		<a data-ajax="false" href="?action=Localise" type="button" data-transition="slide" data-icon="search"><?= _("Localize") ?></a>
		<br/>
		<a href="?action=ExtendedProfile" data-icon="user" data-role="button" <?= (isset($_SESSION['user']) && !$_SESSION['user']->is_guest)? "" : " class='ui-disabled'" ?>><?=_("Profile")?></a>
		<br/>
		<p style="display:inline; position: absolute; right:10px">
			<a href="?action=Home" data-role="button" data-inline="true" data-icon="home" ><?=_("Home")?></a>
			<a href="?action=About" data-icon="info-sign" data-role="button" data-inline="true" ><?=_("About")?></a>
		</p>
		<br/>
		
	</div>	
	<!-- End page content -->
	</div>
</body>

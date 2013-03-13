<!-- ------------------ -->
<!-- App Register View  -->
<!-- ------------------ -->
<? require_once("header.php"); ?>
</head>
<body>

<div id="upgradeaccountView" data-role="page" >

	<!-- Page Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="?action=ExtendedProfile" data-icon="arrow-l" data-ajax="false"><?= _("back")?></a>
		<h1><?= _("Upgrade to myMed") ?></h1>
		<a href="#helpPopup" id="openHelp" data-icon="question-sign" class="ui-btn-right" data-theme="e" data-rel="popup"><?= _('Help')?></a>
	</div>
		
	<!-- Help popup -->
	<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Create a myMed profile help text") ?></p>
	</div>
		
	<!-- Page content -->
	<div data-role="content">
		<? include_once "notifications.php"; ?>
		<? print_notification($this->success.$this->error); ?>
		<!--  Register form -->
		<form action="?action=upgradeAccount" method="post" data-ajax="false">
			<input type="hidden" name="email" value="<?= $_SESSION['user']->email ?>" />
			<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>" />
			<input type="hidden" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			<input type="hidden" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />

			<!-- Email -->
			<p><?= _("E-mail")?> : <b><?= $_SESSION['user']->email ?></b></p>
			<!-- Name -->
			<label for="prenom"><?= _("First name") ?>: </label>
			<input type="text" name="prenom" value="<?= $_SESSION['user']->firstName ?>" />
			<br />
			<!-- SurName-->
			<label for="nom"><?= _("Last name") ?>: </label>
			<input type="text" name="nom" value="<?= $_SESSION['user']->lastName ?>" />
			<br />
			<!-- Password -->
			<label for="password" ><?= _("Password") ?><b>*</b> : </label>
			<input type="password" name="password" />
			<br />
			<!-- Confirm password -->
			<label for="password" ><?= _("Password Confirmation") ?><b>*</b> : </label>
			<input type="password" name="confirm" />
			<br />
			
			<!-- Agree terms and conditions -->
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 5px; width:17px;height:17px;"/>
			<span style="position: relative; left: 50px;">
				<a href="../application/myMed/doc/CGU_fr.pdf" rel="external"><?= _('I accept the general terms and conditions'); ?></a>
			</span>
			<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			
			
			<!-- SubMit button -->
			<div style="text-align: center;">
				<input type="submit" data-role="button" data-theme="g" data-inline="true" data-icon="ok-sign" value="<?= _('Send') ?>" />
			</div>
		
		</form>
		
	</div> <!-- END Page content -->
</div> <!-- END Page -->
</div>

<? include("footer.php"); ?>
</div>

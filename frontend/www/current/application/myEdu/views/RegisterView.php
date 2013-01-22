<!-- ------------------    -->
<!-- App Register View     -->
<!-- ------------------    -->

<div id="registerView" data-role="page" >

	<? $title = _("Register");
	   print_header_bar(true, false, $title); ?>
	
	<div data-role="header" data-theme="b">
		<h1><?= _("Account creation") ?></h1>
	</div>
	<div data-role="content">
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
	
		<!--  Register form -->
		<form action="?action=register" method="post" data-ajax="false">
		
				<label for="prenom"><?= _("First name") ?>: </label>
				<input type="text" name="prenom" value="" />
				<br />
				
				<label for="nom"><?= _("Last name") ?>: </label>
				<input type="text" name="nom" value="" />
				<br />
				
				<label for="email" >Email: </label>
				<input type="text" name="email" value="" />
				<br />
				
				<label for="password" ><?= _("Password") ?>: </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" ><?= _("Confirm") ?>: </label>
				<input type="password" name="confirm" />
				<br />
				
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
				<span style="position: relative; left: 50px;">
					<a href="application/myMed/doc/CGU_fr.pdf" rel="external"><?= _('I accept the general terms and conditions'); ?></a>
				</span><br>
				
				<div style="text-align: center;">
					<input type="submit" data-role="button" data-theme="g" data-inline="true" data-mini="true" data-icon="ok-sign" value="<?= _('Send') ?>" />
				</div>
		
		</form>
	</div>
	
</div>

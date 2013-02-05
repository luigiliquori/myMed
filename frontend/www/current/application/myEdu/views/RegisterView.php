<!-- ------------------ -->
<!-- App Register View  -->
<!-- ------------------ -->

<div id="registerView" data-role="page" >

	<!-- Page Header -->
	<? $title = _("Register a new account");
	   print_header_bar("?action=extendedProfile", false, $title); ?>
	
	<!-- Page content -->
	<div data-role="content">
	
		<!-- Notification pop up -->
		<? include_once 'notifications.php'; ?>
		<? print_notification($this->success.$this->error);?>
	
		<!--  Register form -->
		<form action="?action=register" method="post" data-ajax="false">
		
				<!-- Name -->
				<label for="prenom"><?= _("First name") ?>: </label>
				<input type="text" name="prenom" value="" />
				<br />
				<!-- SurName-->
				<label for="nom"><?= _("Last name") ?>: </label>
				<input type="text" name="nom" value="" />
				<br />
				<!-- Email -->
				<label for="email" >Email: </label>
				<input type="text" name="email" value="" />
				<br />
				<!-- Password -->
				<label for="password" ><?= _("Password") ?>: </label>
				<input type="password" name="password" />
				<br />
				<!-- Confirm password -->
				<label for="password" ><?= _("Confirm") ?>: </label>
				<input type="password" name="confirm" />
				<br />
				
				<!-- Agree terms and conditions -->
				<input style="vertical-align:middle;display:inline;float:left;width:17px;height:17px;padding:0;margin:0;vertical-align: bottom;" type="checkbox" id="service-term"  name="checkCondition"/>	
				<span style="vertical-align:middle;display:inline;float:left; position: relative; left: 50px;">
					<?= _("I accept the ")?>
					<a href="../application/myMed/doc/CGU_fr.pdf" rel="external"><?= _('general terms and conditions'); ?></a>
				</span><br/>
				
				
				
				<!-- SubMit button -->
				<div style="text-align: center;">
					<input type="submit" data-role="button" data-theme="g" data-inline="true" data-mini="true" data-icon="ok-sign" value="<?= _('Send') ?>" />
				</div>
		
		</form>
		
	</div> <!-- END Page content -->
	
</div> <!-- END Page -->

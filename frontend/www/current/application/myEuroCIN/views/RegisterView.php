<!-- ------------------ -->
<!-- App Register View  -->
<!-- ------------------ -->

<!-- Page View  -->
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
				<label for="prenom"><?= _("First name") ?> : </label>
				<input type="text" name="prenom" value="" />
				<br />
				<!-- SurName-->
				<label for="nom"><?= _("Last name") ?> : </label>
				<input type="text" name="nom" value="" />
				<br />
				<!-- Email -->
				<label for="email" ><?= _("Email")?><b>*</b> :</label>
				<input type="text" name="email" value="" />
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

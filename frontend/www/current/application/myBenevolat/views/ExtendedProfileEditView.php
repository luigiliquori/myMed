<!-- ------------------------------------ -->
<!-- ExtendedProfileEdit View             -->
<!-- Edit a user extended profile details -->
<!-- ------------------------------------ -->


<!-- Header bar functions -->
<? require_once('header-bar.php'); ?>


<!-- Notification pop up -->
<? require_once('notifications.php'); ?>


<!-- Page view -->
<div data-role="page" id="extendedprofileeditview">

	<!-- Page header -->
	<? $title = _("Edit Profile");

	   print_header_bar(
	   		'index.php?action=extendedProfile&method=show_user_profile&user='
	   		.$_SESSION['user']->id.'', "defaultHelpPopup", $title); ?>

	   <!-- Print profile type -->
	   <div class="ui-bar ui-bar-e" style="text-align: center" data-theme="e">
	   		<h2> <?= _("Profile type") ?>: <strong style="text-transform:uppercase;"><?= _($_SESSION['myBenevolat']->details['type'])?></strong></h2>
	   </div>
	   
	   
	<!-- Page content -->
	<div data-role="content">
	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Extended profile edit form -->
		<!-- Final wizard form -->
		<form action="?action=ExtendedProfile&method=update" id="profileForm" method="POST" data-ajax="false">
						
			<input type="hidden" name="id" value="<?= $_SESSION['myBenevolat']->profile ?>" />

			<!-- Profile type -->
			<div style="text-align: center">
				
			</div>
			<script type="text/javascript">
				$("#extendedprofileeditview").on("pageshow", function() {  
					
					switch ('<?= $_SESSION['myBenevolat']->details['type'] ?>') {			

						case 'volunteer':
							$('#siretdiv').hide();
							$('#websitediv').hide();	
	  						break; 
	  						
	  					case 'association':
	  						$('#siretdiv').show();
	  						$('#websitediv').show();
	  						$('#birthdaydiv').hide();
	  						$('#sexdiv').hide();
	  						$('#workdiv').hide();
	  						$('#mobilitediv').hide();	  						
		   					break;

  					}
				});
			</script>
			
			<!-- User profile details -->
			
			<!-- First Name -->
			<div data-role="fieldcontain">
				<label for="firstName" style="text-align:right"><?= _("First Name") ?> : </label>
				<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			</div>
			
			<!-- Last Name -->
			<div data-role="fieldcontain">
				<label for="lastName" style="text-align:right"><?= _("Last Name") ?> : </label>
				<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			</div>
			
			<!-- Birthday -->
			<div data-role="fieldcontain" id="birthdaydiv" >
				<label for="birthday" style="text-align:right"><?= _("Birthday") ?> : </label>
				<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			</div>
			
			<!-- Profile picture -->
			<div data-role="fieldcontain">
				<label for="profilePicture" style="text-align:right"><?= _("Profile picture") ?> (url): </label>
				<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			</div>
			
			<!-- User language -->
			<div data-role="fieldcontain">
				<label for="lang" style="text-align:right"><?= _("Language") ?>	: </label>
				<select id="lang" name="lang">
					<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
					<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
					<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
				</select>
			</div>
			
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="phone" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="phone" name="phone" value="<?= $_SESSION['myBenevolat']->details['phone'] ?>"  type="tel" />
			</div>
			
			<!-- Address -->		
			<div data-role="fieldcontain">
				<label for="address" style="text-align:right"><?= _('Address') ?>: </label>
				<input id="address" name="address" value="<?= $_SESSION['myBenevolat']->details['address'] ?>" />
			</div>
			
			<!-- Only Volunteer fields-->
			<!-- Sex -->
			<div id="sexdiv" data-role="fieldcontain" style="text-align:right">	
				<fieldset data-role="controlgroup" name="sex" id="sex">
					<legend> Sex: </legend>
			     	<input type="radio" name="sex" id="male" value="male"/>
			     	<label for="male">Male</label>
					<input type="radio" name="sex" id="female" value="female"/>
			     	<label for="female">Female</label>
				</fieldset>
			</div>
			
			<!-- Work -->
			<div id="workdiv" data-role="fieldcontain" id="work" style="text-align:right">	
				<fieldset data-role="controlgroup">
				<legend> Your work: </legend>
		     	<input type="radio" name="work" id="active" value="active" />
		     	<label for="active">Active</label>
		     	<input type="radio" name="work" id="unemployed" value="unemployed" />
		     	<label for="unemployed">Unemployed</label>
		     	<input type="radio" name="work" id="retired" value="retired" />
		     	<label for="retired">Retired</label>
		     	<input type="radio" name="work" id="student" value="student" />
		     	<label for="student">Student</label>
				</fieldset>
			</div>
			<!-- END Only Volunteer fields-->
			
			<!-- Only Association fields-->
			<!-- Siret -->		
			<div id="siretdiv" data-role="fieldcontain">
				<label for="siret" style="text-align:right"><?= _('SIRET') ?>: </label>
				<input id="siret" name="siret" value="<?= $_SESSION['myBenevolat']->details['siret'] ?>" />
			</div>
			
			<!-- Web Site -->		
			<div id="websitediv" data-role="fieldcontain">
				<label for="website" style="text-align:right"><?= _('Web site') ?>: </label>
				<input id="website" name="website" value="<?= $_SESSION['myBenevolat']->details['website'] ?>" />
			</div>
			<!-- END Only Association fields-->
			
			<!-- Competences list -->
			<br/><br/>
			<div class="ui-bar ui-bar-e" style="text-align: center" data-theme="e">
				<h1 style="white-space: normal;">
				<?php if($_SESSION['myBenevolat']->details['type'] == 'volunteer'):?>
					<?= _("Your competences.") ?>
				<?else:?>
					<?= _("The competences you need.") ?>
				<?endif;?>
				</h1>
			</div>
			<br />
			<div data-role="fieldcontain" style="text-align: center" id="competences">
	    		<fieldset data-role="controlgroup">
	    		<? foreach (Categories::$competences as $k=>$v) :?>
					<input type="checkbox" name="competences-checkbox" id="<?=$k?>" value="<?=$k?>" />
					<label for="<?=$k?>"> <?=$v?> </label>
				<? endforeach ?>
	    		</fieldset>
	    	</div>
	    	
	    	<!-- Missions list -->
			<br/><br/>
			<div class="ui-bar ui-bar-e" style="text-align: center" data-theme="e">
				<h1 style="white-space: normal;">
				<?php if($_SESSION['myBenevolat']->details['type'] == 'volunteer'):?>
					<?= _("Missions.") ?>
				<?else:?>
					<?= _("The missions you propose.") ?>
				<?endif;?>
				</h1>
			</div>
			<br />
			<div data-role="fieldcontain" style="text-align: center" id="missions">
	    		<fieldset data-role="controlgroup">
	    		<? foreach (Categories::$missions as $k=>$v) :?>
					<input type="checkbox" name="missions-checkbox" id="<?=$k?>" value="<?=$k?>" />
					<label for="<?=$k?>"> <?=$v?> </label>
				<? endforeach ?>
	    		</fieldset>
	    	</div>
	    	

	    	<!-- Only Volunteer fields -->	    	
	    	<!-- Mobilite list -->
			<br/><br/>
			<div id="mobilitediv">
				<div class="ui-bar ui-bar-e" style="text-align: center" data-theme="e">
					<h1 style="white-space: normal;">
						<?= _("Your mobilite.") ?>
					</h1>
				</div>
				<br />
				<div data-role="fieldcontain" style="text-align: center" id="mobilite">
		    		<fieldset data-role="controlgroup">
		    		<? foreach (Categories::$mobilite as $k=>$v) :?>
						<input type="checkbox" name="mobilite-checkbox" id="<?=$k?>" value="<?=$k?>" />
						<label for="<?=$k?>"> <?=$v?> </label>
					<? endforeach ?>
		    		</fieldset>
		    	</div>
	    	</div>
			<!-- END Only Volunteer fields-->
						
			<br/>
			<div data-role="fieldcontain">
				
				<!-- Password -->
				<label for="password" style="text-align:right"><?= _("Password") ?>:</label>
				<input type="password" id="password" name="password" />
				
				<!-- MyMed basic profile fields -->
				<input type="hidden" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
				<input type="hidden" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
				<input type="hidden" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
				<input type="hidden" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
				<input type="hidden" id="picture" name="picture" value="<?= $_SESSION['user']->profilePicture ?>" />
				
				<!-- Extended profile fields -->
				<input type="hidden" id="type" name="type" value="<?= $_SESSION['myBenevolat']->details['type'] ?>" />
				<input type="hidden" id="validated" name="validated" value="false"/>
				<input type="hidden" id="sex" name="sex" />
				<input type="hidden" id="phone" name="phone" value="" />
				<input type="hidden" id="work" name="work" value="" />
				<input type="hidden" id="address" name="address" value="" />
				<input type="hidden" id="website" name="website" value="" />
				<input type="hidden" id="siret" name="siret" value="" />
				<input type="hidden" id="competences" name="competences" value="" />
				<input type="hidden" id="missions" name="missions" value="" />
				<input type="hidden" id="mobilite" name="mobilite" value="" />
				
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="ok" value="<?= _('Update') ?>"/>
			</div>
		</form>
		
	</div> <!-- END page-->
	
	
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit your Profile") ?></h3>
		<p> <?= _("Here you can update your organization profile.") ?></p>
	</div>
	
</div>


	<!-- Notification messages pop up -->
<div data-role="popup" id="notificationPopup" data-transition="flip" data-theme="e" class="ui-content">
	<p id="popupMessage" name="popupMessage"><p>
</div>';


<!-- Handle checkboxes and radio buttons -->
<script type="text/javascript">

	// Fill in values 
	//$(document).on("pageshow","#extendedprofileeditview", function() {

		// Reset checked controls
		$("input[name=sex]").prop('checked',false);//.checkboxradio('refresh');
		$("input[name=work]").prop('checked',false);//.checkboxradio('refresh');
		$("input[name='competences-checkbox']").prop('checked',false);//.checkboxradio('refresh');
		$("input[name='missions-checkbox']").prop('checked',false);//.checkboxradio('refresh');
		$("input[name='mobilite-checkbox']").prop('checked',false);//.checkboxradio('refresh');

		// Check proper values
		<?php if(isset($_SESSION['myBenevolat']->details['sex'])): ?>
		$("input[name=sex]").filter('[value=<?= $_SESSION['myBenevolat']->details['sex']?>]').prop('checked',true);//.checkboxradio('refresh');
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['work'])): ?>
		$("input[name=work]").filter('[value=<?= $_SESSION['myBenevolat']->details['work']?>]').prop('checked',true);//.checkboxradio('refresh');
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['competences'])): ?>
			<? $tokens = explode(" ", $_SESSION['myBenevolat']->details['competences']);
			   array_pop($tokens);
				foreach ($tokens as $t) : ?>
				$("input[name=competences-checkbox]").filter('[value=<?= $t?>]').prop('checked',true);//.checkboxradio('refresh');
				<? endforeach ?>
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['missions'])): ?>
			<? $tokens = explode(" ", $_SESSION['myBenevolat']->details['missions']);
			   array_pop($tokens);
				foreach ($tokens as $t) : ?>
				$("input[name=missions-checkbox]").filter('[value=<?= $t?>]').prop('checked',true);//.checkboxradio('refresh');
				<? endforeach ?>
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['mobilite'])): ?>
			<? $tokens = explode(" ", $_SESSION['myBenevolat']->details['mobilite']);
			   array_pop($tokens);
				foreach ($tokens as $t) : ?>
				$("input[name=mobilite-checkbox]").filter('[value=<?= $t?>]').prop('checked',true);//.checkboxradio('refresh');
				<? endforeach ?>
		<? endif; ?>
		
	//});

		
	/* Override default submit function */
	$('#profileForm').submit(function() {

		
		switch('<?= $_SESSION['myBenevolat']->details['type'] ?>') {
	
				case 'volunteer':

					// Validate volunteer fields			
					if(!$('#phone').val()) {
					warningPopUp('Please provide a valid telephone number');
					break;
					}
					if(!$("#sex :radio:checked").val()) {
						warningPopUp('Please specify your sex');
						break;
					}
					if(!$("#work :radio:checked").val()) {
						warningPopUp('Please specify your working status');
						break;
					}

					// Fill volunteer profile fields
					$("input[id=phone]").val($('#phone').val());
					$("input[id=sex]").val($('#sex').val());
					$("input[id=work]").val($('#phone').val());
					$("input[id=address]").val($('#address').val());
					$("input[id=competences]").val($("input[name*=competences]:checked"));
					$("input[id=missions]").val($("input[name*=missions]:checked"));
					$("input[id=mobilite]").val($("input[name*=mobilite]:checked"));
												
					break;

				case 'association':

					// Validate association fields
					if(!$('#phone').val()) {
						warningPopUp('Please provide a valid telephone number');
						return false;
					}
					if(!$("#address").val()) {
						warningPopUp('Please specify a valid address');
						return false;
					}
					var n_competences = $("input[name*=competences]:checked").size(); 
					if(!(n_competences>=1)) {
						warningPopUp('Choose at least one competence you need');
						return false;
					}
					profile.competences = $("input[name*=competences]:checked");
					var n_missions = $("input[name*=missions]:checked").size(); 
					if(!(n_missions>=1)) {
						warningPopUp('Choose at least one mission');
						return false;
					}
								
					// Fill association fields
					$("input[id=phone]").val($('#phone').val());
					$("input[id=website]").val($('#website').val());
					$("input[id=siret]").val($('#siret').val());
					$("input[id=address]").val($('#address').val());
					$("input[id=competences]").val($("input[name*=competences]:checked"));
					$("input[id=missions]").val($("input[name*=missions]:checked"));
					
					break;
			}
				 
			
			// Submit the form
			return true;
	});

	
	/* Show a warning pop up */ 
	function warningPopUp(message) {
		$("#notificationPopup").popup({ history: false });
		$("#popupMessage").text(message);
		$("#notificationPopup").popup("open");	
	}
	
</script>
			

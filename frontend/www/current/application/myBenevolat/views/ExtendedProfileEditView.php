<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<!-- ------------------------------------ -->
<!-- ExtendedProfileEdit View             -->
<!-- Edit a user extended profile details -->
<!-- ------------------------------------ -->

<? require_once('header-bar.php'); ?>

<!-- Page view -->
<div data-role="page" id="extendedprofileeditview">

	
	<!-- Notification pop up -->
	<? include_once 'notifications.php'; ?>
	<? print_notification($this->success.$this->error); ?>
		
	<!-- Page header -->
	<? $title = _("Edit Profile");

	   print_header_bar('index.php?action=extendedProfile&method=show_user_profile&user='.$_SESSION['user']->id.'', "defaultHelpPopup", $title); ?>	   
	   
	<!-- Page content -->
	<div data-role="content">
	
		<!-- Print profile type -->
	   <div style="text-align: center">
	   		<label for="typeProfile"> <?= _("Profile type") ?>: </label>
	   		<strong style="text-transform:uppercase;"><?= _($_SESSION['myBenevolat']->details['type'])?></strong>
	   </div>
	   
		<!-- Extended profile edit form -->
		<form action="?action=ExtendedProfile&method=update" id="updateProfileForm" method="POST" data-ajax="false" >
						
			<input type="hidden" name="id" value="<?= $_SESSION['myBenevolat']->profile ?>" />

			<script type="text/javascript">
				$("#extendedprofileeditview").on("pageshow", function() {  
					switch ('<?= $_SESSION['myBenevolat']->details['type'] ?>') {			

						case 'volunteer':
							$('#associationnamediv').hide();
							$('#siretdiv').hide();
							$('#websitediv').hide();	
	  						break; 

						case 'admin':
	  					case 'association':
	  						$('#associationname').show();
	  						$('#siretdiv').show();
	  						$('#websitediv').show();
	  						//$('#birthdaydiv').hide();
	  						$('#sexdiv').hide();
	  						$('#workdiv').hide();
	  						$('#mobilitediv').hide();	
	  						$('#dispodiv').hide();  						
		   					break;
  					}
				});
			</script>
			
			<!-- User profile details -->
			
			<!-- First Name -->
			<div data-role="fieldcontain">
				<label for="firstName" style="text-align:right"><?= _("First Name") ?> : </label>
				<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			
			<!-- Last Name -->
			<div data-role="fieldcontain">
				<label for="lastName" style="text-align:right"><?= _("Last Name") ?> : </label>
				<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			
			<!-- Birthday -->
			<div data-role="fieldcontain" id="birthdaydiv" >
				<label for="birthday" style="text-align:right"><?= _("Date of birth") ?> (jj/mm/aaaa) : </label>
				<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			
			<!-- Profile picture -->
			<div data-role="fieldcontain">
				<label for="profilePicture" style="text-align:right"><?= _("Profile picture") ?> (url) : </label>
				<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			
			<!-- User language -->
			<div data-role="fieldcontain">
				<label for="lang" style="text-align:right"><?= _("Language") ?>	: </label>
				<select id="lang" name="lang" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>>
					<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
					<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
					<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
				</select>
			</div>
			
			<!-- Association name -->		
			<div data-role="fieldcontain" id="associationnamediv">
				<label for="associationname" style="text-align:right"><?= _('Association name') ?><b>*</b>: </label>
				<input id="associationname" name="associationname" value="<?= $_SESSION['myBenevolat']->details['associationname'] ?>" />
			</div>
			
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="phone" style="text-align:right">
					<?= ($_SESSION['myBenevolat']->details['type']=="association")? _('Association phone'): _('Phone'); ?><b>*</b>: 
				</label>
				<input id="phone" name="phone" value="<?= $_SESSION['myBenevolat']->details['phone'] ?>"  type="tel" />
			</div>
			
			<!-- Address -->		
			<div data-role="fieldcontain">
				<label for="address" style="text-align:right">
					<?= ($_SESSION['myBenevolat']->details['type']=="association")? _('Association address'): _("Address"); ?>: 
				</label>
				<input id="address" name="address" value="<?= $_SESSION['myBenevolat']->details['address'] ?>" />
			</div>
			
			<!-- Only Volunteer fields-->
			<!-- Sex -->
			<div id="sexdiv" data-role="fieldcontain" style="text-align:right">	
				<fieldset data-role="controlgroup">
					<legend> <?=_("Sex")?>: </legend>
			     	<input type="radio" name="sex-radio" id="male" value="male"/>
			     	<label for="male"><?= _("Male")?></label>
					<input type="radio" name="sex-radio" id="female" value="female"/>
			     	<label for="female"><?= _("Female")?></label>
				</fieldset>
			</div>
			
			<!-- Work -->
			<div id="workdiv" data-role="fieldcontain" style="text-align:right">	
				<fieldset data-role="controlgroup">
				<legend> <?= _("Working status")?>: </legend>
		     	<input type="radio" name="work-radio" id="active" value="active" />
		     	<label for="active"><?= _("Active")?></label>
		     	<input type="radio" name="work-radio" id="unemployed" value="unemployed" />
		     	<label for="unemployed"><?= _("Unemployed")?></label>
		     	<input type="radio" name="work-radio" id="retired" value="retired" />
		     	<label for="retired"><?= _("Retired")?></label>
		     	<input type="radio" name="work-radio" id="student" value="student" />
		     	<label for="student"><?= _("Student")?></label>
				</fieldset>
			</div>
			<!-- END Only Volunteer fields-->
			
			<!-- Only Association fields-->
			<!-- Siret -->		
			<div id="siretdiv" data-role="fieldcontain">
				<label for="siret" style="text-align:right"><?= _('SIRET') ?><b>*</b>: </label>
				<input id="siret" name="siret" value="<?= $_SESSION['myBenevolat']->details['siret'] ?>" />
			</div>
			
			<!-- Web Site -->		
			<div id="websitediv" data-role="fieldcontain">
				<label for="website" style="text-align:right"><?= _('Association web site') ?>: </label>
				<input id="website" name="website" value="<?= $_SESSION['myBenevolat']->details['website'] ?>" />
			</div>
			<!-- END Only Association fields-->
			
			<!-- Competences list -->
			<br/>
			<div class="ui-bar ui-bar-e" data-theme="e">
				<h1 style="white-space: normal;">
	  <?php if($_SESSION['myBenevolat']->details['type'] == 'volunteer'):?>
					<?= _("Your skills (1 to 4)")?> <b>*</b> : 
				<?else:?>
					<?= _("The skills you need (1 to 4)")?> <b>*</b> : 
				<?endif;?>
				</h1>
			</div>	
			<br />
			
	    <?  foreach (Categories::$competences as $k=>$v) :?>
				<input type="checkbox" name="competences-checkbox" id="<?=$k?>" value="<?=$k?>" />
				<label for="<?=$k?>"> <?=$v?> </label>
		 <? endforeach ?>
	    		
	    	<!-- Missions list -->
			<br/><br/>
			<div class="ui-bar ui-bar-e" data-theme="e">
				<h1 style="white-space: normal;">
				<?php if($_SESSION['myBenevolat']->details['type'] == 'volunteer'):?>
					<?= _("Missions <b>*</b> :") ?>
				<?else:?>
					<?= _("The missions you propose")?> <b>*</b> :
				<?endif;?>
				</h1>
			</div>
			<br />
			
	    <?  foreach (Categories::$missions as $k=>$v) :?>
				<input type="checkbox" name="missions-checkbox" id="<?=$k?>" value="<?=$k?>" />
				<label for="<?=$k?>"> <?=$v?> </label>
		<?  endforeach ?>
	    	

	    	<!-- Only Volunteer fields -->	    	
	    	<!-- Mobilite list -->
			
			<div id="mobilitediv">
				<br/><br/>
				<div class="ui-bar ui-bar-e" data-theme="e">
					<h1 style="white-space: normal;">
						<?= _("Your district")?> <b>*</b> :
					</h1>
				</div>
				<br />
				
		     <? foreach (Categories::$mobilite as $k=>$v) :?>
					<input type="checkbox" name="mobilite-checkbox" id="<?=$k?>" value="<?=$k?>" />
					<label for="<?=$k?>"> <?=$v?> </label>
			 <? endforeach ?>
	    	</div>
	    	
	    	<!-- Disponibility list -->
	    	<div id="dispodiv">
	    		<br/><br/>
				<div class="ui-bar ui-bar-e" data-theme="e">
					<h1 style="white-space: normal;">
						<?= _("Your disponibility")?> <b>*</b> :
					</h1>
				</div>
				<br />
				
		     <? foreach (Categories::$disponibilites as $k=>$v) :?>
					<input type="checkbox" name="disponibilite-checkbox" id="<?=$k?>" value="<?=$k?>" />
					<label for="<?=$k?>"> <?=$v?> </label>
			 <? endforeach ?>
	    	</div>
			<!-- END Only Volunteer fields-->	
			<br />
			<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			
			<!-- MyMed basic profile fields -->
			<input type="hidden" id="email_h" name="email" value="<?= $_SESSION['user']->email ?>" />
			
			<!-- Extended profile fields -->
			<input type="hidden" id="type" name="type" value="<?= $_SESSION['myBenevolat']->details['type'] ?>" />
			<input type="hidden" id="validated" name="validated" value="false"/>
			<input type="hidden" id="sex" name="sex" />
			<input type="hidden" id="work" name="work" value="" />
			<input type="hidden" id="competences" name="competences" value="" />
			<input type="hidden" id="missions" name="missions" value="" />
			<input type="hidden" id="mobilite" name="mobilite" value="" />
			<input type="hidden" id="disponibilite" name="disponibilite" value="" />
			
			<div style="text-align: center;">
				<input id="submit" type="submit" data-inline="true" data-role="button" data-icon="ok" data-theme="g" value="<?= _('Update') ?>"/>
			</div>
		</form>
		
	
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit your Profile") ?></h3>
		<p> <?= _("Here you can update your profile.") ?></p>
	</div>
	
	
	<!-- Notification messages pop up -->
	<div data-role="popup" id="formerrorPopup" data-transition="flip" data-theme="e" class="ui-content">
		<p id="popupMessage"><p>
	</div>
	
	
<!-- Handle checkboxes and radio buttons -->
<script type="text/javascript">


	// Fill checkbox and radio controls   
	$(document).on("pageshow", function() {

		// Check proper values
		<?php if(isset($_SESSION['myBenevolat']->details['sex'])): ?>
		$("input[name=sex-radio]").filter('[value=<?= $_SESSION['myBenevolat']->details['sex'] ?>]').prop('checked',true).checkboxradio('refresh');
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['work'])): ?>
		$("input[name=work-radio]").filter('[value=<?= $_SESSION['myBenevolat']->details['work'] ?>]').prop('checked',true).checkboxradio('refresh');
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['competences'])): ?>
			<? $tokens = explode(" ", $_SESSION['myBenevolat']->details['competences']);
			   array_pop($tokens);
				foreach ($tokens as $t) : ?>
				$("input[name=competences-checkbox]").filter('[value=<?= $t?>]').prop('checked',true).checkboxradio('refresh');
				<? endforeach ?>
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['missions'])): ?>
			<? $tokens = explode(" ", $_SESSION['myBenevolat']->details['missions']);
			   array_pop($tokens);
				foreach ($tokens as $t) : ?>
				$("input[name=missions-checkbox]").filter('[value=<?= $t?>]').prop('checked',true).checkboxradio('refresh');
				<? endforeach ?>
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['mobilite'])): ?>
			<? $tokens = explode(" ", $_SESSION['myBenevolat']->details['mobilite']);
			   array_pop($tokens);
				foreach ($tokens as $t) : ?>
				$("input[name=mobilite-checkbox]").filter('[value=<?= $t?>]').prop('checked',true).checkboxradio('refresh');
				<? endforeach ?>
		<? endif; ?>

		<?php if(isset($_SESSION['myBenevolat']->details['disponibilite'])): ?>
		<? $tokens = explode(" ", $_SESSION['myBenevolat']->details['disponibilite']);
		   array_pop($tokens);
			foreach ($tokens as $t) : ?>
			$("input[name=disponibilite-checkbox]").filter('[value=<?= $t?>]').prop('checked',true).checkboxradio('refresh');
			<? endforeach ?>
	<? endif; ?>
		
	});

		
	/* Override default submit function */
	$('#updateProfileForm').submit(function() {
		
		switch('<?= $_SESSION['myBenevolat']->details['type'] ?>') {
	
				case 'volunteer':
					
					// Validate volunteer fields
					if(!$('#phone').val()) {
					warningPopUp('Please provide a valid telephone number');
						return false;
					}
					
					if(!$("input[name='sex-radio']:radio:checked").val()) {
						warningPopUp('Please specify your sex');
						return false;
					}
					if(!$("input[name='work-radio']:radio:checked").val()) {
						warningPopUp('Please specify your working status');
						return false;
					}
					var n_competences = $("input[name*=competences]:checked").size(); 
					if(!(n_competences>=1 && n_competences<=4)) {
						warningPopUp('You must choose from 1 to 4 competences');
						return false;;
					}
					if($("input[name*=missions]:checked").size()<1) {
						warningPopUp('You must choose at least 1 mission');
						return false;
					}
					if($("input[name*=mobilite]:checked").size()<1) {
						warningPopUp('You must choose at least 1 mobility');
						return false;
					}	
					if($("input[name*=disponibilite]:checked").size()<1) {
						warningPopUp('You must choose at least 1 disponibility');
						return false;
					}

					// Fill volunteer profile fields
					$("input[id=sex]").val($("input[name='sex-radio']:radio:checked").val());
					$("input[id=work]").val($("input[name='work-radio']:radio:checked").val());
					var competences = "";
					var checked = $("input[name*=competences]:checked");
					for(var i=0; i<checked.size(); i++)
						competences = competences + checked[i].value + " ";
					$("input[id=competences]").val(competences);
					var missions = "";
					var checked = $("input[name*=missions]:checked");
					for(var i=0; i<checked.size(); i++)
						missions = missions + checked[i].value + " ";
					$("input[id=missions]").val(missions);
					var mobilite = "";
					var checked = $("input[name*=mobilite]:checked");
					for(var i=0; i<checked.size(); i++)
						mobilite = mobilite + checked[i].value + " ";
					$("input[id=mobilite]").val(mobilite);
					var disponibilite = "";
					var checked = $("input[name*=disponibilite]:checked");
					for(var i=0; i<checked.size(); i++)
						disponibilite = disponibilite + checked[i].value + " ";
					$("input[id=disponibilite]").val(disponibilite);
												
					break;

				case 'admin':
				case 'association':

					// Validate association fields			
					if(!$('#associationname').val()) {
					warningPopUp('Please provide an association name');
						return false;
					}
					if(!$('#phone').val()) {
						warningPopUp('Please provide a valid telephone number');
						return false;
					}
					if(!$("#siret").val()) {
						warningPopUp('Please provide a siret');
						return false;
					}
					var n_competences = $("input[name*=competences]:checked").size(); 
					if(!(n_competences>=1 && n_competences<=4)) {
						warningPopUp('You must choose from 1 to 4 skills');
						return false;
					}
					var n_missions = $("input[name*=missions]:checked").size(); 
					if(!(n_missions>=1)) {
						warningPopUp('Choose at least one mission');
						return false;
					}
								
					// Fill association fields
					var competences = "";
					var checked = $("input[name*=competences]:checked");
					for(var i=0; i<checked.size(); i++)
						competences = competences + checked[i].value + " ";
					$("input[id=competences]").val(competences);

					var missions = "";
					var checked = $("input[name*=missions]:checked");
					for(var i=0; i<checked.size(); i++)
						missions = missions + checked[i].value + " ";
					$("input[id=missions]").val(missions);
					
					break;
			}
				 
			
			// Submit the form
			return true;
	});

	
	/* Show a warning pop up */ 
	function warningPopUp(message) {
		$("#formerrorPopup").popup({ history: false });
		$("p#popupMessage").text(message);
		$("#formerrorPopup").popup("open");	
	}

</script>

		</div> <!-- END page content -->
	
</div>


			

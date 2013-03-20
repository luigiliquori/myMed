<!--
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
 -->
<!-- --------------------------------------- -->
<!-- CreateProfileAssociationView            -->
<!-- Wizard for create a association profile -->
<!-- --------------------------------------- -->


<!-- STEP 1 VOLUNTEER -->
<div data-role="page" id="step1_association">	

	<!-- Header bar -->
	<? print_header_bar("?action=ExtendedProfile", false, _("Step 1 - association info")); ?>
		
	<!-- Container div to give objects a margin -->
	<div data-role="content" style="margin:30px; ">	
		<!-- Message -->
		<div class="ui-bar ui-bar-e">
			<h1><?= _("Please fill in your association information:") ?></h1>
		</div>
		<br>
		<!-- Association name -->
		<div data-role="fieldcontain" >
			<label for="associationname" style="text-align:right"> <?= _('Association name')?><b>*</b> :</label>
			<input type="text" name="associationname" id="associationname" />
		</div>
		
		<!-- Phone -->
		<div data-role="fieldcontain" >
			<label for="phone" style="text-align:right"> <?= _("Phone")?><b>*</b> : </label>
			<input type="text" name="phone" id="phone" placeholder="00 00 00 00 00" />
		</div>
		
		<!-- Siret number -->
		<div data-role="fieldcontain" >
			<label for="siret" style="text-align:right"> <?= _("SIRET")?><b>*</b> :</label>
			<input type="text" name="siret" id="siret"/>
		</div>
		
		<!-- Web Site -->
		<div data-role="fieldcontain" >
			<label for="website" style="text-align:right"> <?= _("Web site")?>: </label>
			<input type="text" name="website" id="website" placeholder="http://"/>
		</div>
		
		<!-- Address -->
		<div data-role="fieldcontain" >
			<label for="address" style="text-align:right"> <?= _("Address")?>: </label>
			<input type="text" name="address" id="address" />
		</div>
		<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
					
		<!-- Next and previous buttons -->
		<div style="text-align:center;">
			<a href="?action=ExtendedProfile" data-role="button" data-icon="arrow-l"  data-theme="e" data-inline="true"><?= _("Previous")?></a>
			<a data-role="button" data-icon="arrow-r" data-theme="b" data-inline="true" onClick="handleNext()"><?= _("Next")?></a>
		</div>
		
	</div>
	
</div> <!-- END - STEP 1 Association  -->


<!-- STEP 2 ASSOCIATION - Competences and Missions -->
<div data-role="page" id="step2_association" >	

	<!-- Header bar -->
	<? print_header_bar("#step1_association", false, _("Step 2 - Skills and missions")); ?>
		
	<div data-role="content" style="margin:30px; ">
		<!-- Competences list -->
		<div class="ui-bar ui-bar-e">
			<h1 style="white-space: normal;">
				<?= _("Please choose the skills you need (1 to 4)") ?><b>*</b> :
			</h1>
		</div>
		<br />
	<?  foreach (Categories::$competences as $k=>$v) :?>
			<input type="checkbox" name="competences-checkbox" id="<?=$k?>" value="<?=$k?>" />
			<label for="<?=$k?>"> <?=$v?> </label>
	 <? endforeach ?>
    	<br><br>
		
		<!-- Missions list -->
		<div class="ui-bar ui-bar-e">
			<h1 style="white-space: normal;">
				<?= _("Please choose the type of mission you propose") ?><b>*</b> :
			</h1>
		</div>
		<br />
     <? foreach (Categories::$missions as $k=>$v) :?>
			<input type="checkbox" name="missions-checkbox" id="<?=$k?>" value="<?=$k?>" />
			<label for="<?=$k?>"><?=$v?></label>
	 <? endforeach ?>
    	
    	<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			
		<!-- Container for final wizard form -->
		<div style="text-align:center;">
			
			<!-- Final wizard form -->
			<form action="?action=ExtendedProfile&method=create" id="profileForm" method="POST">
				
				<!-- Back link-->
				<a href="#step1_association" data-role="button" data-icon="arrow-l"  data-theme="e" data-inline="true"><?= _("Previous")?></a>
				
				<!-- MyMed basic profile fields 
				<input type="hidden" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
				<input type="hidden" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
				<input type="hidden" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
				<input type="hidden" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
				<input type="hidden" id="picture" name="picture" value="<?= $_SESSION['user']->profilePicture ?>" />
				-->
				<!-- Extended profile fields -->
				<input type="hidden" id="type" name="type" value="association" />
				<input type="hidden" id="associationname" name="associationname" />
				<input type="hidden" id="siret" name="siret" value="" />
				<input type="hidden" id="website" name="website" value="" />
				<input type="hidden" id="phone" name="phone" value="" />
				<input type="hidden" id="address" name="address" value="" />
				<input type="hidden" id="competences" name="competences" value="" />
				<input type="hidden" id="missions" name="missions" value="" />
				
				<!-- Submit button-->
				<input type="submit" id="submit" value=<?= _("Create the profile")?> data-inline="true" data-theme="g" />
			</form>
		</div>	
	</div>	
		
</div> <!-- END - STEP 2 ASSOCIATION -->


<!-- Notification messages pop up -->
<div data-role="popup" id="notificationPopup" data-transition="flip" data-theme="e" class="ui-content">
	<p id="popupMessage" name="popupMessage"><p>
</div>';

<!-- Handle the multi page wizard -->
<script type="text/javascript">

	/* Override default submit function */
	$('#profileForm').submit(function() {

		// Validate last page fields 
		if(handleNext()) {
			
			// Fill the form
			$("input[id=associationname]").val(profile.associationname);
			$("input[id=phone]").val(profile.phone);
			$("input[id=siret]").val(profile.siret);
			$("input[id=website]").val(profile.website);
			$("input[id=address]").val(profile.address);
			
			var competences = "";
			for(var i=0; i<profile.competences.size(); i++)
				competences = competences + profile.competences[i].value + " ";
			$("input[id=competences]").val(competences);

			var missions = "";
			for(var i=0; i<profile.missions.size(); i++)
				missions = missions + profile.missions[i].value + " ";
			$("input[id=missions]").val(missions);

			// Submit the form
			return true;
		} else {
			
			return false; 
		}
			
			
	});
	
	/* Association profile */
	function associationProfile() {

		this.associationname;
		this.phone;
		this.siret;
		this.website;
		this.address;
		this.competences;
		this.missions;
		
	}

	/* Store the profile data */
	var profile = new associationProfile();
	
	/* Handle the next button press event in the form */
	function handleNext() {

		// Check the current wizard page 
		switch($.mobile.activePage.attr('id')) {

			// Step 2 
			case 'step1_association':

				// Validate fields
				if(!$('#associationname').val()) {
					warningPopUp("Association name field can't be empty");
					break;
				}
				if(!$('#phone').val()) {
					warningPopUp("Phone field can't be empty");
					break;
				}
				if(!$("#siret").val()) {
					warningPopUp("SIRET field can't be empty");
					break;
				}
				
				// Fill profile fields
				profile.associationname = $('#associationname').val();
				profile.phone = $('#phone').val();
				profile.website = $('#website').val();
				profile.siret = $('#siret').val();
				profile.address = $('#address').val();
				$.mobile.changePage('#step2_association');
				break;

				
			// Step 2 
			case 'step2_association':
				// Validate fields
				var n_competences = $("input[name*=competences]:checked").size(); 
				if(!(n_competences>=1 && n_competences<=4)) {
					warningPopUp('You must choose from 1 to 4 skills');
					return false;
				}
				profile.competences = $("input[name*=competences]:checked");
				var n_missions = $("input[name*=missions]:checked").size(); 
				if(!(n_missions>=1)) {
					warningPopUp('Choose at least one mission');
					return false;
				}
				profile.missions = $("input[name*=missions]:checked");				
				break;

		}

		return true;
	}

	/* Show a warning pop up */ 
	function warningPopUp(message) {
		$("#notificationPopup").popup({ history: false });
		$("#popupMessage").text(message);
		$("#notificationPopup").popup("open");
	}

</script>

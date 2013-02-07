<!-- --------------------------------------- -->
<!-- CreateProfileAssociationView            -->
<!-- Wizard for create a association profile -->
<!-- --------------------------------------- -->


<!-- STEP 1 VOLUNTEER -->
<div data-role="page" id="step1_association">	

	<!-- Header bar -->
	<? print_header_bar("?action=ExtendedProfile", false, "Step 1 - personal info"); ?>
		
	<!-- Message -->
	<div class="ui-bar ui-bar-e" style="text-align: center">
		<h1><?= _("Please fill in your personal info.") ?></h1>
	</div>
		
	<!-- Container div to give objects a margin -->
	<div data-role="content" style="margin:30px; ">
		
		<!-- Phone -->
		<div data-role="fieldcontain" >
			<label for="phone" style="text-align:right"> Phone: </label>
			<input type="text" name="phone" id="phone" placeholder="00 00 00 00 00" />
		</div>
		
		<!-- Siret number -->
		<div data-role="fieldcontain" >
			<label for="siret" style="text-align:right"> SIRET: </label>
			<input type="text" name="siret" id="siret" placeholder="(optional)" />
		</div>
		
		<!-- Web Site -->
		<div data-role="fieldcontain" >
			<label for="website" style="text-align:right"> Web Site: </label>
			<input type="text" name="website" id="website" placeholder="http://...(optional)" />
		</div>
		
		<!-- Address -->
		<div data-role="fieldcontain" >
			<label for="address" style="text-align:right"> Address: </label>
			<input type="text" name="address" id="address" />
		</div>
					
		<!-- Next and previous buttons -->
		<div style="text-align:right; margin-right: 40px;">
			<a href="?action=ExtendedProfile" data-role="button" data-icon="arrow-l"  data-theme="e" data-inline="true">Previous</a>
			<a data-role="button" data-icon="arrow-r" data-theme="b" data-inline="true" onClick="handleNext()">Next</a>
		</div>
		
	</div>
	
</div> <!-- END - STEP 1 Association  -->


<!-- STEP 2 ASSOCIATION - Competences and Missions -->
<div data-role="page" id="step2_association" >	

	<!-- Header bar -->
	<? print_header_bar("?action=ExtendedProfile", false, "Step 2 - Competences and Missions "); ?>
		
		<!-- Competences list -->
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;">
				<?= _("Please choose form competences you need.") ?>
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
    	</div><br><br><br>
		
		<!-- Missions list -->
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;">
				<?= _("Please choose the type of mission you propose.") ?>
			</h1>
		</div>
		<br />
		<div data-role="fieldcontain" style="text-align: center" id="missions">
    		<fieldset data-role="controlgroup">
    		<? foreach (Categories::$missions as $k=>$v) :?>
				<input type="checkbox" name="missions-checkbox" id="<?=$k?>" value="<?=$k?>" />
				<label for="<?=$k?>"><?=$v?></label>
			<? endforeach ?>
    		</fieldset>
    	</div><br><br><br>
			
		<!-- Container for final wizard form -->
		<div style="text-align:right; margin-right: 40px;">
			
			<!-- Final wizard form -->
			<form action="?action=ExtendedProfile&method=create" id="profileForm" method="POST">
				
				<!-- Back link-->
				<a href="#step2_volunteer" data-role="button" data-icon="arrow-l"  data-theme="e" data-inline="true">Previous</a>
				
				<!-- MyMed basic profile fields -->
				<input type="hidden" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
				<input type="hidden" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
				<input type="hidden" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
				<input type="hidden" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
				<input type="hidden" id="picture" name="picture" value="<?= $_SESSION['user']->profilePicture ?>" />
				
				<!-- Extended profile fields -->
				<input type="hidden" id="type" name="type" value="association" />
				<input type="hidden" id="siret" name="siret" value="" />
				<input type="hidden" id="websit" name="website" value="" />
				<input type="hidden" id="phone" name="phone" value="" />
				<input type="hidden" id="address" name="address" value="" />
				<input type="hidden" id="competences" name="competences" value="" />
				<input type="hidden" id="missions" name="missions" value="" />
				
				<!-- Submit button-->
				<input type="submit" id="submit" value="Create the profile" data-inline="true" data-theme="g" />
			</form>
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
				if(!$('#phone').val()) {
					warningPopUp('Please provide a valid telephone number');
					break;
				}
				if(!$("#address").val()) {
					warningPopUp('Please specify a valid address');
					break;
				}
				
				// Fill profile fields
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

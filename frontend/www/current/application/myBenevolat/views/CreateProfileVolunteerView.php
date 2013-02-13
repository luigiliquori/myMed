<!-- ------------------------------------- -->
<!-- CreateProfileVolunteerView            -->
<!-- Wizard for create a volunteer profile -->
<!-- ------------------------------------- -->


<!-- STEP 1 VOLUNTEER -->
<div data-role="page" id="step1_volunteer">	

	<!-- Header bar -->
	<? print_header_bar("?action=ExtendedProfile", false, "Step 1 - personal info"); ?>
		
		<!-- Container div to give objects a margin -->
		<div style="margin:30px; ">
			
			<!-- Phone -->
			<div data-role="fieldcontain" >
				<label for="phone" style="text-align:right"> Phone: </label>
				<input type="text" name="phone" id="phone" placeholder="00 00 00 00 00 (Mandatory)" />
			</div>
			
			<!-- Sex -->
			<div data-role="fieldcontain" style="text-align:right">	
				<fieldset data-role="controlgroup" name="sex" id="sex">
					<legend> Sex: </legend>
			     	<input type="radio" name="sex" id="male" value="male"/>
			     	<label for="male">Male</label>
					<input type="radio" name="sex" id="female" value="female"/>
			     	<label for="female">Female</label>
				</fieldset>
			</div>
			
			<!-- Work -->
			<div data-role="fieldcontain" id="work" style="text-align:right">	
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
			
			<!-- Next and previous buttons -->
			<div style="text-align:right; margin-right: 40px;">
				<a href="?action=ExtendedProfile" data-role="button" data-icon="arrow-l"  data-theme="e" data-inline="true">Previous</a>
				<a data-role="button" data-icon="arrow-r" data-theme="b" data-inline="true" onClick="handleNext()">Next</a>
			</div>
		</div>
	
</div> <!-- END - STEP 1 VOLUNTEER -->


<!-- STEP 2 VOLUNTEER - Competences -->
<div data-role="page" id="step2_volunteer" >	

	<!-- Header bar -->
	<? print_header_bar("#step1_volunteer", false, "Step 2 - competences"); ?>
		
			<!-- Competences list -->
			<div data-role="header" data-theme="e">
				<h1 style="white-space: normal;">
					<?= _("Please choose form 1 to 4 competences.") ?>
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
			
			<!-- Next and previous buttons -->
			<div style="text-align:right; margin-right: 40px;">
				<a href="#step1_volunteer" data-role="button" data-icon="arrow-l"  data-theme="e" data-inline="true"> Previous </a>
				<a data-role="button" data-icon="arrow-r" data-theme="b" data-inline="true" onClick="handleNext();"> Next </a>
			</div>
		
</div> <!-- END - STEP 2 VOLUNTEER -->


<!-- STEP 3 VOLUNTEER - Competences -->
<div data-role="page" id="step3_volunteer" >	

	<!-- Header bar -->
	<? print_header_bar("#step2_volunteer", false, "Step 3 - missions and mobility"); ?>
	
		<!-- Missions list -->
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;">
				<?= _("Please choose the type of mission.") ?>
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
    	
    	<!-- mobilite list -->
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;">
				<?= _("Please choose your mobilite.") ?>
			</h1>
		</div>
		<br />
		<div data-role="fieldcontain" style="text-align: center" id="mobilite">
    		<fieldset data-role="controlgroup">
    		<? foreach (Categories::$mobilite as $k=>$v) :?>
				<input type="checkbox" name="mobilite-checkbox" id="<?=$k?>" value="<?=$k?>" />
				<label for="<?=$k?>"><?=$v?></label>
			<? endforeach ?>
    		</fieldset>
    	</div>
		
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
				<input type="hidden" id="type" name="type" value="volunteer" />
				<input type="hidden" id="sex" name="sex" />
				<input type="hidden" id="phone" name="phone" value="" />
				<input type="hidden" id="work" name="work" value="" />
				<input type="hidden" id="competences" name="competences" value="" />
				<input type="hidden" id="missions" name="missions" value="" />
				<input type="hidden" id="mobilite" name="mobilite" value="" />
				
				<!-- Submit button-->
				<input type="submit" id="submit" value="Create the profile" data-inline="true" data-theme="g" />
			</form>
		</div>
	
</div> <!-- END - STEP 3 VOLUNTEER -->



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
			
			$("input[id=sex]").val(profile.sex);
			$("input[id=phone]").val(profile.phone);
			$("input[id=work]").val(profile.work);
			
			var competences = "";
			for(var i=0; i<profile.competences.size(); i++)
				competences = competences + profile.competences[i].value + " ";
			$("input[id=competences]").val(competences);

			var missions = "";
			for(var i=0; i<profile.missions.size(); i++)
				missions = missions + profile.missions[i].value + " ";
			$("input[id=missions]").val(missions);

			var mobilite = "";
			for(var i=0; i<profile.mobilite.size(); i++)
				mobilite = mobilite + profile.mobilite[i].value + " ";
			$("input[id=mobilite]").val(mobilite);

			// Submit the form
			return true;
		} else {
			
			return false; 
		}
			
			
	});
	
	/* Volunteer profile */
	function volunteerProfile() {
		this.phone;
		this.sex;
		this.work;
		this.competences;
		this.missions;
		this.mobilite;
	}

	/* Store the profile data */
	var profile = new volunteerProfile();
	
	/* Handle the next button press event in the form */
	function handleNext() {

		// Check the current wizard page 
		switch($.mobile.activePage.attr('id')) {

			// Step 2 
			case 'step1_volunteer':
				// Validate fields
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
				// Fill profile fields
				profile.phone = $('#phone').val();
				profile.sex = $('#sex :radio:checked').val();
				profile.work = $('#work :radio:checked').val();
				$.mobile.changePage('#step2_volunteer');
				break;

			// Step 2 
			case 'step2_volunteer':
				// Validate fields
				var n_competences = $("input[name*=competences]:checked").size(); 
				if(!(n_competences>=1 && n_competences<=4)) {
					warningPopUp('You must choose from 1 to 4 competences');
					break;
				}
				profile.competences = $("input[name*=competences]:checked");
				$.mobile.changePage('#step3_volunteer');
				break;

			// Step 3 - final wizard step
			case 'step3_volunteer':
				// Validate fields 
				if($("input[name*=missions]:checked").size()<1) {
					warningPopUp('You must choose at least 1 mission');
					return false;
				}
				if($("input[name*=mobilite]:checked").size()<1) {
					warningPopUp('You must choose at least 1 mobility');
					return false;
				}
				profile.missions = $("input[name*=missions]:checked");
				profile.mobilite = $("input[name*=mobilite]:checked");
				break;

		}

		return true;
	}

	/* Show a warning pop up */ 
	function warningPopUp(message) {
		$("#notificationPopup").popup();
		//$("#notificationPopup").popup({ history: false });
		$("#popupMessage").text(message);
		$("#notificationPopup").popup("open");
	}

</script>

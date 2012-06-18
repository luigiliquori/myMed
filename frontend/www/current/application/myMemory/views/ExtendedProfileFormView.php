<? include("header.php"); ?>
<? include("notifications.php")?>
<!--  Javascript that disables the submit button as long as the checkbox is not checked -->
<script type="text/javascript">
	$('#agreement').change(function() {
		if (this.checked)
			$('#submitButton').button('enable');
		else
			$('#submitButton').button('disable');
	});
</script>

<!-- Header -->
<div data-role="header" data-position="inline">
	<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete">Exit</a>
	<h1>Profile</h1>
</div>

<div data-role="content" data-theme="a">
	<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm">


		<!-- LEVEL OF DISEASE -->
		<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Level of the disease :</div>
				<div class="ui-controlgroup-controls">
			     	<input type="radio" name="diseaseLevel" id="disease_low" value="1" checked="checked" />
			     	<label for="disease_low">Low</label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_moderate" value="2"  />
			     	<label for="disease_moderate">Moderate</label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_advanced" value="3"  />
			     	<label for="disease_advanced">Advanced</label>
			     </div>
			</fieldset>
		</div>

		<!-- CAREGIVER -->
		<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Your personnal Caregiver :</div>
				<div class="ui-controlgroup-controls">
					<label for="CareGiverName" class="ui-hidden-accessible">Caregiver name : </label>
					<input type="text" name="CareGiverName" name="CareGiverName" value="" placeholder="Caregiver name" />
					
					<label for="CareGiverEmail" class="ui-hidden-accessible">Caregiver e-mail : </label>
					<input type="text" name="CareGiverEmail" name="CareGiverEmail" value="" placeholder="Caregiver e-mail"/>
					
					<label for="CareGiverPhone" class="ui-hidden-accessible">Caregiver phone : </label>
					<input type="text" name="CareGiverPhone" name="CareGiverPhone" value="" placeholder="Caregiver phone"/>
				</div>
			</fieldset>
		</div>
		
		<!-- DOCTOR -->
		<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Your doctor :</div>
				<div class="ui-controlgroup-controls">
					<label for="DoctorName" class="ui-hidden-accessible">Doctor name : </label>
					<input type="text" name="DoctorName" name="DoctorName" value="" placeholder="Doctor name"/>
					
					<label for="DoctorEmail" class="ui-hidden-accessible">Doctor e-mail : </label>
					<input type="text" name="DoctorEmail" name="DoctorEmail" value="" placeholder="Doctor e-mail"/>
					
					<label for="DoctorPhone" class="ui-hidden-accessible">Doctor phone : </label>
					<input type="text" name="DoctorPhone" name="DoctorPhone" value="" placeholder="Doctor phone"/>
				</div>
			</fieldset>
		</div>
		
		<!-- CALLING LIST -->
		<p>MyMemory can call for you a list up to 4 persons in case of emergency.<br />
		The first person called is your caregiver, and the last one will be the emergency services.<br />
		You can fill another 2 persons that will be called in between. This is not mandatory.</p>
		
		
		<div data-role="collapsible-set" data-theme="b" data-content-theme="c">
			<div data-role="collapsible">
				<h3>Second person to call</h3>
				<div class="ui-controlgroup-controls">
					<label for="CL_name_1" class="ui-hidden-accessible">Name : </label>
					<input type="text" name="CL_name_1" name="CL_name_1" value="" placeholder="Name"/>
					
					<label for="CL_phone_1" class="ui-hidden-accessible">Phone number : </label>
					<input type="text" name="CL_phone_1" name="CL_phone_1" value="" placeholder="Phone number"/>
				</div>
			</div>
			
			<div data-role="collapsible">
				<h3>Third person to call</h3>
				<div class="ui-controlgroup-controls">
					<label for="CL_name_2" class="ui-hidden-accessible">Name : </label>
					<input type="text" name="CL_name_2" name="CL_name_2" value="" placeholder="Name"/>
					
					<label for="CL_phone_2" class="ui-hidden-accessible">Phone number : </label>
					<input type="text" name="CL_phone_2" name="CL_phone_2" value="" placeholder="Phone number"/>
				</div>
			</div>
			
		</div>
		
		<!-- Agreements  -->
		<input type="checkbox" name="agreement" id="agreement" />
		<label for="agreement">I understand that this application needs to geotag me in case of emergency and I therefore give my consent.</label>
		
		<input type="submit" data-role="button" id="submitButton" value="Save" disabled="true" data-theme="b"/>
	</form>
</div>
<? include("footer.php"); ?>
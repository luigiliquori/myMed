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
 	<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _('Exit') ?></a>
 	<h1></h1>
</div>

<div data-role="content" data-theme="a">
	<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
		<input type="hidden" name="form" value="create" />

		<!-- HOME -->
		<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Your personnal address :</div>
				<div class="ui-controlgroup-controls">
					<label for="home" class="ui-hidden-accessible">Address : </label>
					<input type="text" name="home" name="home" value="" placeholder="Address"/>
				</div>
			</fieldset>
		</div>
		
		
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
					<label for="CareGiverFirstname" class="ui-hidden-accessible">Caregiver Firstname : </label>
					<input type="text" name="CareGiverFirstname" name="CareGiverFirstname" value="" placeholder="Caregiver firstname" />
					
					<label for="CareGiverLastname" class="ui-hidden-accessible">Caregiver Lastname : </label>
					<input type="text" name="CareGiverLastname" name="CareGiverLastname" value="" placeholder="Caregiver lastname" />
					
					<label for="CareGiverNickname" class="ui-hidden-accessible">Caregiver Nickname : </label>
					<input type="text" name="CareGiverNickname" name="CareGiverNickname" value="" placeholder="Caregiver nickname" />
					
					<label for="CareGiverAddress" class="ui-hidden-accessible">Caregiver address : </label>
					<input type="text" name="CareGiverAddress" name="CareGiverAddress" value="" placeholder="Caregiver address"/>
					
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
					<label for="DoctorFirstname" class="ui-hidden-accessible">Doctor Firstname : </label>
					<input type="text" name="DoctorFirstname" name="DoctorFirstname" value="" placeholder="Doctor firstname" />
					
					<label for="DoctorLastname" class="ui-hidden-accessible">Doctor Lastname : </label>
					<input type="text" name="DoctorLastname" name="DoctorLastname" value="" placeholder="Doctor lastname" />
					
					<label for="DoctorNickname" class="ui-hidden-accessible">Doctor Nickname : </label>
					<input type="text" name="DoctorNickname" name="DoctorNickname" value="" placeholder="Doctor nickname" />
					
					<label for="DoctorAddress" class="ui-hidden-accessible">Doctor address : </label>
					<input type="text" name="DoctorAddress" name="DoctorAddress" value="" placeholder="Doctor address"/>
					
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
		
		
			<div data-role="collapsible">
				<h3>Second person to call</h3>
				<div class="ui-controlgroup-controls">
					<label for="CL_Firstname_1" class="ui-hidden-accessible">Firstname : </label>
					<input type="text" name="CL_Firstname_1" name="CL_Firstname_1" value="" placeholder="Firstname" />
					
					<label for="CL_Lastname_1" class="ui-hidden-accessible">Lastname : </label>
					<input type="text" name="CL_Lastname_1" name="CL_Lastname_1" value="" placeholder="Lastname" />
					
					<label for="CL_Nickname_1" class="ui-hidden-accessible">Nickname : </label>
					<input type="text" name="CL_Nickname_1" name="CL_Nickname_1" value="" placeholder="Nickname" />
					
					<label for="CL_address_1" class="ui-hidden-accessible">Address : </label>
					<input type="text" name="CL_address_1" name="CL_address_1" value="" placeholder="Address"/>
					
					<label for="CL_email_1" class="ui-hidden-accessible">E-Mail : </label>
					<input type="text" name="CL_email_1" name="CL_email_1" value="" placeholder="e-mail"/>
					
					<label for="CL_phone_1" class="ui-hidden-accessible">Phone number : </label>
					<input type="text" name="CL_phone_1" name="CL_phone_1" value="" placeholder="Phone number"/>
				</div>
			</div>
			
			<div data-role="collapsible">
				<h3>Third person to call</h3>
				<div class="ui-controlgroup-controls">
					<label for="CL_Firstname_2" class="ui-hidden-accessible">Firstname : </label>
					<input type="text" name="CL_Firstname_2" name="CL_Firstname_2" value="" placeholder="Firstname" />
					
					<label for="CL_Lastname_2" class="ui-hidden-accessible">Lastname : </label>
					<input type="text" name="CL_Lastname_2" name="CL_Lastname_2" value="" placeholder="Lastname" />
					
					<label for="CL_Nickname_2" class="ui-hidden-accessible">Nickname : </label>
					<input type="text" name="CL_Nickname_2" name="CL_Nickname_2" value="" placeholder="Nickname" />
					
					<label for="CL_address_2" class="ui-hidden-accessible">Address : </label>
					<input type="text" name="CL_address_2" name="CL_address_2" value="" placeholder="Address"/>
					
					<label for="CL_email_2" class="ui-hidden-accessible">E-Mail : </label>
					<input type="text" name="CL_email_2" name="CL_email_2" value="" placeholder="e-mail"/>
					
					<label for="CL_phone_2" class="ui-hidden-accessible">Phone number : </label>
					<input type="text" name="CL_phone_2" name="CL_phone_2" value="" placeholder="Phone number"/>
				</div>
			</div>
			
		
		<!-- Agreements  -->
		<input type="checkbox" name="agreement" id="agreement" />
		<label for="agreement">I understand that this application needs to geotag me in case of emergency and I therefore give my consent.</label>
		<input type="submit" data-role="button" id="submitButton" name="create" value="<?= _('Save') ?>" disabled="true" data-theme="b"/>
		
	</form>
</div>
<? include("footer.php"); ?>
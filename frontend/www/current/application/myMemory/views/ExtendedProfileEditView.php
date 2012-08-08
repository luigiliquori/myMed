<? include("header.php"); ?>
<? include("notifications.php")?>


<!-- Header -->
<div data-role="header" data-position="inline">
	<a href="?action=ExtendedProfile" data-role="button"  data-icon="back">Back</a>
	<h1>Profile</h1>
	<a href="" data-role="button" data-theme="b" data-icon="check" onclick='document.ExtendedProfileForm.submit();'>Save</a>
</div>

<div data-role="content" data-theme="a">
	<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
		<input type="hidden" name="form" value="edit" />

		<!-- HOME -->
		<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Your personnal address :</div>
				<div class="ui-controlgroup-controls">
					<label for="home" class="ui-hidden-accessible">Address : </label>
					<input type="text" name="home" name="home" value="<?=$_SESSION['ExtendedProfile']->home ?>" placeholder="Address"/>
				</div>
			</fieldset>
		</div>
		
		
		<!-- LEVEL OF DISEASE -->
		<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Level of the disease :</div>
				<div class="ui-controlgroup-controls">
			     	<input type="radio" name="diseaseLevel" id="disease_low" value="1" <?php if ($_SESSION['ExtendedProfile']->diseaseLevel == 1) echo 'checked="checked"'; ?> />
			     	<label for="disease_low">Low</label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_moderate" value="2" <?php if ($_SESSION['ExtendedProfile']->diseaseLevel == 2) echo 'checked="checked"'; ?> />
			     	<label for="disease_moderate">Moderate</label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_advanced" value="3" <?php if ($_SESSION['ExtendedProfile']->diseaseLevel == 3) echo 'checked="checked"'; ?> />
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
					<input type="text" name="CareGiverName" name="CareGiverName" value="<?= $_SESSION['ExtendedProfile']->careGiver->name ?>" placeholder="Caregiver name" />
					
					<label for="CareGiverAddress" class="ui-hidden-accessible">Caregiver address : </label>
					<input type="text" name="CareGiverAddress" name="CareGiverAddress" value="<?= $_SESSION['ExtendedProfile']->careGiver->address ?>" placeholder="Caregiver address"/>
					
					<label for="CareGiverEmail" class="ui-hidden-accessible">Caregiver e-mail : </label>
					<input type="text" name="CareGiverEmail" name="CareGiverEmail" value="<?= $_SESSION['ExtendedProfile']->careGiver->email ?>" placeholder="Caregiver e-mail"/>
					
					<label for="CareGiverPhone" class="ui-hidden-accessible">Caregiver phone : </label>
					<input type="text" name="CareGiverPhone" name="CareGiverPhone" value="<?= $_SESSION['ExtendedProfile']->careGiver->phone ?>" placeholder="Caregiver phone"/>
				</div>
			</fieldset>
		</div>
		
		<!-- DOCTOR -->
		<div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Your doctor :</div>
				<div class="ui-controlgroup-controls">
					<label for="DoctorName" class="ui-hidden-accessible">Doctor name : </label>
					<input type="text" name="DoctorName" name="DoctorName" value="<?= $_SESSION['ExtendedProfile']->doctor->name ?>" placeholder="Doctor name"/>
					
					<label for="DoctorAddress" class="ui-hidden-accessible">Doctor address : </label>
					<input type="text" name="DoctorAddress" name="DoctorAddress" value="<?= $_SESSION['ExtendedProfile']->doctor->address ?>" placeholder="Doctor address"/>
					
					<label for="DoctorEmail" class="ui-hidden-accessible">Doctor e-mail : </label>
					<input type="text" name="DoctorEmail" name="DoctorEmail" value="<?= $_SESSION['ExtendedProfile']->doctor->email ?>" placeholder="Doctor e-mail"/>
					
					<label for="DoctorPhone" class="ui-hidden-accessible">Doctor phone : </label>
					<input type="text" name="DoctorPhone" name="DoctorPhone" value="<?= $_SESSION['ExtendedProfile']->doctor->phone ?>" placeholder="Doctor phone"/>
				</div>
			</fieldset>
		</div>
		
		<!-- CALLING LIST -->
		<p>MyMemory can call for you a list up to 4 persons in case of emergency.<br />
		The first person called is your caregiver, and the last one will be the emergency services.<br />
		You can fill another 2 persons that will be called in between. This is not mandatory.</p>
		
		<?php 
		// Do we have extra persons in callingList, aside from the caregiver and emergency
			$callingList_lenght = count($_SESSION['ExtendedProfile']->callingList);
			if ($callingList_lenght > 2){
				if($callingList_lenght == 4){
					// case where there is 2 persons
					$p2_name	= $_SESSION['ExtendedProfile']->callingList['2']->name;
					$p2_address = $_SESSION['ExtendedProfile']->callingList['2']->address;
					$p2_email	= $_SESSION['ExtendedProfile']->callingList['2']->email;
					$p2_phone	= $_SESSION['ExtendedProfile']->callingList['2']->phone;
				}
				$p1_name	= $_SESSION['ExtendedProfile']->callingList['1']->name;
				$p1_address = $_SESSION['ExtendedProfile']->callingList['1']->address;
				$p1_email	= $_SESSION['ExtendedProfile']->callingList['1']->email;
				$p1_phone	= $_SESSION['ExtendedProfile']->callingList['1']->phone;
				
			}
		
		?>

		<div data-role="collapsible">
			<h3>Second person to call</h3>
			<div class="ui-controlgroup-controls">
				<label for="CL_name_1" class="ui-hidden-accessible">Name : </label>
				<input type="text" name="CL_name_1" name="CL_name_1" value="<?php if(isset($p1_name)) echo $p1_name;?>" placeholder="Name"/>
				
				<label for="CL_address_1" class="ui-hidden-accessible">Address : </label>
				<input type="text" name="CL_address_1" name="CL_address_1" value="<?php if(isset($p1_address)) echo $p1_address;?>" placeholder="Address"/>
				
				<label for="CL_email_1" class="ui-hidden-accessible">E-Mail : </label>
				<input type="text" name="CL_email_1" name="CL_email_1" value="<?php if(isset($p1_email)) echo $p1_email;?>" placeholder="e-mail"/>
				
				<label for="CL_phone_1" class="ui-hidden-accessible">Phone number : </label>
				<input type="text" name="CL_phone_1" name="CL_phone_1" value="<?php if(isset($p1_phone)) echo $p1_phone;?>" placeholder="Phone number"/>
			</div>

			
			<div data-role="collapsible">
				<h3>Third person to call</h3>
				<div class="ui-controlgroup-controls">
					<label for="CL_name_2" class="ui-hidden-accessible">Name : </label>
					<input type="text" name="CL_name_2" name="CL_name_2" value="<?php if(isset($p2_name)) echo $p2_name;?>" placeholder="Name"/>
					
					<label for="CL_address_2" class="ui-hidden-accessible">Address : </label>
					<input type="text" name="CL_address_2" name="CL_address_2" value="<?php if(isset($p2_address)) echo $p2_address;?>" placeholder="Address"/>
					
					<label for="CL_email_2" class="ui-hidden-accessible">E-Mail : </label>
					<input type="text" name="CL_email_2" name="CL_email_2" value="<?php if(isset($p2_email)) echo $p2_email;?>" placeholder="e-mail"/>
					
					<label for="CL_phone_2" class="ui-hidden-accessible">Phone number : </label>
					<input type="text" name="CL_phone_2" name="CL_phone_2" value="<?php if(isset($p2_phone)) echo $p2_phone;?>" placeholder="Phone number"/>
				</div>
			</div>
			
		</div>
		<input type="hidden" name="agreement" value="true" />
		<input type="submit" data-role="button" id="submitButton"  value="Save" data-theme="b"/>
	</form>
</div>
<? include("footer.php"); ?>
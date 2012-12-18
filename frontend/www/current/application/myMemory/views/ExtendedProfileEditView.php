<? include("header.php"); ?>
<? include("notifications.php")?>
<script>

$("#step1").live("pageinit", function() {
	$("form.msform").live("submit", handleMSForm);    
});

var formData = {};

function handleMSForm(e) {

        var next = "";
        
        //gather the fields
        var data = $(this).serializeArray();

        //store them - assumes unique names
        for(var i=0; i<data.length; i++) {
            //If nextStep, it's our metadata, don't store it in formdata
            if(data[i].name=="nextStep") { next=data[i].value; continue; }
            //if we have it, add it to a list. This is not "comma" safe.
            if(formData.hasOwnProperty(data[i].name)) formData[data[i].name] += ","+data[i].value;
            else formData[data[i].name] = data[i].value;
        }

        //now - we need to go the next page...
        //if next step isn't a full url, we assume internal link
        //logic will be, if ?action=something, do a post
        if(next.indexOf("?") == -1) {
            var nextPage = "#" + next;
            $.mobile.changePage(nextPage);
        } else {
            $.mobile.changePage(next, {type:"post",data:formData});
        }
        e.preventDefault();
    
};


</script>
<div data-role="page" id="step1">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("EditProfile"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_EditProfileStep1Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="form" value="edit" />
			<input type="hidden" name="nextStep" value="step2" />
	
			<!-- HOME -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
				<legend><?= _("Domicile");?></legend>
				<label for="home" class="ui-hidden-accessible"><?= _("Domicile");?></label>
				<input type="text" name="home" name="home" value="<?=$_SESSION['ExtendedProfile']->home ?>" placeholder="<?= _("Domicile");?>"/>
				</fieldset>
			</div>
			
			
			<!-- LEVEL OF DISEASE -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<legend><?= _("DiseaseLevel"); ?></legend>
			     	<input type="radio" name="diseaseLevel" id="disease_low" value="1" <?php if ($_SESSION['ExtendedProfile']->diseaseLevel == 1) echo 'checked="checked"'; ?> />
			     	<label for="disease_low"><?= _("DiseaseLevel1"); ?></label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_moderate" value="2" <?php if ($_SESSION['ExtendedProfile']->diseaseLevel == 2) echo 'checked="checked"'; ?> />
			     	<label for="disease_moderate"><?= _("DiseaseLevel2"); ?></label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_advanced" value="3" <?php if ($_SESSION['ExtendedProfile']->diseaseLevel == 3) echo 'checked="checked"'; ?> />
			     	<label for="disease_advanced"><?= _("DiseaseLevel3"); ?></label>
			     
				</fieldset>
			</div>
			
			<input type="submit" name="submit1" data-theme="b" value="<?= _("Next"); ?>" />
		</form>
	</div>
</div>

<div data-role="page" id="step2">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("EditProfile"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_EditProfileStep2Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="step3" />			
	
			<!-- CAREGIVER -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyCaregiver"); ?></div>
					<div class="ui-controlgroup-controls">
						<label for="CareGiverFirstname" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
						<input type="text" name="CareGiverFirstname" name="CareGiverFirstname" value="<?= $_SESSION['ExtendedProfile']->doctor->firstname ?>" placeholder="<?= _("FirstName"); ?>" />
						
						<label for="CareGiverLastname" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
						<input type="text" name="CareGiverLastname" name="CareGiverLastname" value="<?= $_SESSION['ExtendedProfile']->doctor->lastname ?>" placeholder="<?= _("LastName"); ?>" />
						
						<label for="CareGiverNickname" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
						<input type="text" name="CareGiverNickname" name="CareGiverNickname" value="<?= $_SESSION['ExtendedProfile']->doctor->nickname ?>" placeholder="<?= _("NickName"); ?>" />
						
						<label for="CareGiverAddress" class="ui-hidden-accessible"><?= _("Address"); ?></label>
						<input type="text" name="CareGiverAddress" name="CareGiverAddress" value="<?= $_SESSION['ExtendedProfile']->doctor->address ?>" placeholder="<?= _("Address"); ?>"/>
						
						<label for="CareGiverEmail" class="ui-hidden-accessible"><?= _("Email"); ?></label>
						<input type="text" name="CareGiverEmail" name="CareGiverEmail" value="<?= $_SESSION['ExtendedProfile']->doctor->email ?>" placeholder="<?= _("Email"); ?>"/>
						
						<label for="CareGiverPhone" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
						<input type="text" name="CareGiverPhone" name="CareGiverPhone" value="<?= $_SESSION['ExtendedProfile']->doctor->phone ?>" placeholder="<?= _("Phone"); ?>"/>
					</div>
				</fieldset>
			</div>
			<input type="submit" name="submit2" data-theme="b" value="<?= _("Next"); ?>" />
		</form>
	</div>
</div>

<div data-role="page" id="step3">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("EditProfile"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_EditProfileStep3Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="step4" />			
			<!-- DOCTOR -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyDoctor"); ?></div>
					<div class="ui-controlgroup-controls">
						<label for="DoctorFirstname" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
						<input type="text" name="DoctorFirstname" name="DoctorFirstname" value="<?= $_SESSION['ExtendedProfile']->doctor->firstname ?>" placeholder="<?= _("FirstName"); ?>" />
						
						<label for="DoctorLastname" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
						<input type="text" name="DoctorLastname" name="DoctorLastname" value="<?= $_SESSION['ExtendedProfile']->doctor->lastname ?>" placeholder="<?= _("LastName"); ?>" />
						
						<label for="DoctorNickname" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
						<input type="text" name="DoctorNickname" name="DoctorNickname" value="<?= $_SESSION['ExtendedProfile']->doctor->nickname ?>" placeholder="<?= _("NickName"); ?>" />
						
						<label for="DoctorAddress" class="ui-hidden-accessible"><?= _("Address"); ?></label>
						<input type="text" name="DoctorAddress" name="DoctorAddress" value="<?= $_SESSION['ExtendedProfile']->doctor->address ?>" placeholder="<?= _("Address"); ?>"/>
						
						<label for="DoctorEmail" class="ui-hidden-accessible"><?= _("Email"); ?></label>
						<input type="text" name="DoctorEmail" name="DoctorEmail" value="<?= $_SESSION['ExtendedProfile']->doctor->email ?>" placeholder="<?= _("Email"); ?>"/>
						
						<label for="DoctorPhone" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
						<input type="text" name="DoctorPhone" name="DoctorPhone" value="<?= $_SESSION['ExtendedProfile']->doctor->phone ?>" placeholder="<?= _("Phone"); ?>"/>
					</div>
				</fieldset>
			</div>
			<input type="submit" name="submit3" data-theme="b" value="<?= _("Next"); ?>" />
		</form>
	</div>
</div>

<div data-role="page" id="step4">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("EditProfile"); ?></h1>
		<a href="?action=main"  data-role="button" class="ui-btn-left" data-icon="back"><?= _("Back"); ?></a>
	 	<a href="#" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_EditProfileStep4Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="?action=ExtendedProfile" />
			<!-- CALLING LIST -->
			
			<?php 
			// Do we have extra persons in callingList, aside from the caregiver and emergency
				$callingList_lenght = count($_SESSION['ExtendedProfile']->callingList);
				if ($callingList_lenght > 2){
					if($callingList_lenght == 4){
						// case where there is 2 persons
						$p2_firstname	= $_SESSION['ExtendedProfile']->callingList['2']->firstname;
						$p2_lastname	= $_SESSION['ExtendedProfile']->callingList['2']->lastname;
						$p2_nickname	= $_SESSION['ExtendedProfile']->callingList['2']->nickname;
						$p2_address 	= $_SESSION['ExtendedProfile']->callingList['2']->address;
						$p2_email		= $_SESSION['ExtendedProfile']->callingList['2']->email;
						$p2_phone		= $_SESSION['ExtendedProfile']->callingList['2']->phone;
					}
					$p1_firstname	= $_SESSION['ExtendedProfile']->callingList['1']->firstname;
					$p1_lastname	= $_SESSION['ExtendedProfile']->callingList['1']->lastname;
					$p1_nickname	= $_SESSION['ExtendedProfile']->callingList['1']->nickname;
					$p1_address 	= $_SESSION['ExtendedProfile']->callingList['1']->address;
					$p1_email		= $_SESSION['ExtendedProfile']->callingList['1']->email;
					$p1_phone		= $_SESSION['ExtendedProfile']->callingList['1']->phone;
					
				}
			
			?>
	
			<div data-role="collapsible" data-collapsed="false">
				<h3><?= _("callingslot1"); ?></h3>
				<div class="ui-controlgroup-controls">
					<label for="CL_Firstname_1" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
					<input type="text" name="CL_Firstname_1" name="CL_Firstname_1" value="<?php if(isset($p1_firstname)) echo $p1_firstname;?>" placeholder="<?= _("FirstName"); ?>" />
					
					<label for="CL_Lastname_1" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
					<input type="text" name="CL_Lastname_1" name="CL_Lastname_1" value="<?php if(isset($p1_lastname)) echo $p1_lastname;?>" placeholder="<?= _("LastName"); ?>" />
					
					<label for="CL_Nickname_1" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
					<input type="text" name="CL_Nickname_1" name="CL_Nickname_1" value="<?php if(isset($p1_nickname)) echo $p1_nickname;?>" placeholder="<?= _("NickName"); ?>" />
					
					<label for="CL_address_1" class="ui-hidden-accessible"><?= _("Address"); ?></label>
					<input type="text" name="CL_address_1" name="CL_address_1" value="<?php if(isset($p1_address)) echo $p1_address;?>" placeholder="<?= _("Address"); ?>"/>
					
					<label for="CL_email_1" class="ui-hidden-accessible"><?= _("Email"); ?></label>
					<input type="text" name="CL_email_1" name="CL_email_1" value="<?php if(isset($p1_email)) echo $p1_email;?>" placeholder="<?= _("Email"); ?>"/>
					
					<label for="CL_phone_1" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
					<input type="text" name="CL_phone_1" name="CL_phone_1" value="<?php if(isset($p1_phone)) echo $p1_phone;?>" placeholder="<?= _("Phone"); ?>"/>
				</div>
	
				
				<div data-role="collapsible">
					<h3><?= _("callingslot2"); ?></h3>
						<div class="ui-controlgroup-controls">
						<label for="CL_Firstname_2" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
						<input type="text" name="CL_Firstname_2" name="CL_Firstname_2" value="<?php if(isset($p2_firstname)) echo $p2_firstname;?>" placeholder="<?= _("FirstName"); ?>" />
						
						<label for="CL_Lastname_2" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
						<input type="text" name="CL_Lastname_2" name="CL_Lastname_2" value="<?php if(isset($p2_lastname)) echo $p2_lastname;?>" placeholder="<?= _("LastName"); ?>" />
						
						<label for="CL_Nickname_2" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
						<input type="text" name="CL_Nickname_2" name="CL_Nickname_2" value="<?php if(isset($p2_nickname)) echo $p2_nickname;?>" placeholder="<?= _("NickName"); ?>" />
						
						<label for="CL_address_2" class="ui-hidden-accessible"><?= _("Address"); ?></label>
						<input type="text" name="CL_address_2" name="CL_address_2" value="<?php if(isset($p2_address)) echo $p2_address;?>" placeholder="<?= _("Address"); ?>"/>
						
						<label for="CL_email_2" class="ui-hidden-accessible"><?= _("Email"); ?></label>
						<input type="text" name="CL_email_2" name="CL_email_2" value="<?php if(isset($p2_email)) echo $p2_email;?>" placeholder="<?= _("Email"); ?>"/>
						
						<label for="CL_phone_2" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
						<input type="text" name="CL_phone_2" name="CL_phone_2" value="<?php if(isset($p2_phone)) echo $p2_phone;?>" placeholder="<?= _("Phone"); ?>"/>
					</div>
				</div>
				
			</div>
			<input type="hidden" name="agreement" value="true" />
			<input type="submit" data-role="button" id="submitButton"  value="<?= _('Save') ?>" data-theme="b"/>
		</form>
	</div>
</div>	
<? include("footer.php"); ?>
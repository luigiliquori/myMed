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
	 	<h1><?= _("CreateProfile"); ?></h1>
		<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _('Exit') ?></a>
		<a href="#createProfileStep1HelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_CreateProfileStep1Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="nextStep" value="step2" />
	
			<!-- HOME -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
				<legend><?= _("Domicile");?></legend>
				<label for="home" class="ui-hidden-accessible"><?= _("Domicile");?></label>
				<input type="text" name="home" name="home"  placeholder="<?= _("Domicile");?>"/>
				</fieldset>
			</div>
			
			
			<!-- LEVEL OF DISEASE -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<legend><?= _("DiseaseLevel"); ?></legend>
			     	<input type="radio" name="diseaseLevel" id="disease_low" value="1" />
			     	<label for="disease_low"><?= _("DiseaseLevel1"); ?></label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_moderate" value="2" />
			     	<label for="disease_moderate"><?= _("DiseaseLevel2"); ?></label>
			
			     	<input type="radio" name="diseaseLevel" id="disease_advanced" value="3" />
			     	<label for="disease_advanced"><?= _("DiseaseLevel3"); ?></label>
			     
				</fieldset>
			</div>
			
			<input type="submit" name="submit1" data-theme="b" value="<?= _("Next"); ?>" />
		</form>
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="createProfileStep1HelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_CreateProfileStep1Help"); ?>
		</div>
	</div>
</div>

<div data-role="page" id="step2">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("CreateProfile"); ?></h1>
	 	<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _('Exit') ?></a>
		<a href="#createProfileStep2HelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_CreateProfileStep2Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="step3" />			
	
			<!-- CAREGIVER -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyCaregiver"); ?></div>
					<div class="ui-controlgroup-controls">
						<label for="CareGiverFirstname" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
						<input type="text" name="CareGiverFirstname" name="CareGiverFirstname" placeholder="<?= _("FirstName"); ?>" />
						
						<label for="CareGiverLastname" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
						<input type="text" name="CareGiverLastname" name="CareGiverLastname" placeholder="<?= _("LastName"); ?>" />
						
						<label for="CareGiverNickname" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
						<input type="text" name="CareGiverNickname" name="CareGiverNickname" placeholder="<?= _("NickName"); ?>" />
						
						<label for="CareGiverAddress" class="ui-hidden-accessible"><?= _("Address"); ?></label>
						<input type="text" name="CareGiverAddress" name="CareGiverAddress" placeholder="<?= _("Address"); ?>"/>
						
						<label for="CareGiverEmail" class="ui-hidden-accessible"><?= _("Email"); ?></label>
						<input type="text" name="CareGiverEmail" name="CareGiverEmail"  placeholder="<?= _("Email"); ?>"/>
						
						<label for="CareGiverPhone" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
						<input type="text" name="CareGiverPhone" name="CareGiverPhone" placeholder="<?= _("Phone"); ?>"/>
					</div>
				</fieldset>
			</div>
			<input type="submit" name="submit2" data-theme="b" value="<?= _("Next"); ?>" />
		</form>
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="createProfileStep2HelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_CreateProfileStep2Help"); ?>
		</div>
	</div>
</div>

<div data-role="page" id="step3">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("CreateProfile"); ?></h1>
	 	<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _('Exit') ?></a>
		<a href="#createProfileStep3HelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_CreateProfileStep3Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="step4" />			
			<!-- DOCTOR -->
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyDoctor"); ?></div>
					<div class="ui-controlgroup-controls">
						<label for="DoctorFirstname" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
						<input type="text" name="DoctorFirstname" name="DoctorFirstname" placeholder="<?= _("FirstName"); ?>" />
						
						<label for="DoctorLastname" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
						<input type="text" name="DoctorLastname" name="DoctorLastname" placeholder="<?= _("LastName"); ?>" />
						
						<label for="DoctorNickname" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
						<input type="text" name="DoctorNickname" name="DoctorNickname" placeholder="<?= _("NickName"); ?>" />
						
						<label for="DoctorAddress" class="ui-hidden-accessible"><?= _("Address"); ?></label>
						<input type="text" name="DoctorAddress" name="DoctorAddress" placeholder="<?= _("Address"); ?>"/>
						
						<label for="DoctorEmail" class="ui-hidden-accessible"><?= _("Email"); ?></label>
						<input type="text" name="DoctorEmail" name="DoctorEmail" placeholder="<?= _("Email"); ?>"/>
						
						<label for="DoctorPhone" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
						<input type="text" name="DoctorPhone" name="DoctorPhone" placeholder="<?= _("Phone"); ?>"/>
					</div>
				</fieldset>
			</div>
			<input type="submit" name="submit3" data-theme="b" value="<?= _("Next"); ?>" />
		</form>
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="createProfileStep3HelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_CreateProfileStep3Help"); ?>
		</div>
	</div>
</div>

<div data-role="page" id="step4">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("CreateProfile"); ?></h1>
	 	<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _('Exit') ?></a>
		<a href="#createProfileStep4HelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_CreateProfileStep4Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="step5" />
			<!-- CALLING LIST -->
	
			<div data-role="collapsible" data-collapsed="false">
				<h3><?= _("callingslot1"); ?></h3>
				<div class="ui-controlgroup-controls">
					<label for="CL_Firstname_1" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
					<input type="text" name="CL_Firstname_1" name="CL_Firstname_1" placeholder="<?= _("FirstName"); ?>" />
					
					<label for="CL_Lastname_1" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
					<input type="text" name="CL_Lastname_1" name="CL_Lastname_1" placeholder="<?= _("LastName"); ?>" />
					
					<label for="CL_Nickname_1" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
					<input type="text" name="CL_Nickname_1" name="CL_Nickname_1" placeholder="<?= _("NickName"); ?>" />
					
					<label for="CL_address_1" class="ui-hidden-accessible"><?= _("Address"); ?></label>
					<input type="text" name="CL_address_1" name="CL_address_1" placeholder="<?= _("Address"); ?>"/>
					
					<label for="CL_email_1" class="ui-hidden-accessible"><?= _("Email"); ?></label>
					<input type="text" name="CL_email_1" name="CL_email_1" placeholder="<?= _("Email"); ?>"/>
					
					<label for="CL_phone_1" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
					<input type="text" name="CL_phone_1" name="CL_phone_1" placeholder="<?= _("Phone"); ?>"/>
				</div>
	
				
				<div data-role="collapsible">
					<h3><?= _("callingslot2"); ?></h3>
						<div class="ui-controlgroup-controls">
						<label for="CL_Firstname_2" class="ui-hidden-accessible"><?= _("FirstName"); ?></label>
						<input type="text" name="CL_Firstname_2" name="CL_Firstname_2" placeholder="<?= _("FirstName"); ?>" />
						
						<label for="CL_Lastname_2" class="ui-hidden-accessible"><?= _("LastName"); ?></label>
						<input type="text" name="CL_Lastname_2" name="CL_Lastname_2" placeholder="<?= _("LastName"); ?>" />
						
						<label for="CL_Nickname_2" class="ui-hidden-accessible"><?= _("NickName"); ?></label>
						<input type="text" name="CL_Nickname_2" name="CL_Nickname_2" placeholder="<?= _("NickName"); ?>" />
						
						<label for="CL_address_2" class="ui-hidden-accessible"><?= _("Address"); ?></label>
						<input type="text" name="CL_address_2" name="CL_address_2" placeholder="<?= _("Address"); ?>"/>
						
						<label for="CL_email_2" class="ui-hidden-accessible"><?= _("Email"); ?></label>
						<input type="text" name="CL_email_2" name="CL_email_2" placeholder="<?= _("Email"); ?>"/>
						
						<label for="CL_phone_2" class="ui-hidden-accessible"><?= _("Phone"); ?></label>
						<input type="text" name="CL_phone_2" name="CL_phone_2" placeholder="<?= _("Phone"); ?>"/>
					</div>
				</div>
				
			</div>
			<input type="submit" data-role="button" id="submitButton"  value="<?= _('Next') ?>" data-theme="b"/>
		</form>
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="createProfileStep4HelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_CreateProfileStep4Help"); ?>
		</div>
	</div>
</div>
<div data-role="page" id="step5">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("CreateProfile"); ?></h1>
	 	<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _('Exit') ?></a>
		<a href="#createProfileStep5HelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_CreateProfileStep5Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="step6" />
			<!-- Agreements  -->
			<input type="checkbox" name="agreement" id="agreement" />
			<label for="agreement"><?= _("myMemory_Agreement"); ?></label>

			<input type="submit" name="submit5" data-theme="b" value="<?= _("Next"); ?>" />
		</form>
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="createProfileStep5HelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_CreateProfileStep5Help"); ?>
		</div>
	</div>
</div>
<div data-role="page" id="step6">
	<!-- Header -->
	<div data-role="header" data-position="inline">
	 	<h1><?= _("CreateProfile"); ?></h1>
		<a href="?action=logout" rel="external" data-role="button" data-theme="r" class="ui-btn-left" data-icon="delete"><?= _('Exit') ?></a>
		<a href="#createProfileStep6HelpPopup" data-role="button" data-theme="e" class="ui-btn-right" data-icon="info" data-rel="popup"><?= _("Help"); ?></a>
	</div>

	<div data-role="content" data-theme="a">
	
	
		<div class="description-box">
		<?= _("MyMemory_CreateProfileStep6Desc"); ?>
		</div>
	
		<form  method="post" class="msform" data-ajax="false">
			<input type="hidden" name="nextStep" value="?action=ExtendedProfile" />
			
			
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyMemory_PerimeterHome"); ?></div>
					<div class="ui-controlgroup-controls">
						
						<label for="AutoCall0"><?= _("MyMemory_CallThisNumber"); ?></label>
						<input type="text" name="AutoCall0" name="AutoCall0" placeholder="06....." />
						<label for="PerimeterHome" ><?= _("MyMemory_IfOutsidePerimeter"); ?> (m)</label>
						<input type="range" name="PerimeterHome" id="PerimeterHome" value="100" min="20" max="200" />
					</div>
				</fieldset>
			</div>
			<br />
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyMemory_PerimeterNear"); ?></div>
					<div class="ui-controlgroup-controls">
						
						<label for="AutoCall1"><?= _("MyMemory_CallThisNumber"); ?></label>
						<input type="text" name="AutoCall1" name="AutoCall1" placeholder="06....." />
						<label for="PerimeterNear" ><?= _("MyMemory_IfOutsidePerimeter"); ?> (m)</label>
						<input type="range" name="PerimeterNear" id="PerimeterNear" value="250" min="100" max="700" />
					</div>
				</fieldset>
			</div>
			<br />
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyMemory_PerimeterFar"); ?></div>
					<div class="ui-controlgroup-controls">
						
						<label for="AutoCall2"><?= _("MyMemory_CallThisNumber"); ?></label>
						<input type="text" name="AutoCall2" name="AutoCall2" placeholder="06....." />
						<label for="PerimeterFar" ><?= _("MyMemory_IfOutsidePerimeter"); ?> (m)</label>
						<input type="range" name="PerimeterFar" id="PerimeterFar" value="1000" min="500" max="2000" />
					</div>
				</fieldset>
			</div>
			<br />
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<div role="heading" class="ui-controlgroup-label"><?= _("MyMemory_AutoCallFrequency"); ?></div>
					<div class="ui-controlgroup-controls">
						
						<label for="AutoCallInterval"><?= _("MyMemory_CheckEvery"); ?></label>
						<input type="text" name="AutoCallInterval" name="AutoCallInterval" placeholder="20" />
					</div>
				</fieldset>
			</div>
			

			<input type="submit" data-role="button" id="submitButton"  value="<?= _('Save') ?>" data-theme="b"/>
			
		</form>
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		
		<div data-role="popup" id="createProfileStep6HelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px; margin-top:50px;">
			<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<h3><?= _("How it works") ?></h3>
			<?= _("MyMemory_CreateProfileStep6Help"); ?>
		</div>
	</div>
</div>

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
<!-- ----------------------------- -->
<!-- ExtendedProfileEdit View      -->
<!-- ----------------------------- -->

<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="extendedprofileeditview">

	<!-- Page header -->
	<? $title = _("Edit Profile");

	   print_header_bar('index.php?action=extendedProfile&method=show_user_profile&user='.$_SESSION['user']->id.'', false, $title); ?>

	   
	<!-- Page content -->
	<div data-role="content">
	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Extended profile edit form -->
		<form action="?action=ExtendedProfile&method=update" method="POST" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" id="role" name="role" value="<?= $_SESSION['myEdu']->details['role'] ?>" />
			<input type="hidden" name="id" value="<?= $_SESSION['myEdu']->profile ?>" />
			<input type="hidden" name="form" value="edit" />

			<!-- Role -->
			<div style="text-align: center">
				<label for="typeProfile"> <?= _("Profile type") ?>: </label>
				<strong style="text-transform:uppercase;"><?= _($_SESSION['myEdu']->details['role'])?></strong>
			</div>
			<script type="text/javascript">
				$("#extendedprofileeditview").on("pageshow", function() {  
					switch ('<?= $_SESSION['myEdu']->details['role'] ?>') {			
						case 'student':
    						$('#studentnumberfield').show();	
    						$('#facultyfield').show();
    						$('#universityfield').hide();
    						$('#coursesfield').hide();
    						$('#companytypefield').hide();
    						$('#siretfield').hide();
  							break; 
  						
  						case 'professor':
    						$('#studentnumberfield').hide();
    						$('#facultyfield').hide();
    						$('#universityfield').show();
    						$('#coursesfield').show();
    						$('#companytypefield').hide();
    						$('#siretfield').hide();
  							break;

  						case 'company':
    						$('#studentnumberfield').hide();
    						$('#facultyfield').hide();
    						$('#universityfield').hide();
    						$('#coursesfield').hide();
    						$('#companytypefield').show();
    						$('#siretfield').show();
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
			<div data-role="fieldcontain">
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
			
			<!-- MyEdu Extended Profile details -->
			
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myEdu']->details['phone'] ?>"  type="tel" />
			</div>
			<!-- Description -->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" style="height: 120px;" name="desc"><?= $_SESSION['myEdu']->details['desc'] ?></textarea>
			</div>
			<!-- For Students: student number -->
			<div id="studentnumberfield" data-role="fieldcontain" class="ui-screen-hidden">
				<label for="studentnumber"  style="text-align:right"><?= _('Student number') ?><b>*</b> : </label>
				<input id="studentnumber" name="studentnumber" placeholder="Your student number" value="<?= $_SESSION['myEdu']->details['studentnumber'] ?>" ></input>
			</div>
			<!-- For Students: school faculty-->
			<div id="facultyfield" data-role="fieldcontain">
				<label for="faculty"  style="text-align:right"><?= _('Faculty') ?><b>*</b> : </label>
				<input id="faculty" name="faculty" placeholder="Your School faculty" value="<?= $_SESSION['myEdu']->details['faculty'] ?>" ></input>
			</div>
			<!-- For Professor: University -->
			<div id="universityfield" data-role="fieldcontain">
				<label for="university"  style="text-align:right"><?= _('University') ?><b>*</b> : </label>
				<input id="university" name="university" placeholder="Your University" value="<?= $_SESSION['myEdu']->details['university'] ?>"></input>
			</div>
			<!-- For Professor: Courses -->
			<div id="coursesfield" data-role="fieldcontain">
				<label for="courses"  style="text-align:right"><?= _('Courses') ?> <b>*</b>: </label>
				<textarea id="courses" style="height: 120px;" name="courses" placeholder="The list of your courses"><?= $_SESSION['myEdu']->details['courses'] ?></textarea>
			</div>
			<!-- For Companies: type -->
			<div id="companytypefield" data-role="fieldcontain">
				<label for="companytype"  style="text-align:right"><?= _('Company type') ?> <b>*</b>: </label>
				<input id="companytype" name="companytype" placeholder="Company type" value="<?= $_SESSION['myEdu']->details['companytype'] ?>"></input>
			</div>
			<!-- For Companies: siret -->
			<div id="siretfield" data-role="fieldcontain">
				<label for="siret"  style="text-align:right"><?= _('SIRET') ?> <b>*</b>: </label>
				<input id="siret" name="siret" placeholder="Write your SIRET number " value="<?= $_SESSION['myEdu']->details['siret'] ?>"></input>
			</div>
			<div data-role="fieldcontain">
				<label for="siret"  style="text-align:right"><b>*</b>: <i><?= _("Mandatory fields")?></i></label>
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="ok" data-theme="g" value="<?= _('Update') ?>"/>
			</div>
		</form>
		
	</div> <!-- END page-->
</div>
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
<!-- --------------------------- -->
<!-- ExtendedProfileCreate View  -->
<!-- --------------------------- -->

<? require_once('header-bar.php'); ?>


<div data-role="page" id="extendedprofilecreateview" >
	
	<? require_once('Categories.class.php'); ?>  
  	 
	<!-- Page header bar -->
	<? 	$title = _("Create profile");
		if(strpos($_SERVER['HTTP_REFERER'],"?action=profile")) {
			print_header_bar("back", false, $title);
		} else {
	   		print_header_bar("?action=main", "helpPopup", $title); 
		}?>
	   
	<!-- Notification pop up -->
	<? include_once 'notifications.php'; ?>
	<? print_notification($this->success.$this->error); ?>
	
	   
	<!-- Page content -->
	<div data-role="content">
	
		<br>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time in myEdu! Please create your extended profile.") ?></h1>
		</div>
		<br />
		
		<script type="text/javascript">
			// Hide all fields releated to specific profile	
			$("#extendedprofilecreateview").on("pageshow", function() {  
	    			$('#studentnumberfield').hide();	
   					$('#facultyfield').hide();
   					$('#universityfield').hide();
   					$('#coursesfield').hide();
   					$('#companytypefield').hide();    					
   					$('#siretfield').hide();
				});
		</script>
		
		
		<!-- Create extended profile form -->
		<form action="?action=ExtendedProfile&method=create" method="post" id="ExtendedProfileForm" data-ajax="false">
			
			<!-- These hidden fields are from the myMed profile and are also saved in the extended profile
			<input type="hidden" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			<input type="hidden" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
			<input type="hidden" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			<input type="hidden" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			<input type="hidden" id="picture" name="picture" value="<?= $_SESSION['user']->profilePicture ?>" />
			 -->
			
			<!-- Role -->
			<div data-role="fieldcontain">
				<label for="role" class="select" style="text-align:right"><?= _("Your category")?><b>*</b> :</label>
				<select name="role" id="role" data-native-menu="false" onChange="
					
					switch ($('#role').val()) {
						
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
					
					}">
				<option value=""><?= _("Select your category")?></option>
				<? foreach (Categories::$roles as $k=>$v) :?>
					<option value="<?= $k ?>"><?= $v ?></option>
				<? endforeach ?>
				</select>
			</div>
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value='' type="tel" />
			</div>
			<!-- Description / CV-->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description / <br/> Curriculum Vitae') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires" style="height:120px;"></textarea>
			</div>
			<!-- For Students: student number -->
			<div id="studentnumberfield" data-role="fieldcontain" class="ui-screen-hidden">
				<label for="studentnumber"  style="text-align:right"><?= _('Student number') ?><b>*</b> : </label>
				<input id="studentnumber" name="studentnumber" placeholder="Your student number" ></input>
			</div>
			<!-- For Students: school faculty-->
			<div id="facultyfield" data-role="fieldcontain">
				<label for="faculty"  style="text-align:right"><?= _('Faculty') ?><b>*</b> : </label>
				<input id="faculty" name="faculty" placeholder="Your School faculty"></input>
			</div>
			<!-- For Professor: University -->
			<div id="universityfield" data-role="fieldcontain">
				<label for="university"  style="text-align:right"><?= _('University') ?><b>*</b> : </label>
				<input id="university" name="university" placeholder="Your University"></input>
			</div>
			<!-- For Professor: Courses -->
			<div id="coursesfield" data-role="fieldcontain">
				<label for="courses"  style="text-align:right"><?= _('Courses') ?><b>*</b> : </label>
				<textarea id="courses" style="height: 120px;" name="courses" placeholder="The list of your courses"></textarea>
			</div>
			<!-- For Companies: type -->
			<div id="companytypefield" data-role="fieldcontain">
				<label for="companytype"  style="text-align:right"><?= _('Company type') ?><b>*</b> : </label>
				<input id="companytype" name="companytype" placeholder="Company type"></input>
			</div>
			<!-- For Companies: siret -->
			<div id="siretfield" data-role="fieldcontain">
				<label for="siret"  style="text-align:right"><?= _('SIRET') ?><b>*</b> : </label>
				<input id="siret" name="siret" placeholder="Write your SIRET number "></input>
			</div>
			<br/>
			<div data-role="fieldcontain">
				<label for="siret"  style="text-align:right"><b>*</b>: <i><?= _('Mandatory fields') ?></i></label>
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="e" data-role="button" data-icon="gear" value="<?= _('Create this profile') ?>"/>
			</div>
		</form>
	</div>
	
	
	<!-- Help Pop Up -->
	<div data-role="popup" id="helpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<p> <?= _("Create your myEdu Profile as an oriented profile according to your status (student, professor, company), to use myEdu network in the best conditions.") ?></p>
		
	</div>
	
</div>

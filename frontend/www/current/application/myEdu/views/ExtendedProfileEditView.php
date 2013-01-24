<!-- ----------------------------- -->
<!-- ExtendedProfileEdit View   -->
<!-- ----------------------------- -->

<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="extendedprofileeditview">

	<!-- Page header -->
	<? $title = _("Edit Profile");
	   print_header_bar(true, "defaultHelpPopup", $title); ?>
	
	
	<!-- Page content -->
	<div data-role="content">
	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Extended profile edit form -->
		<form action="?action=ExtendedProfile&method=update" method="post" id="ExtendedProfileForm" data-ajax="false">
		
			<!-- Role -->
			<p> Profile type: <strong><?= $_SESSION['myEdu']->details['role'] ?></strong></p>
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
				<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			</div>
			<!-- Last Name -->
			<div data-role="fieldcontain">
				<label for="lastName" style="text-align:right"><?= _("Last Name") ?> : </label>
				<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			</div>
			<!-- Birthday -->
			<div data-role="fieldcontain">
				<label for="birthday" style="text-align:right"><?= _("Birthday") ?> : </label>
				<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			</div>
			<!-- Profile picture -->
			<div data-role="fieldcontain">
				<label for="profilePicture" style="text-align:right"><?= _("Profile picture") ?> (url): </label>
				<input type="text" id="profilePicture" name="profilePicture" value="<?= $_SESSION['user']->profilePicture ?>" />
			</div>
			<!-- User language -->
			<div data-role="fieldcontain">
				<label for="lang" style="text-align:right"><?= _("Language") ?>	: </label>
				<select id="lang" name="lang">
					<option value="fr" <?= $_SESSION['user']->lang == "fr" ? "selected" : "" ?>><?= _("French")?></option>
					<option value="it" <?= $_SESSION['user']->lang == "it" ? "selected" : "" ?>><?= _("Italian")?></option>
					<option value="en" <?= $_SESSION['user']->lang == "en" ? "selected" : "" ?>><?= _("English")?></option>
				</select>
			</div>
			
			<p><strong> MyEdu Profile details </strong></p>
			<!-- MyEdu Extended Profile details -->
			<!-- MyEdu name -->
			<div data-role="fieldcontain">
				<label for="textinputu1" style="text-align:right"><?= _('MyEdu Profile Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value="<?= $_SESSION['myEdu']->details['name'] ?>" type="text" />
			</div>
			<!-- Address -->
			<div data-role="fieldcontain">
				<label for="textinputu4" style="text-align:right"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value="<?= $_SESSION['myEdu']->details['address'] ?>"  type="text" />
			</div>
			<!-- Email -->
			<div data-role="fieldcontain">
				<label for="textinputu5"  style="text-align:right"><?= _('Email') ?>: </label>
				<input id="textinputu5" name="email" placeholder="" value="<?= $_SESSION['myEdu']->details['email'] ?>"  type="email" />
			</div>
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myEdu']->details['phone'] ?>"  type="tel" />
			</div>
			<!-- Description -->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"><?= $_SESSION['myEdu']->details['desc'] ?></textarea>
			</div>
			<!-- For Students: student number -->
			<div id="studentnumberfield" data-role="fieldcontain" class="ui-screen-hidden">
				<label for="studentnumber"  style="text-align:right"><?= _('Student number') ?>: </label>
				<input id="studentnumber" name="studentnumber" placeholder="Your student number" value="<?= $_SESSION['myEdu']->details['studentnumber'] ?>" ></input>
			</div>
			<!-- For Students: school faculty-->
			<div id="facultyfield" data-role="fieldcontain">
				<label for="faculty"  style="text-align:right"><?= _('Faculty') ?>: </label>
				<input id="faculty" name="faculty" placeholder="Your School faculty" value="<?= $_SESSION['myEdu']->details['faculty'] ?>" ></input>
			</div>
			<!-- For Professor: University -->
			<div id="universityfield" data-role="fieldcontain">
				<label for="university"  style="text-align:right"><?= _('University') ?>: </label>
				<input id="university" name="university" placeholder="Your University" value="<?= $_SESSION['myEdu']->details['university'] ?>"></input>
			</div>
			<!-- For Professor: Courses -->
			<div id="coursesfield" data-role="fieldcontain">
				<label for="courses"  style="text-align:right"><?= _('Courses') ?>: </label>
				<input id="courses" name="courses" placeholder="The list of your courses" value="<?= $_SESSION['myEdu']->details['courses'] ?>"></input>
			</div>
			<!-- For Companies: type -->
			<div id="companytypefield" data-role="fieldcontain">
				<label for="companytype"  style="text-align:right"><?= _('Company type') ?>: </label>
				<input id="companytype" name="companytype" placeholder="Company type" value="<?= $_SESSION['myEdu']->details['companytype'] ?>"></input>
			</div>
			<!-- For Companies: siret -->
			<div id="siretfield" data-role="fieldcontain">
				<label for="siret"  style="text-align:right"><?= _('SIRET') ?>: </label>
				<input id="siret" name="siret" placeholder="Write your SIRET number " value="<?= $_SESSION['myEdu']->details['siret'] ?>"></input>
			</div>
			<br/>
			<div data-role="fieldcontain">
				<label for="password" style="text-align:right"><?= _("Password") ?>:</label>
				<input type="password" id="password" name="password" />
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="ok" value="<?= _('Update') ?>"/>
			</div>
		</form>
		
	</div> <!-- END page-->
	
	
	<!-- Help popup -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit your Profile") ?></h3>
		<p> <?= _("Here you can update your organization profile.") ?></p>
	</div>
	
</div>
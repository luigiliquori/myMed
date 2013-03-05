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
				<input type="text" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			</div>
			<!-- Last Name -->
			<div data-role="fieldcontain">
				<label for="lastName" style="text-align:right"><?= _("Last Name") ?> : </label>
				<input type="text" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			</div>
			<!-- Birthday -->
			<div data-role="fieldcontain">
				<label for="birthday" style="text-align:right"><?= _("Date of birth") ?> : </label>
				<input type="text" id="birthday" name="birthday" placeholder="jj/mm/aaaa" value="<?= $_SESSION['user']->birthday ?>" />
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
			
			<!-- MyEdu Extended Profile details -->
			
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myEdu']->details['phone'] ?>"  type="tel" />
			</div>
			<!-- Description -->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" style="height: 120px;" name="desc" placeholder="description, commentaires"><?= $_SESSION['myEdu']->details['desc'] ?></textarea>
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
			<div data-role="fieldcontain">
				<label for="password" style="text-align:right"><?= _("Password") ?> :</label>
				<input type="password" id="password" name="password" />
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="ok" data-theme="g" value="<?= _('Update') ?>"/>
			</div>
		</form>
		
	</div> <!-- END page-->
</div>
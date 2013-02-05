<!-- --------------------------- -->
<!-- ExtendedProfileCreate View  -->
<!-- --------------------------- -->

<? require_once('header-bar.php'); ?>


<div data-role="page" id="extendedprofilecreateview" >
	
	<? require_once('Categories.class.php'); ?>  
  	 
	<!-- Page header bar -->
	<? $title = _("Create profile");
	   print_header_bar("?action=main", "defaultHelpPopup", $title); ?>
	   
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
			
			<!-- These hidden fields are from the myMed profile and are also saved in the extended profile -->
			<input type="hidden" id="firstName" name="firstName" value="<?= $_SESSION['user']->firstName ?>" />
			<input type="hidden" id="email" name="email" value="<?= $_SESSION['user']->email ?>" />
			<input type="hidden" id="lastName" name="lastName" value="<?= $_SESSION['user']->lastName ?>" />
			<input type="hidden" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" />
			<input type="hidden" id="picture" name="picture" value="<?= $_SESSION['user']->profilePicture ?>" />
			
			
			<!-- Role -->
			<div data-role="fieldcontain">
				<label for="role" class="select" style="text-align:right"><?= _("Your category") ?>:</label>
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
				<label for="studentnumber"  style="text-align:right"><?= _('Student number') ?>: </label>
				<input id="studentnumber" name="studentnumber" placeholder="Your student number" ></input>
			</div>
			<!-- For Students: school faculty-->
			<div id="facultyfield" data-role="fieldcontain">
				<label for="faculty"  style="text-align:right"><?= _('Faculty') ?>: </label>
				<input id="faculty" name="faculty" placeholder="Your School faculty"></input>
			</div>
			<!-- For Professor: University -->
			<div id="universityfield" data-role="fieldcontain">
				<label for="university"  style="text-align:right"><?= _('University') ?>: </label>
				<input id="university" name="university" placeholder="Your University"></input>
			</div>
			<!-- For Professor: Courses -->
			<div id="coursesfield" data-role="fieldcontain">
				<label for="courses"  style="text-align:right"><?= _('Courses') ?>: </label>
				<textarea id="courses" style="height: 120px;" name="courses" placeholder="The list of your courses"></textarea>
			</div>
			<!-- For Companies: type -->
			<div id="companytypefield" data-role="fieldcontain">
				<label for="companytype"  style="text-align:right"><?= _('Company type') ?>: </label>
				<input id="companytype" name="companytype" placeholder="Company type"></input>
			</div>
			<!-- For Companies: siret -->
			<div id="siretfield" data-role="fieldcontain">
				<label for="siret"  style="text-align:right"><?= _('SIRET') ?>: </label>
				<input id="siret" name="siret" placeholder="Write your SIRET number "></input>
			</div>
			<br/>
			<!-- Accept terms and conditions -->
			<input id="service-term" type="checkbox" name="checkCondition" style="display:inline-block;float:right;top:5px;width:17px;height:17px"/>
			<span style="display:inline-block;margin-left: 40px;">
				<?= _("I accept the ")?>
				<a href="../myMed/doc/CGU_fr.pdf" rel="external"><?= _("general terms and conditions")?></a>
			</span>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="e" data-role="button" data-icon="gear" value="<?= _('Create this profile') ?>"/>
			</div>
		</form>
	</div>
	
	
	<!-- Help Pop Up -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("<<<<< Help title. >>>>>") ?></h3>
		<p> <?= _("<<<<< Help text >>>>>") ?></p>
		
	</div>
	
</div>

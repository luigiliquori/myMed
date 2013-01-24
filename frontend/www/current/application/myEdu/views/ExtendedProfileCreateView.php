<!-- --------------------------- -->
<!-- ExtendedProfileCreate View  -->
<!-- --------------------------- -->

<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="extendedprofilecreateview" >
	
	<? require_once('Categories.class.php'); ?>  
  	 
  	 
	<!-- Page header bar -->
	<? $title = _("Create profile");
	   print_header_bar(true, "defaultHelpPopup", $title); ?>
	
	   
	<!-- Page content -->
	<div data-role="content">
	
		<br>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time in this myApp! Please create a your extended profile") ?></h1>
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
				<option value="">Select your category</option>
				<? foreach (Categories::$roles as $k=>$v) :?>
					<option value="<?= $k ?>"><?= $v ?></option>
				<? endforeach ?>
				</select>
			</div>
			<!-- Name -->
			<div id="namefield" data-role="fieldcontain">
				<label for="textinputu1" style="text-align:right" ><?= _('Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			</div>
			<!-- Address -->
			<div data-role="fieldcontain">
				<label for="textinputu4" style="text-align:right"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value='' type="text" />
			</div>
			<!-- Email -->
			<div data-role="fieldcontain">
				<label for="textinputu5"  style="text-align:right"><?= _('Email') ?>: </label>
				<input id="textinputu5" name="email" placeholder="" value='<?= $_SESSION['user']->email ?>' type="email" />
			</div>
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value='' type="tel" />
			</div>
			<!-- Description -->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
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
				<input id="courses" name="courses" placeholder="The list of your courses"></input>
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
			<input id="service-term" type="checkbox" name="checkCondition" style="display: inline-block; top: 8px;"/>
			<span style="display:inline-block;margin-left: 40px;">
				<?= _("I accept the ")?>
				<a href="<?= APP_ROOT ?>/conds" rel="external"><?= _("general terms and conditions")?></a>
			</span>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="e" data-role="button" data-icon="gear" value="<?= _('Create this profile') ?>"/>
			</div>
		</form>
	</div>
	
	
	<!-- Help Pop Up -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Help title.") ?></h3>
		<p> <?= _("Help text") ?></p>
		
	</div>
	
</div>

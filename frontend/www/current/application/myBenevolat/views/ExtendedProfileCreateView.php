<!-- ------------------------------------ -->
<!-- ExtendedProfileCreateView            -->
<!-- Implements a profile creation wizard --> 
<!-- ------------------------------------ -->

<!-- action="?action=ExtendedProfile&method=create"  -->

<? require_once('Categories.class.php'); ?>  


<!-- Step 1 - Starting Extended Profile creation page-->
<div data-role="page" id="step1" >
	
	<!-- Header bar -->
	<? $title = _("Step 1 - Choose your profile");
	   print_header_bar("?action=main", "createprofileHelpPopup", $title); ?>
	
	<!-- Page content -->
	<div data-role="content">
	
		<br>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time in myBenevolat! Please create your extended profile.") ?></h1>
		</div>
		<br />		

		<br/>
	    <?= _("Are you ?") ?><br/>
		<br/>
		<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
			href="?action=ExtendedProfile&method=start_wizard_volunteer">
			<?= _("A Volunteer") ?>
		</a>
		<a data-role="button" data-theme="e" class="mm-left" data-ajax="false"
			href="?action=ExtendedProfile&method=start_wizard_association">
        	<?= _("An association") ?>
		</a>		
		
	</div>
	
	<!-- Help Pop Up -->
	<div data-role="popup" id="createprofileHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Extended Profile Help") ?></h3>
		<p> <?= _("Choose your role.") ?></p>
	</div>
	
</div>
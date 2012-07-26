<? include("header.php"); ?>
<? include("notifications.php")?>

<!-- Header -->
<div data-role="header" data-position="inline" data-theme="a">
	<a href="?action=logout" rel="external" data-role="button" class="ui-btn-left" data-icon="delete">Exit</a>
	<h1>Profile</h1>
</div>

<div data-role="content" data-theme="a">
	<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
		
		<!-- DOCTOR -->
		<div data-role="fieldcontain" data-theme="a">
			<fieldset data-role="controlgroup">
				<div role="heading" class="ui-controlgroup-label">Full profile</div>
				<div class="ui-controlgroup-controls">
					<label for="companyName" class="ui-hidden-accessible">Company address : </label>
					<input type="text" name="companyName" name="companyName" value="" placeholder="Company address" />
					
					<label for="DoctorName" class="ui-hidden-accessible">Doctor name : </label>
					<input type="text" name="DoctorName" name="DoctorName" value="" placeholder="Name"/>
					
					<label for="DoctorEmail" class="ui-hidden-accessible">Doctor e-mail : </label>
					<input type="text" name="DoctorEmail" name="DoctorEmail" value="" placeholder="Surname"/>
					
					<label for="DoctorPhone" class="ui-hidden-accessible">Doctor phone : </label>
					<input type="text" name="DoctorPhone" name="DoctorPhone" value="" placeholder="Phone"/>
				</div>
			</fieldset>
		</div>
		
		<input type="submit" data-role="button" id="submitButton" value="Save" data-theme="a"/>
	</form>
</div>
<? include("footer.php"); ?>
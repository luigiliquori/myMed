<!-- ------------------------------------ -->
<!-- ExtendedProfileEdit View             -->
<!-- Edit a user extended profile details -->
<!-- ------------------------------------ -->


<!-- Header bar functions -->
<? require_once('header-bar.php'); ?>


<!-- Notification pop up -->
<? require_once('notifications.php'); ?>


<!-- Page view -->
<div data-role="page" id="extendedprofileeditview">

	<!-- Page header -->
	<? $title = _("Edit Profile");

	   print_header_bar(
	   		'index.php?action=extendedProfile&method=show_user_profile&user='
	   		.$_SESSION['user']->id.'', "defaultHelpPopup", $title); ?>

	   
	<!-- Page content -->
	<div data-role="content">
	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- Extended profile edit form -->
		<form action="?action=ExtendedProfile&method=update" method="POST" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" id="role" name="role" value="<?= $_SESSION['myTemplateExtended']->details['role'] ?>" />
			<input type="hidden" name="id" value="<?= $_SESSION['myTemplateExtended']->profile ?>" />
			<input type="hidden" name="form" value="edit" />

			<!-- Role -->
			<div style="text-align: center">
				<label for="typeProfile"> <?= _("Profile type") ?>: </label>
				<strong style="text-transform:uppercase;"><?= _($_SESSION['myTemplateExtended']->details['role'])?></strong>
			</div>
			<script type="text/javascript">
				$("#extendedprofileeditview").on("pageshow", function() {  
					switch ('<?= $_SESSION['myTemplateExtended']->details['role'] ?>') {			
						case 'Role_1':
							$('#role1field1div').show();	
		   					$('#role1field2div').show();
		   					$('#role2field1div').hide();
		   					$('#role2field2div').hide();
  							break; 
  						
  						case 'Role_2':
  							$('#role1field1div').hide();	
		   					$('#role1field2div').hide();
		   					$('#role2field1div').show();
		   					$('#role2field2div').show();  							
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
				<label for="birthday" style="text-align:right"><?= _("Birthday") ?> : </label>
				<input type="text" id="birthday" name="birthday" value="<?= $_SESSION['user']->birthday ?>" <?= (isset($_SESSION['userFromExternalAuth']))? "disabled" : "" ?>/>
			</div>
			<!-- Profile picture -->
			<div data-role="fieldcontain">
				<label for="profilePicture" style="text-align:right"><?= _("Profile picture") ?> (url): </label>
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
			<!--
			<p><strong> myTemplateExtended Profile details </strong></p>
			 myTemplateExtended Extended Profile details -->
			
			<!-- Phone -->		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myTemplateExtended']->details['phone'] ?>"  type="tel" />
			</div>
			<!-- Description -->
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" style="height: 120px;" name="desc" placeholder="description, commentaires"><?= $_SESSION['myTemplateExtended']->details['desc'] ?></textarea>
			</div>
			<!-- Fields for role_1 -->
			<div id="role1field1div" data-role="fieldcontain" class="ui-screen-hidden">
				<label for="role1field1"  style="text-align:right"><?= _('Role 1 field 1') ?>: </label>
				<input id="role1field1" name="role1field1" placeholder="Role 1 field 1" value="<?= $_SESSION['myTemplateExtended']->details['role1field1'] ?>"></input>
			</div>
			<div id="role1field2div" data-role="fieldcontain">
				<label for="role1field2"  style="text-align:right"><?= _('Role 1 field 2') ?>: </label>
				<textarea id="role1field2" name="role1field2" style="height: 120px;" placeholder="Role 1 field 2"><?= $_SESSION['myTemplateExtended']->details['role1field2'] ?></textarea>
			</div>
			<!-- Fields for role_2 -->
			<div id="role2field1div" data-role="fieldcontain">
				<label for="role2field1"  style="text-align:right"><?= _('Role 2 field 1') ?>: </label>
				<input id="role2field1" name="role2field1" placeholder="Role 2 field 1" value="<?= $_SESSION['myTemplateExtended']->details['role2field1'] ?>"></input>
			</div>
			<div id="role2field2div" data-role="fieldcontain">
				<label for="role2field2"  style="text-align:right"><?= _('Role 2 field 2') ?>: </label>
				<textarea id="role2field2" name="role2field2" style="height: 120px;" placeholder="Role 2 field 2"><?= $_SESSION['myTemplateExtended']->details['role2field2'] ?></textarea>
			</div>
			<br/>
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
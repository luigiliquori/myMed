<!-- --------------------------------------------------- -->
<!-- ExtendedProfileCreate View                          -->
<!-- Here you can create an extended profile in this app -->
<!-- You can specify different roles for users and 		 -->
<!-- profile attributes specifics to those roles         -->
<!-- --------------------------------------------------- -->


<!-- Header bars functions -->
<? require_once('header-bar.php'); ?>

<!-- Page view -->
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
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time in myTemplateExtended! Please create your extended profile.") ?></h1>
		</div>
		<br />
		
		<script type="text/javascript">
			// Hide all fields releated to specific profile	
			$("#extendedprofilecreateview").on("pageshow", function() {  
	    			$('#role1field1div').hide();	
   					$('#role1field2div').hide();
   					$('#role2field1div').hide();
   					$('#role2field2div').hide();
				});
		</script>
		
		
		<!-- Create extended profile form -->
		<form action="?action=ExtendedProfile&method=create" method="post" id="ExtendedProfileForm" data-ajax="false">

			<!-- Role -->
			<div data-role="fieldcontain">
				<label for="role" class="select" style="text-align:right"><?= _("Your category") ?>:</label>
				<select name="role" id="role" data-theme="e" data-native-menu="false" onChange="
					
					switch ($('#role').val()) {
						
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
				<textarea id="desc" name="desc" style="height:120px;"></textarea>
			</div>
			<!-- Fields for role_1 -->
			<div id="role1field1div" data-role="fieldcontain" class="ui-screen-hidden">
				<label for="role1field1"  style="text-align:right"><?= _('Role 1 field 1') ?>: </label>
				<input id="role1field1" name="role1field1"></input>
			</div>
			<div id="role1field2div" data-role="fieldcontain">
				<label for="role1field2"  style="text-align:right"><?= _('Role 1 field 2') ?>: </label>
				<textarea id="role1field2" name="role1field2" style="height: 120px;"></textarea>
			</div>
			<!-- Fields for role_2 -->
			<div id="role2field1div" data-role="fieldcontain">
				<label for="role2field1"  style="text-align:right"><?= _('Role 2 field 1') ?>: </label>
				<input id="role2field1" name="role2field1"></input>
			</div>
			<div id="role2field2div" data-role="fieldcontain">
				<label for="role2field2"  style="text-align:right"><?= _('Role 2 field 2') ?>: </label>
				<textarea id="role2field2" name="role2field2" style="height: 120px;"></textarea>
			</div>
			<br/>
			
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="e" data-role="button" data-icon="gear" value="<?= _('Create this profile') ?>"/>
			</div>
		</form>
	</div>
	
	
	<!-- Help Pop Up -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Extended Profile Help") ?></h3>
		<p> <?= _("Choose your role and fill in all the fields for your application extended profile.") ?></p>
		
	</div>
	
</div>

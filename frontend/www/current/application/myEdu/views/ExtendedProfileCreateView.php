<!-- ------------------ -->
<!-- App Main View      -->
<!-- ------------------ -->

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
		
		<!-- Create extended profile form -->
		<form action="?action=ExtendedProfile&method=create" method="post" id="ExtendedProfileForm" data-ajax="false">
			
			<input type="hidden" name="form" value="create" />
			
			<!-- Name -->
			<div data-role="fieldcontain">
				<label for="textinputu1" style="text-align:right" ><?= _('Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			</div>
			<!-- Role -->
			<div data-role="fieldcontain">
				<label for="role" class="select" style="text-align:right"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<option value="">Select your category</option>
				<? foreach (Categories::$roles as $k=>$v) :?>
					<option value="<?= $k ?>"><?= $v ?></option>
				<? endforeach ?>
				</select>
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
			<br/>
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

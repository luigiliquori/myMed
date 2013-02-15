<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="new" >
	
	<? $title = _("Create profile");
	   print_header_bar("?action=main", "defaultHelpPopup", $title); ?>

	<div data-role="content">
		<? print_notification($this->success.$this->error);?>
		<br>
		<div data-role="header" data-theme="e">
			<h1 style="white-space: normal;"><?= _("Hello, This is your first time on myEurope! Please register by creating your own profile.") ?></h1>
		</div>
		<br />	
		<form action="?action=ExtendedProfile&method=create" method="post" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" name="form" value="create" />
			<div data-role="fieldcontain">
				<label for="textinputu1" style="text-align:right" ><?= _('Organization Name') ?><b>*</b>: </label>
				<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="role" class="select" style="text-align:right"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<? foreach ($this->cats as $k=>$v) :?>
					<option value="<?= $k ?>"><?= Categories::$roles[$k] ?></option>
				<? endforeach ?>
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu2" style="text-align:right"><?= _("Action area")?>: </label>
				<input id="textinputu2" name="activity" placeholder="" value='' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu4" style="text-align:right"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value='' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="area" class="select" style="text-align:right"><?= _("Action territory") ?>:</label>
				<select name="area" id="area">
					<option value="local"><?= _("local")?></option>
					<option value="départemental"><?= _("departmental")?></option>
					<option value="régional"><?= _("regional")?></option>
					<option value="national"><?= _("national")?></option>
					<option value="international"><?= _("international")?></option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset id="type" data-role="controlgroup">
					<legend> <p style="text-align:right"> <?= _("Territory type")?>: </p> </legend>
					<input type="checkbox" name="type-urbain" id="check-view-a" value="urbain" checked="checked"/> <label for="check-view-a"><?= _("urban")?></label>
					<input type="checkbox" name="type-rural" id="check-view-b" value="rural" /> <label for="check-view-b"><?= _("rural")?></label>
					<input type="checkbox" name="type-montagnard" id="check-view-c" value="montagnard" /> <label for="check-view-c"><?= _("mountain")?></label>
					<input type="checkbox" name="type-maritime" id="check-view-d" value="maritime" /> <label for="check-view-d"><?= _("maritime")?></label>
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu5"  style="text-align:right"><?= _('Organization email') ?><b>*</b>: </label>
				<input id="textinputu5" name="email" placeholder="" value='<? /*=$_SESSION['user']->email*/ ?>' type="email" />
			</div>		
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value='' type="tel" />
			</div>
			<div data-role="fieldcontain">
				<label for="desc"  style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			</div>
			<br/>
			<input id="service-term" type="checkbox" name="checkCondition" style="display: inline-block; top:5px;width:17px;height:17px"/>
			<span style="display:inline-block;margin-left: 40px;">
				<?= _("I accept the ")?>
				<a href="<?= APP_ROOT ?>/conds" rel="external"><?= _("general terms and conditions")?></a>
			</span>
			<p><b>*</b>: <i><?= _("Mandatory fields")?></i></p>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="e" data-role="button" data-icon="gear" value="<?= _('Create this profile') ?>"/>
			</div>
		</form>
	</div>
	
	<!-- ----------------- -->
	<!-- DEFAULT HELP POPUP -->
	<!-- ----------------- -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Create a new profile of your organization.") ?></h3>
		<p> <?= _("Fill the form with your organization details. All members of your organization can use this profile to register in myEurope") ?></p>
		
	</div>
	
</div>


<? include("footer.php"); ?>


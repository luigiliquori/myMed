<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page">
	<? $title = _("EditProfile");
	 print_header_bar(true, "defaultHelpPopup", $title); ?>
	
	
	<div data-role="content">
		<form action="?action=ExtendedProfile&method=update" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" name="form" value="edit" />
			<input type="hidden" name="id" value="<?= $_SESSION['myEurope']->profile ?>" />
			<div data-role="fieldcontain">
				<label for="textinputu1" style="text-align:right"><?= _('Organization Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value="<?= $_SESSION['myEurope']->details['name'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="role" class="select" style="text-align:right"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<? foreach (Categories::$roles as $v) :?>
					<option value="<?= $v ?>" <?= $_SESSION['myEurope']->details['role']==$v?'selected="selected"':'' ?>><?= $v ?></option>
				<? endforeach ?>
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu2" style="text-align:right"><?= _("Action area")?> : </label>
				<input id="textinputu2" name="activity" placeholder="" value="<?= $_SESSION['myEurope']->details['activity'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu4" style="text-align:right"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value="<?= $_SESSION['myEurope']->details['address'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="area" class="select" style="text-align:right"><?= _("Action territory") ?>:</label>
				<select name="area" id="area">
					<option value="local" <?= $_SESSION['myEurope']->details['area']=="local"?'selected="selected"':'' ?>><?= _("local") ?></option>
					<option value="départemental" <?= $_SESSION['myEurope']->details['area']=="départemental"?'selected="selected"':'' ?>><?= _("departmental")?></option>
					<option value="régional" <?= $_SESSION['myEurope']->details['area']=="régional"?'selected="selected"':'' ?>><?= _("regional")?></option>
					<option value="national" <?= $_SESSION['myEurope']->details['area']=="national"?'selected="selected"':'' ?>><?= _("national")?></option>
					<option value="international" <?= $_SESSION['myEurope']->details['area']=="international"?'selected="selected"':'' ?>><?= _("international")?></option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset name="type" id="type" data-role="controlgroup">
					<legend ><p style="text-align:right"><?= _("Territory type")?> : </p></legend>
					<input type="checkbox" name="type-urbain" id="check-view-a" value="urbain" checked="checked"/> <label for="check-view-a"><?= _("urban")?></label>
					<input type="checkbox" name="type-rural" id="check-view-b" value="rural" /> <label for="check-view-b"><?= _("rural")?></label>
					<input type="checkbox" name="type-montagnard" id="check-view-c" value="montagnard" /> <label for="check-view-c"><?= _("mountain")?></label>
					<input type="checkbox" name="type-maritime" id="check-view-d" value="maritime" /> <label for="check-view-d"><?= _("maritime")?></label>
				</fieldset>
			</div>	
			<div data-role="fieldcontain">
				<label for="textinputu5" style="text-align:right"><?= _('Email') ?>: </label>
				<input id="textinputu5" name="email" placeholder="" value="<?= $_SESSION['myEurope']->details['email'] ?>" type="email" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu6" style="text-align:right"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myEurope']->details['phone'] ?>" type="tel" />
			</div>
			<div data-role="fieldcontain">
				<label for="desc" style="text-align:right"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"><?= $_SESSION['myEurope']->details['desc'] ?></textarea>
			</div>
			<br />
			<div data-role="fieldcontain">
				<label for="password" style="text-align:right"><?= _("Password") ?>:</label>
				<input type="password" id="password" name="password" />
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="ok" value="<?= _('Update') ?>"/>
			</div>
		</form>
	</div>
	
	<!-- ----------------- -->
	<!-- DEFAULT HELP POPUP -->
	<!-- ----------------- -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Edit your Profile") ?></h3>
		<p> <?= _("Here you can update your organizations profile.") ?></p>
		
	</div>
	
</div>
<? include("footer.php"); ?>
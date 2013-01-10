<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page">
	<? print_header_bar(true, "defaultHelpPopup"); ?>
	
	
	<div data-role="content">
		<form action="?action=ExtendedProfile&method=update" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" name="form" value="edit" />
			<input type="hidden" name="id" value="<?= $_SESSION['myEurope']->profile ?>" />
			<div data-role="fieldcontain">
				<label for="textinputu1"><?= _('Organization Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value="<?= $_SESSION['myEurope']->details['name'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="role" class="select"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<? foreach (Categories::$roles as $v) :?>
					<option value="<?= $v ?>" <?= $_SESSION['myEurope']->details['role']==$v?'selected="selected"':'' ?>><?= $v ?></option>
				<? endforeach ?>
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu2"><?= _("Action area")?> : </label>
				<input id="textinputu2" name="activity" placeholder="" value="<?= $_SESSION['myEurope']->details['activity'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu4"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value="<?= $_SESSION['myEurope']->details['address'] ?>" type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="area" class="select"><?= _("Action territory") ?>:</label>
				<select name="area" id="area">
					<option value="local" <?= $_SESSION['myEurope']->details['area']=="local"?'selected="selected"':'' ?>><?= _("local") ?></option>
					<option value="départemental" <?= $_SESSION['myEurope']->details['area']=="départemental"?'selected="selected"':'' ?>><?= _("departmental")?></option>
					<option value="régional" <?= $_SESSION['myEurope']->details['area']=="régional"?'selected="selected"':'' ?>><?= _("regional")?></option>
					<option value="national" <?= $_SESSION['myEurope']->details['area']=="national"?'selected="selected"':'' ?>><?= _("national")?></option>
					<option value="international" <?= $_SESSION['myEurope']->details['area']=="international"?'selected="selected"':'' ?>><?= _("international")?></option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset id="type" data-role="controlgroup">
					<legend><?= _("Territory type")?> :</legend>
					<input type="checkbox" name="type-urbain" id="check-view-a" value="urbain" checked="checked"/> <label for="check-view-a"><?= _("urban")?></label>
					<input type="checkbox" name="type-rural" id="check-view-b" value="rural" /> <label for="check-view-b"><?= _("rural")?></label>
					<input type="checkbox" name="type-montagnard" id="check-view-c" value="montagnard" /> <label for="check-view-c"><?= _("mountain")?></label>
					<input type="checkbox" name="type-maritime" id="check-view-d" value="maritime" /> <label for="check-view-d"><?= _("maritime")?></label>
				</fieldset>
			</div>	
			<div data-role="fieldcontain">
				<label for="textinputu5"><?= _('Email') ?>: </label>
				<input id="textinputu5" name="email" placeholder="" value="<?= $_SESSION['myEurope']->details['email'] ?>" type="email" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu6"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value="<?= $_SESSION['myEurope']->details['phone'] ?>" type="tel" />
			</div>
			<div data-role="fieldcontain">
				<label for="desc"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"><?= $_SESSION['myEurope']->details['desc'] ?></textarea>
			</div>
			<br />
			<div data-role="fieldcontain">
				<label for="password"><?= _("Password") ?>:</label>
				<input type="password" id="password" name="password" />
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="ok" value="<?= _('Update') ?>"/>
			</div>
		</form>
	</div>
</div>
<? include("footer.php"); ?>
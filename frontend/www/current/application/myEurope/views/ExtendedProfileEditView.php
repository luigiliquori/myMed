<? include("header.php"); ?>
<div data-role="page">
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3(
				_('Profile'),
				_('Validate'),
				"document.ExtendedProfileForm.submit();",
				"check") ?>
		<? include("notifications.php"); ?>
	</div>
	
	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" name="form" value="edit" />
			<input type="hidden" name="id" value="<?= $_SESSION['myEurope']->profile ?>" />
			<div data-role="fieldcontain">
				<label for="textinputu1"><?= _('Organization Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['myEuropeProfile']->name ?>' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="role" class="select"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<? foreach ($this->cats as $k=>$v) :?>
					<option value="<?= $k ?>" <?= $_SESSION['myEuropeProfile']->role==$k?'selected="selected"':'' ?>><?= $k ?></option>
				<? endforeach ?>
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu2">Domaine d'action: </label>
				<input id="textinputu2" name="activity" placeholder="" value='<?= $_SESSION['myEuropeProfile']->activity ?>' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu4"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value='<?= $_SESSION['myEuropeProfile']->address ?>' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="area" class="select"><?= _("Territoire d'action") ?>:</label>
				<select name="area" id="area">
					<option value="local" <?= $_SESSION['myEuropeProfile']->area=="local"?'selected="selected"':'' ?>>local</option>
					<option value="départemental" <?= $_SESSION['myEuropeProfile']->area=="départemental"?'selected="selected"':'' ?>>départemental</option>
					<option value="régional" <?= $_SESSION['myEuropeProfile']->area=="régional"?'selected="selected"':'' ?>>régional</option>
					<option value="national" <?= $_SESSION['myEuropeProfile']->area=="national"?'selected="selected"':'' ?>>national</option>
					<option value="international" <?= $_SESSION['myEuropeProfile']->area=="international"?'selected="selected"':'' ?>>international</option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset id="type" data-role="controlgroup">
					<legend>Type de territoire:</legend>
					<input type="checkbox" name="type-urbain" id="check-view-a" value="urbain" checked="checked"/> <label for="check-view-a">urbain</label>
					<input type="checkbox" name="type-rural" id="check-view-b" value="rural" /> <label for="check-view-b">rural</label>
					<input type="checkbox" name="type-montagnard" id="check-view-c" value="montagnard" /> <label for="check-view-c">montagnard</label>
					<input type="checkbox" name="type-maritime" id="check-view-d" value="maritime" /> <label for="check-view-d">maritime</label>
				</fieldset>
			</div>	
			<div data-role="fieldcontain">
				<label for="textinputu5"><?= _('Email') ?>: </label>
				<input id="textinputu5" name="email" placeholder="" value='<?= $_SESSION['myEuropeProfile']->email ?>' type="email" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu6"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value='<?= $_SESSION['myEuropeProfile']->phone ?>' type="tel" />
			</div>
			<div data-role="fieldcontain">
				<label for="desc"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"><?= $_SESSION['myEuropeProfile']->desc ?></textarea>
			</div>
			<br />
			<div data-role="fieldcontain">
				<label for="password"><?= _("Password") ?>:</label>
				<input type="password" id="password" name="password" />
			</div>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="<?= _('Validate') ?>"/>
			</div>
		</form>
	</div>
</div>
<? include("footer.php"); ?>
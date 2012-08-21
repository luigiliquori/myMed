<? include("header.php"); ?>

<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			_('Profile'),
			_('Validate'),
			"document.ExtendedProfileForm.submit();",
			"check") ?>
	<? include("notifications.php"); ?>
</div>

<div data-role="content">
	<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" class="compact">
		<input type="hidden" name="form" value="edit" />

		<fieldset id="textinputu0" data-role="controlgroup" data-mini="true" data-type="horizontal" style="display:inline-block;vertical-align: middle;">
			<legend><?= _('Role') ?>:</legend>
			<input type="radio" disabled="disabled" name="role" id="radio-view-a" value="Association" <?= $_SESSION['myEuropeProfile']->role == "Association"?"checked='checked'":"" ?>/>
			<label for="radio-view-a"><?= _('Association') ?></label>
			<input type="radio" disabled="disabled" name="role" id="radio-view-b" value="Entreprise" <?= $_SESSION['myEuropeProfile']->role == "Entreprise"?"checked='checked'":"" ?>/>
			<label for="radio-view-b"><?= _('Company') ?></label>
			<input type="radio" disabled="disabled" name="role" id="radio-view-e" value="EtabPublic" <?= $_SESSION['myEuropeProfile']->role == "EtabPublic"?"checked='checked'":"" ?>/>
			<label for="radio-view-e"><?= _('State-owned enterprise') ?></label>
			<input type="radio" disabled="disabled" name="role" id="radio-view-f" value="Mairie" <?= $_SESSION['myEuropeProfile']->role == "Mairie"?"checked='checked'":"" ?>/>
			<label for="radio-view-f"><?= _('Town hall') ?></label>
			<input type="radio" disabled="disabled" name="role" id="radio-view-g" value="Région" <?= $_SESSION['myEuropeProfile']->role == "Région"?"checked='checked'":"" ?>/>
			<label for="radio-view-g"><?= _('Region') ?></label>
			<input type="radio" disabled="disabled" name="role" id="radio-view-h" value="Département" <?= $_SESSION['myEuropeProfile']->role == "Département"?"checked='checked'":"" ?>/>
			<label for="radio-view-h"><?= _('Department') ?></label>
			<input type="radio" disabled="disabled" name="role" id="radio-view-i" value="Autre" <?= $_SESSION['myEuropeProfile']->role == "Autre"?"checked='checked'":"" ?>/>
			<label for="radio-view-i"><?= _('Other') ?></label>
		</fieldset>
			
		<label for="textinputu1"> <?= _('Name') ?>: </label>
		<input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['myEuropeProfile']->name ?>' type="text" />
		
		<label for="textinputu2"> <?= _('Activity') ?>: </label>
		<input id="textinputu2" name="activity" placeholder="" value='<?= $_SESSION['myEuropeProfile']->activity ?>' type="text" />
		
		<label for="textinputu3"> <?= _('Siret') ?>: </label>
		<input id="textinputu3" name="siret" placeholder="" value='<?= $_SESSION['myEuropeProfile']->siret ?>' type="text" />
		
		<label for="textinputu4"> <?= _('Address') ?>: </label>
		<input id="textinputu4" name="address" placeholder="" value='<?= $_SESSION['myEuropeProfile']->address ?>' type="text" />

		<label for="textinputu5"> <?= _('Email') ?>: </label>
		<input id="textinputu5" name="email" placeholder="" value='<?= $_SESSION['myEuropeProfile']->email ?>' type="email" />
		
		<label for="textinputu6"> <?= _('Phone') ?>: </label>
		<input id="textinputu6" name="phone" placeholder="" value='<?= $_SESSION['myEuropeProfile']->phone ?>' type="text" />
		
		<label for="textinputu7"> <?= _('Description') ?>: </label>
		<input id="textinputu7" name="desc" placeholder="" value='<?= $_SESSION['myEuropeProfile']->desc ?>' type="text" />
		
		<br />
		<label for="password"><?= _("Mot de passe") ?>:</label>
		<input type="password" name="password" />
		
		<div style="text-align: center;" >
			<input type="submit" data-inline="true" data-role="button" data-icon="check" value="<?= _('Validate') ?>"/>
		</div>
	</form>
</div>
<? include("footer.php"); ?>
<? include("header.php"); ?>


<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			_('Profile'),
			_('Validate'),
			"document.ExtendedProfileForm.submit();",
			"check") ?>
	<? include("notifications.php")?>
</div>

<div data-role="content">
	<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
		<input type="hidden" name="form" value="edit" />

		<label for="textinputu0" class="label"> <?= _('Role') ?>: </label>
		<input id="textinputu0" name="role" placeholder="" value='<?= $_SESSION['myEuropeProfile']->role ?>' type="text" />
			
		<label for="textinputu1" class="label"> <?= _('Name') ?>: </label>
		<input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['myEuropeProfile']->name ?>' type="text" />
		
		<label for="textinputu2" class="label"> <?= _('Validate') ?>: </label>
		<input id="textinputu2" name="activity" placeholder="" value='<?= $_SESSION['myEuropeProfile']->activity ?>' type="text" />
		
		<label for="textinputu3" class="label"> <?= _('Siret') ?>: </label>
		<input id="textinputu3" name="siret" placeholder="" value='<?= $_SESSION['myEuropeProfile']->siret ?>' type="text" />
		
		<label for="textinputu4" class="label"> <?= _('Address') ?>: </label>
		<input id="textinputu4" name="address" placeholder="" value='<?= $_SESSION['myEuropeProfile']->address ?>' type="text" />

		<label for="textinputu5" class="label"> <?= _('Email') ?>: </label>
		<input id="textinputu5" name="email" placeholder="" value='<?= $_SESSION['myEuropeProfile']->email ?>' type="email" />
		
		<label for="textinputu6" class="label"> <?= _('Phone') ?>: </label>
		<input id="textinputu6" name="phone" placeholder="" value='<?= $_SESSION['myEuropeProfile']->phone ?>' type="text" />
		
		<label for="textinputu7" class="label"> <?= _('Description') ?>: </label>
		<input id="textinputu7" name="desc" placeholder="" value='<?= $_SESSION['myEuropeProfile']->desc ?>' type="text" />
		
		<br />
		<label for="password" class="label"><?= _("Mot de passe") ?></label>
		<input type="password" name="password" />
		
		<div style="text-align: center;" >
			<input class="label" type="submit" data-inline="true" data-role="button" value="<?= _('Validate') ?>"/>
		</div>
	</form>
</div>
<? include("footer.php"); ?>
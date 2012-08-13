<? // Alias
$user = $this->_user;
global $READ_ONLY;
?>

<?  // Do not show this part for MyMed users that only need to fill extended profiles
if ($this->getUserType() != USER_MYMED) :
?>

<div data-role="wizard-page" id="mymed-profile" >

	<div data-role="header" data-theme="b">
		<h3><?= _("Profil MyMed") ?></h3>
	</div>
	
	<? input("text", "firstName", _("Prénom"), $user->firstName, "", true) ?>
	<? input("text", "lastName", _("Nom"), $user->lastName, "", true) ?>
	<? input("date", "birthday", _("Date de naissance"), $user->birthday) ?>
	<? input("email", "email", _("email"), $user->email, "", true) ?>
	
	<? if ($this->mode == MODE_CREATE && $this->user == null) : ?>
		<? input("password", "password", _("Mot de passe"), "", "", true) ?>
		<? input("password", "confirm", _("Mot de passe (confirmation)"), "", "", true) ?>
	<? endif ?>
	
	<? radiobuttons("lang", array(
				"fr" => "<img src='../../../system/img/flags/fr.png' /> Français",
				"en" => "<img src='../../../system/img/flags/en.png' /> English",
				"it" => "<img src='../../../system/img/flags/it.png' /> Italiano" ), 
				$user->lang, _("Langue")) 
	?>
	<div data-validate="lang" data-validate-non-empty >
		<?= _("Vous devez choisir une langue.") ?>
	</div>

</div>

<? endif; // End of "do not show it to registered mymed users" ?>





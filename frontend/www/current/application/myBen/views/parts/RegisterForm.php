<? // Alias
$user = $this->_user;
global $READ_ONLY;
?>

<? 
// Do not show this part for MyMed users that only need to fill extended profiles
if ($this->getUserType() != USER_MYMED) :
?>

<div data-role="wizard-page" id="mymed-profile" >

	<div data-role="header" data-theme="b">
		<h3><?= _("Profil MyMed") ?></h3>
	</div>
	
	<? $firtNameLabel = ($TYPE == ASSOCIATION) ? _("Nom de l'association") : _("Prénom") ?>
	<? input("text", "firstName", $firtNameLabel, $user->firstName, "", true) ?> 
	
	<? if ($TYPE == BENEVOLE) :?>
		<? input("text", "lastName", _("Nom"), $user->lastName, "", true) ?>
		<? input("date", "birthday", _("Date de naissance"), $user->birthday) ?>
	<? endif ?>
	
	<? input("email", "email", _("email"), $user->email, "", true) ?>
	
	<? if ($this->mode == MODE_CREATE && $this->user == null) : ?>
		<? input("password", "password", _("Mot de passe"), "", "", true) ?>
		<? input("password", "confirm", _("Mot de passe (confirmation)"), "", "", true) ?>
	<? endif ?>

</div><? // End of #wizard-page div ?>

<? endif; // End of "do nto show it to registered mymed users" ?>





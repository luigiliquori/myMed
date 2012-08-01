<? // Alias
$user = $this->_user;
global $READ_ONLY;
?>

<div data-role="header" data-theme="b">
	<h3>Profil MyMed</h3>
</div>

<? $firtNameLabel = ($TYPE == ASSOCIATION) ? "Nom de l'association" : "Prénom" ?>
<? input("text", "firstName", $firtNameLabel, $user->firstName, "", true) ?> 

<? if ($TYPE == BENEVOLE) :?>
	<? input("text", "lastName", "Nom", $user->lastName, "", true) ?>
	<? input("date", "birthday", "Date de naissance", $user->birthday) ?>
<? endif ?>

<? input("email", "email", "email", $user->email) ?>

<? if ($this->mode == MODE_CREATE && $this->user == null) : ?>
	<? input("password", "password", "Mot de passe", "", "", true) ?>
	<? input("password", "confirm", "Mot de passe (confirmation)", "", "", true) ?>
<? endif ?>



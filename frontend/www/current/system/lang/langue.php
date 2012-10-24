<?php 

define('FR', 'fr');
define('IT', 'it');
define('EN', 'en');
$_SESSION['dictionary'] = array();

/**
 * Function Diconnary
 * @param $word - the input word in english
 * @return the translated word
 */
function /*String*/ translate($word) {
	global $LANG;
	if($LANG != EN && isset($_SESSION['dictionary'][$word])) {
		return $_SESSION['dictionary'][$word][$LANG];
	} else {
		return $word;
	}
}


/*
    ____  ________________________  _   _____    ______  __
   / __ \/  _/ ____/_  __/  _/ __ \/ | / /   |  / __ \ \/ /
  / / / // // /     / /  / // / / /  |/ / /| | / /_/ /\  / 
 / /_/ // // /___  / / _/ // /_/ / /|  / ___ |/ _, _/ / /  
/_____/___/\____/ /_/ /___/\____/_/ |_/_/  |_/_/ |_| /_/   
 
 */

// COMMON
$word = 'Update';
$_SESSION['dictionary'][$word][FR] = "Mise à jour";
$_SESSION['dictionary'][$word][IT] = "Aggiornamento";

$word = 'Cancel';
$_SESSION['dictionary'][$word][FR] = "Annuler";
$_SESSION['dictionary'][$word][IT] = "Annulla";

// HEADER BAR
$word = 'Logout';
$_SESSION['dictionary'][$word][FR] = "Deconnexion";
$_SESSION['dictionary'][$word][IT] = "Disconnettersi";

$word = 'Social Network';
$_SESSION['dictionary'][$word][FR] = "Réseau Social Transfrontalier";
$_SESSION['dictionary'][$word][IT] = "Rete Sociale Transfrontaliera";

$word = 'Back';
$_SESSION['dictionary'][$word][FR] = "Retour";
$_SESSION['dictionary'][$word][IT] = "Indietro";

// TAB BAR
$word = 'Sign in';
$_SESSION['dictionary'][$word][FR] = "Connexion";
$_SESSION['dictionary'][$word][IT] = "Connettersi";

$word = 'Create an account';
$_SESSION['dictionary'][$word][FR] = "Créer un compte";
$_SESSION['dictionary'][$word][IT] = "Iscrizione";

$word = 'About';
$_SESSION['dictionary'][$word][FR] = "A propos";
$_SESSION['dictionary'][$word][IT] = "A proposito";

// LOGIN VIEW
$word = 'Login';
$_SESSION['dictionary'][$word][FR] = "Connexion";
$_SESSION['dictionary'][$word][IT] = "Connettersi";

$word = 'Password';
$_SESSION['dictionary'][$word][FR] = "Mot de passe";
$_SESSION['dictionary'][$word][IT] = "Password";

$word = 'Sign in with';
$_SESSION['dictionary'][$word][FR] = "S'enrerister avec";
$_SESSION['dictionary'][$word][IT] = "Registrarsi come";

$word = 'Together';
$_SESSION['dictionary'][$word][FR] = "Ensemble par-delà les frontières";
$_SESSION['dictionary'][$word][IT] = "Insieme oltre i confini";

// REGISTRATION VIEW
$word = 'First Name';
$_SESSION['dictionary'][$word][FR] = "Prénom / Activité commerciale";
$_SESSION['dictionary'][$word][IT] = "Nome / Attività commerciale";

$word = 'Last Name';
$_SESSION['dictionary'][$word][FR] = "Nom";
$_SESSION['dictionary'][$word][IT] = "Cognome";

$word = 'Birthday';
$_SESSION['dictionary'][$word][FR] = "Date de naissance";
$_SESSION['dictionary'][$word][IT] = "Data di nascita";

$word = 'Language';
$_SESSION['dictionary'][$word][FR] = "Langue";
$_SESSION['dictionary'][$word][IT] = "Lingua";

$word = 'Profile picture';
$_SESSION['dictionary'][$word][FR] = "Photo du profil";
$_SESSION['dictionary'][$word][IT] = "Foto profilo";

$word = 'Confirm';
$_SESSION['dictionary'][$word][FR] = "Confirmation";
$_SESSION['dictionary'][$word][IT] = "Conferma";

$word = 'I accept the general terms and conditions';
$_SESSION['dictionary'][$word][FR] = "J'accepte les conditions d'utilisation";
$_SESSION['dictionary'][$word][IT] = "Accetto le condizioni d'uso";

$word = 'Send';
$_SESSION['dictionary'][$word][FR] = "Valider";
$_SESSION['dictionary'][$word][IT] = "Validazione";

// ABOUT VIEW
$word = 'About';
$_SESSION['dictionary'][$word][FR] = "A propos";
$_SESSION['dictionary'][$word][IT] = "A proposito";

$word = 'More informations';
$_SESSION['dictionary'][$word][FR] = "Plus d'informations";
$_SESSION['dictionary'][$word][IT] = "Maggiori Informazioni";


?>
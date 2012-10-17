<?php 

define('FR', 'fr');
define('IT', 'it');
$_SESSION['dictionary'] = array();

/**
 * Function Diconnary
 * @param $word - the input word in english
 * @return the translated word
 */
function /*String*/ translate($word) {
	global $LANG;
	if(isset($_SESSION['dictionary'][$word])) {
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

// HEADER BAR
$word = 'Logout';
$_SESSION['dictionary'][$word][FR] = "Deconnexion";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Social Network';
$_SESSION['dictionary'][$word][FR] = "Réseau Social Transfrontalier";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Back';
$_SESSION['dictionary'][$word][FR] = "Retour";
$_SESSION['dictionary'][$word][IT] = "";

// TAB BAR
$word = 'Sign in';
$_SESSION['dictionary'][$word][FR] = "Connexion";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Create an account';
$_SESSION['dictionary'][$word][FR] = "Créer un compte";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'About';
$_SESSION['dictionary'][$word][FR] = "A propos";
$_SESSION['dictionary'][$word][IT] = "";


// LOGIN VIEW
$word = 'Login';
$_SESSION['dictionary'][$word][FR] = "Connexion";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Password';
$_SESSION['dictionary'][$word][FR] = "Mot de passe";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Sign in with';
$_SESSION['dictionary'][$word][FR] = "S'enrerister avec";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Together';
$_SESSION['dictionary'][$word][FR] = "Ensemble par-delà les frontières";
$_SESSION['dictionary'][$word][IT] = "";

// REGISTRATION VIEW
$word = 'First Name';
$_SESSION['dictionary'][$word][FR] = "Prénom / Activité commerciale";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Last Name';
$_SESSION['dictionary'][$word][FR] = "Name";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Confirm';
$_SESSION['dictionary'][$word][FR] = "Confirmation";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'I accept the general terms and conditions';
$_SESSION['dictionary'][$word][FR] = "J'accepte les conditions d'utilisation";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'Send';
$_SESSION['dictionary'][$word][FR] = "Valider";
$_SESSION['dictionary'][$word][IT] = "";

// ABOUT VIEW
$word = 'About';
$_SESSION['dictionary'][$word][FR] = "Le projet myMed est né d’une double constatation: l’existence d’un énorme potentiel de développement des activités économiques de la zone transfrontalière, objet de l’action Alcotra, et le manque criant d’infrastructures techniquement avancées en permettant un développement harmonieux. La proposition myMed est née d’une collaboration existante depuis plus de 15 ans entre l’Institut National de Recherche en Informatique et en Automatique (INRIA) de Sophia Antipolis et l’Ecole Polytechnique de Turin, auxquels viennent s’ajouter deux autres partenaires, l’Université de Turin et l’Université du Piémont Oriental.";
$_SESSION['dictionary'][$word][IT] = "";

$word = 'More informations';
$_SESSION['dictionary'][$word][FR] = "Plus d'informations";
$_SESSION['dictionary'][$word][IT] = "";


?>
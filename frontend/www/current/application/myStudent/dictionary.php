<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<?php
	define("FR", "FR");
	define("IT", "IT");
	
	if(isset($_GET["language"])){
		$_SESSION['language'] = $_GET["language"];
	} else {
		if(!isset($_SESSION["language"])){
			$_SESSION["language"] = "IT";
		}
	}
	
	define("LG",  $_SESSION["language"]);
	
	$_SESSION['dictionary'] = array();
	
	//---------------------------------------------------------------------------
	// ITALIANO -----------------------------------------------------------------
	//---------------------------------------------------------------------------
	$_SESSION['dictionary'][IT]["view1"] = "Benvenuto";
<<<<<<< HEAD
	$_SESSION['dictionary'][IT]["welcome"] = "<p>My Student è un'applicazione che ha come obiettivo quello di migliorare la comunicazione tra università e studenti. Attraverso myStudent le università possono pubblicare annunci relativi a Tesi, Stage, opportunità di lavoro; mentre gli studenti possono fruire di queste informazioni attraverso qualsiasi dispositivo che disponga di un browser e di una connessione ad internet.<br> E' anche possibile sottoscriversi ad uno o più argomenti di interesse e ricevere direttamente sulla propria email le notifiche dei nuovi contenuti pubblicati sul sito.</p> <p>Presto sarà inoltre possibile condividere materiale didattico ed anche offrire lezioni private! <br> STAY TUNED! </p>";
=======
	$_SESSION['dictionary'][IT]["welcome"] = 
	"<p>My Student è un applicazione che ha come obiettivo quello di raccogliere materiale  e fornire supporto didattico a professori e studenti. <br />
Il progetto nasce come appendice della piattaforma MyMed, la quale estende i propri servizi a tutte le regioni del Piemonte , Ponente Ligure, e parte della Cote Azur.

<p>Sul sito,è possibile condividere materiali didattici quali: appunti, tesine, informazioni su stage & job , contatti utili e tanto altro!<br />
Il tutto riguardanti i corsi e le materie relative alle facoltà delle regioni sopracitate.
<p>E' inoltre possibile sottoscriversi a tutti gli argomenti ed i contenuti presenti,per ricevere notifiche ogni qual volta vi siano aggiornamenti. 

<p>L'iscrizione è completamente gratuita!Cosa aspettate ;)?";
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	
	$_SESSION['dictionary'][IT]["view4"] = "Profilo";
	$_SESSION['dictionary'][IT]["name"] = "Cognome";
	$_SESSION['dictionary'][IT]["firstName"] = "Nome";
	$_SESSION['dictionary'][IT]["birthday"] = "Data di nascita";
	$_SESSION['dictionary'][IT]["profilePicture"] = "Profile photo (url)";
	$_SESSION['dictionary'][IT]["update"] = "Aggiorna";
	$_SESSION['dictionary'][IT]["connect"] = "Accedi";
	$_SESSION['dictionary'][IT]["disconnect"] = "Esci";
	$_SESSION['dictionary'][IT]["inscription"] = "Iscrizione";
	$_SESSION['dictionary'][IT]["password"] = "Password";
	$_SESSION['dictionary'][IT]["confirm"] = "Conferma password";
	$_SESSION['dictionary'][IT]["oldPassword"] = "Password precedente";
	$_SESSION['dictionary'][IT]["accept"] = "Accetto";
	$_SESSION['dictionary'][IT]["condition"] = "Condizioni d'uso";
	$_SESSION['dictionary'][IT]["validate"] = "Validazione";
<<<<<<< HEAD
	$_SESSION['dictionary'][IT]["noArg"] = "Inserire almeno un argomento di ricerca";
	$_SESSION['dictionary'][IT]["SubOK"] ="Se stato sottoscritto all'area selezionata, riceverai una mail di notifica ogni qualvota verrà pubblicato un articolo d'interesse";
	$_SESSION['dictionary'][IT]["SubMade"] = "Al momento sei sottoscritto ai seguenti argomenti";
	$_SESSION['dictionary'][IT]["SubYet"] = "Sei già sottoscritto a :";

=======
	
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	$_SESSION['dictionary'][IT]["view2"] = "Cerca";
	$_SESSION['dictionary'][IT]["view3"] = "Pubblica";
	
	$_SESSION['dictionary'][IT]["option"] = "Opzioni";
	
	$_SESSION['dictionary'][IT]["back"] = "Indietro";
	
	$_SESSION['dictionary'][IT]["noResult"] = "Nessun risultato";
	
	$_SESSION['dictionary'][IT]["pleaseLogin"] = "È necessario autenticarsi per utilizzare questa funzione...";
	
	$_SESSION['dictionary'][IT]["requestSent"] = "La vostra richiesta è stata correttamente inviata. Un amministratore di myStudent la controllerà e validerà.";
<<<<<<< HEAD

	$_SESSION['dictionary'][IT]["IncompleteSend"] = "La vostra richiesta non sarà validata è necessario inserire un Titolo e il Testo";

=======
	
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	$_SESSION['dictionary'][IT]["ontology0"] = "Titolo";
	
	$_SESSION['dictionary'][IT]["ontology1"] = "Area";
	$_SESSION['dictionary'][IT]["Area"][0] = "Aerospaziale";
	$_SESSION['dictionary'][IT]["Area"][1]="Ambientale";
	$_SESSION['dictionary'][IT]["Area"][2] = "Autoveicolo";
<<<<<<< HEAD
	$_SESSION['dictionary'][IT]["Area"][3] = "Biomeccanica";
=======
	$_SESSION['dictionary'][IT]["Area"][3] = "Biomeccania";
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	$_SESSION['dictionary'][IT]["Area"][4]="Cinema";
	$_SESSION['dictionary'][IT]["Area"][5] = "Civile";
	$_SESSION['dictionary'][IT]["Area"][6] = "Elettrica";
	$_SESSION['dictionary'][IT]["Area"][7] = "Elettronica";
	$_SESSION['dictionary'][IT]["Area"][8]="Energetica";
	$_SESSION['dictionary'][IT]["Area"][9] = "Fisica";
	$_SESSION['dictionary'][IT]["Area"][10]="Gestionale";
	$_SESSION['dictionary'][IT]["Area"][11]="Informatica";
	$_SESSION['dictionary'][IT]["Area"][12]="Matematica";
	$_SESSION['dictionary'][IT]["Area"][13]="Materiali";
	$_SESSION['dictionary'][IT]["Area"][14]="Meccanica";
	$_SESSION['dictionary'][IT]["Area"][15]="Telecomunicazioni";
	
	
	$_SESSION['dictionary'][IT]["ontology2"] = "Categoria";
	$_SESSION['dictionary'][IT]["Categoria"][0] = "Stage";
	$_SESSION['dictionary'][IT]["Categoria"][1] = "Job";
	$_SESSION['dictionary'][IT]["Categoria"][2] = "Tesi";
	$_SESSION['dictionary'][IT]["Categoria"][3] = "Appunti";
	
//	$_SESSION['dictionary'][IT]["ontology3"] = "Categorie";
//	$_SESSION['dictionary'][IT]["Categorie"][0] = "Arte/Cultura";
//	$_SESSION['dictionary'][IT]["Categorie"][1] = "Natura";
//	$_SESSION['dictionary'][IT]["Categorie"][2] = "Tradizioni";
//	$_SESSION['dictionary'][IT]["Categorie"][3] = "Enogastronomia";
//	$_SESSION['dictionary'][IT]["Categorie"][4] = "Benessere";
//	$_SESSION['dictionary'][IT]["Categorie"][5] = "Storia";
//	$_SESSION['dictionary'][IT]["Categorie"][6] = "Religione";
//	$_SESSION['dictionary'][IT]["Categorie"][7] = "Escursioni/Sport";
	
	$_SESSION['dictionary'][IT]["ontology4"] = "Testo";
	
	$_SESSION['dictionary'][IT]["view5"] = "Admin";
	$_SESSION['dictionary'][IT]["view6"] = "myMed";
<<<<<<< HEAD
	$_SESSION['dictionary'][IT]["view7"] = "Sottoscrizione";
=======
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	
	//---------------------------------------------------------------------------
	// FRANCAIS -----------------------------------------------------------------
	//---------------------------------------------------------------------------
	$_SESSION['dictionary'][FR]["view1"] = "Bienvenu";
<<<<<<< HEAD
	$_SESSION['dictionary'][FR]["welcome"] = "<p>My Student è un'applicazione che ha come obiettivo quello di migliorare la comunicazione tra università e studenti. Attraverso myStudent le università possono pubblicare annunci relativi a Tesi, Stage, opportunità di lavoro; mentre gli studenti possono fruire di queste informazioni attraverso qualsiasi dispositivo che disponga di un browser e di una connessione ad internet.<br> E' anche possibile sottoscriversi ad uno o più argomenti di interesse e ricevere direttamente sulla propria email le notifiche dei nuovi contenuti pubblicati sul sito.</p> <p>Presto sarà inoltre possibile condividere materiale didattico ed anche offrire lezioni private! <br> STAY TUNED! </p>";
=======
	$_SESSION['dictionary'][FR]["welcome"] =
		"<p>My Student è un applicazione che ha come obiettivo quello di raccogliere materiale  e fornire supporto didattico a professori e studenti. <br />
Il progetto nasce come appendice della piattaforma MyMed, la quale estende i propri servizi a tutte le regioni del Piemonte , Ponente Ligure, e parte della Cote Azur.

<p>Sul sito,è possibile condividere materiali didattici quali: appunti, tesine, informazioni su stage & job , contatti utili e tanto altro!<br />
Il tutto riguardanti i corsi e le materie relative alle facoltà delle regioni sopracitate.
<p>E' inoltre possibile sottoscriversi a tutti gli argomenti ed i contenuti presenti,per ricevere notifiche ogni qual volta vi siano aggiornamenti. 

<p>L'iscrizione è completamente gratuita!Cosa aspettate ;)?";
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	
	$_SESSION['dictionary'][FR]["view4"] = "Profil";
	$_SESSION['dictionary'][FR]["name"] = "Nom";
	$_SESSION['dictionary'][FR]["firstName"] = "Prénom";
	$_SESSION['dictionary'][FR]["birthday"] = "Date de naissance";
	$_SESSION['dictionary'][FR]["profilePicture"] = "Photo de profil (url)";
	$_SESSION['dictionary'][FR]["update"] = "Mise à jour";
	$_SESSION['dictionary'][FR]["connect"] = "Connexion";
	$_SESSION['dictionary'][FR]["disconnect"] = "Deconnexion";
	$_SESSION['dictionary'][FR]["inscription"] = "Inscription";
	$_SESSION['dictionary'][FR]["oldPassword"] = "Ancien Mot de passe";
	$_SESSION['dictionary'][FR]["password"] = "Mot de passe";
	$_SESSION['dictionary'][FR]["confirm"] = "Confirmation";
	$_SESSION['dictionary'][FR]["accept"] = "J'accepte les";
	$_SESSION['dictionary'][FR]["condition"] = "conditions d'utilisation";
	$_SESSION['dictionary'][FR]["validate"] = "Valider";
<<<<<<< HEAD
	$_SESSION['dictionary'][FR]["noArg"] = "Entrez au moins un argument de recherche";
	$_SESSION['dictionary'][FR]["SubOK"] ="Si vous êtes abonné à la zone sélectionnée recevra une notification par e-mail chaque qualvota seront publiés dans un article d'intérêt";
	$_SESSION['dictionary'][FR]["SubMade"] = "Vous êtes actuellement abonné à des sujets suivants";

=======
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	
	$_SESSION['dictionary'][FR]["view2"] = "Rechercher";
	$_SESSION['dictionary'][FR]["view3"] = "Publier";
	
	$_SESSION['dictionary'][FR]["option"] = "Option";
	
	$_SESSION['dictionary'][FR]["back"] = "Retour";
	
	$_SESSION['dictionary'][FR]["noResult"] = "Pas de résultat";
	
	$_SESSION['dictionary'][FR]["pleaseLogin"] = "Veuillez vous <a href='#ProfileView'>authentifier</a> avant d'utiliser cette fonction...";
	
	$_SESSION['dictionary'][FR]["requestSent"] = "Votre requête à bien été envoyé. Elle est maintenant en attente de validation par un administrateur de myStudent.";
<<<<<<< HEAD

	$_SESSION['dictionary'][FR]["IncompleteSend"] = "Votre demande ne sera pas validé devez entrer un Titre et du texte";
=======
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	
	$_SESSION['dictionary'][FR]["ontology0"] = "Titre";
	
	$_SESSION['dictionary'][FR]["ontology1"] = "Zone";
	$_SESSION['dictionary'][FR]["Area"][0] = "Aéronautique";
	$_SESSION['dictionary'][FR]["Area"][1] = "Environnement";
	$_SESSION['dictionary'][FR]["Area"][2] = "Véhicules automobiles";
	$_SESSION['dictionary'][FR]["Area"][3] = "Biomeccania";
	$_SESSION['dictionary'][FR]["Area"][4] = "Cinèma";
	$_SESSION['dictionary'][FR]["Area"][5] = "Civil";
	$_SESSION['dictionary'][FR]["Area"][6] = "Èlectricité";
	$_SESSION['dictionary'][FR]["Area"][7] = "Electronics";
	$_SESSION['dictionary'][FR]["Area"][8] = "Energie";
	$_SESSION['dictionary'][FR]["Area"][9] = "Physique";
	$_SESSION['dictionary'][FR]["Area"][10] = "Gestion";
	$_SESSION['dictionary'][FR]["Area"][11] = "Informatique";
	$_SESSION['dictionary'][FR]["Area"][12] = "Mathématiques";
	$_SESSION['dictionary'][FR]["Area"][13] = "Matériaux";
	$_SESSION['dictionary'][FR]["Area"][14] = "Mécanique";
	$_SESSION['dictionary'][FR]["Area"][15] = "Télécommunications";
	
<<<<<<< HEAD
	$_SESSION['dictionary'][FR]["ontology2"] = "Catégorie";
=======
	$_SESSION['dictionary'][FR]["ontology2"] = "catégorie";
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	$_SESSION['dictionary'][FR]["Categoria"][0] = "Stage";
	$_SESSION['dictionary'][FR]["Categoria"][1] = "Job";
	$_SESSION['dictionary'][FR]["Categoria"][2] = "Thèse";
	$_SESSION['dictionary'][FR]["Categoria"][3] = "Remarques";
	
	$_SESSION['dictionary'][FR]["ontology3"] = "Categorie";
	$_SESSION['dictionary'][FR]["Categorie"][0] = "Art/Culture";
	$_SESSION['dictionary'][FR]["Categorie"][1] = "Nature";
	$_SESSION['dictionary'][FR]["Categorie"][2] = "Traditions";
	$_SESSION['dictionary'][FR]["Categorie"][3] = "Gastronomie";
	$_SESSION['dictionary'][FR]["Categorie"][4] = "Bien-être";
	$_SESSION['dictionary'][FR]["Categorie"][5] = "Histoire";
	$_SESSION['dictionary'][FR]["Categorie"][6] = "Religion";
	$_SESSION['dictionary'][FR]["Categorie"][7] = "Excursions/Sport";
	
	$_SESSION['dictionary'][FR]["ontology4"] = "Texte";
	
	$_SESSION['dictionary'][FR]["view5"] = "Admin";
	$_SESSION['dictionary'][FR]["view6"] = "myMed";
<<<<<<< HEAD
	$_SESSION['dictionary'][FR]["view7"] = "Abonnement";
=======
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
	
?>

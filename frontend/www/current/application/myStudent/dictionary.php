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
	$_SESSION['dictionary'][IT]["welcome"] = "<p>My Student è un'applicazione che ha come obiettivo quello di migliorare la comunicazione tra università e studenti. Attraverso myStudent le università possono pubblicare annunci relativi a Tesi, Stage, opportunità di lavoro; mentre gli studenti possono fruire di queste informazioni attraverso qualsiasi dispositivo che disponga di un browser e di una connessione ad internet.<br> E' anche possibile sottoscriversi ad uno o più argomenti di interesse e ricevere direttamente sulla propria email le notifiche dei nuovi contenuti pubblicati sul sito.</p> <p>Presto sarà inoltre possibile condividere materiale didattico ed anche offrire lezioni private! <br> STAY TUNED! </p>";
	
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
	$_SESSION['dictionary'][IT]["noArg"] = "Inserire almeno un argomento di ricerca";
	$_SESSION['dictionary'][IT]["SubOK"] ="Se stato sottoscritto all'area selezionata, riceverai una mail di notifica ogni qualvota verrà pubblicato un articolo d'interesse";
	$_SESSION['dictionary'][IT]["SubMade"] = "Al momento sei sottoscritto ai seguenti argomenti";
	$_SESSION['dictionary'][IT]["SubYet"] = "Sei già sottoscritto a :";

	$_SESSION['dictionary'][IT]["view2"] = "Cerca";
	$_SESSION['dictionary'][IT]["view3"] = "Pubblica";
	
	$_SESSION['dictionary'][IT]["option"] = "Opzioni";
	
	$_SESSION['dictionary'][IT]["back"] = "Indietro";
	
	$_SESSION['dictionary'][IT]["noResult"] = "Nessun risultato";
	
	$_SESSION['dictionary'][IT]["pleaseLogin"] = "È necessario autenticarsi per utilizzare questa funzione...";
	
	$_SESSION['dictionary'][IT]["requestSent"] = "La vostra richiesta è stata correttamente inviata. Un amministratore di myStudent la controllerà e validerà.";

	$_SESSION['dictionary'][IT]["IncompleteSend"] = "La vostra richiesta non sarà validata è necessario inserire un Titolo e il Testo";

	$_SESSION['dictionary'][IT]["ontology0"] = "Titolo";
	
	$_SESSION['dictionary'][IT]["ontology1"] = "Area";
	$_SESSION['dictionary'][IT]["Area"][0] = "Aerospaziale";
	$_SESSION['dictionary'][IT]["Area"][1]="Ambientale";
	$_SESSION['dictionary'][IT]["Area"][2] = "Autoveicolo";
	$_SESSION['dictionary'][IT]["Area"][3] = "Biomeccanica";
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
	$_SESSION['dictionary'][IT]["view7"] = "Sottoscrizione";
	
	//---------------------------------------------------------------------------
	// FRANCAIS -----------------------------------------------------------------
	//---------------------------------------------------------------------------
	$_SESSION['dictionary'][FR]["view1"] = "Bienvenu";
	$_SESSION['dictionary'][FR]["welcome"] = "<p>My Student è un'applicazione che ha come obiettivo quello di migliorare la comunicazione tra università e studenti. Attraverso myStudent le università possono pubblicare annunci relativi a Tesi, Stage, opportunità di lavoro; mentre gli studenti possono fruire di queste informazioni attraverso qualsiasi dispositivo che disponga di un browser e di una connessione ad internet.<br> E' anche possibile sottoscriversi ad uno o più argomenti di interesse e ricevere direttamente sulla propria email le notifiche dei nuovi contenuti pubblicati sul sito.</p> <p>Presto sarà inoltre possibile condividere materiale didattico ed anche offrire lezioni private! <br> STAY TUNED! </p>";
	
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
	$_SESSION['dictionary'][FR]["noArg"] = "Entrez au moins un argument de recherche";
	$_SESSION['dictionary'][FR]["SubOK"] ="Si vous êtes abonné à la zone sélectionnée recevra une notification par e-mail chaque qualvota seront publiés dans un article d'intérêt";
	$_SESSION['dictionary'][FR]["SubMade"] = "Vous êtes actuellement abonné à des sujets suivants";

	
	$_SESSION['dictionary'][FR]["view2"] = "Rechercher";
	$_SESSION['dictionary'][FR]["view3"] = "Publier";
	
	$_SESSION['dictionary'][FR]["option"] = "Option";
	
	$_SESSION['dictionary'][FR]["back"] = "Retour";
	
	$_SESSION['dictionary'][FR]["noResult"] = "Pas de résultat";
	
	$_SESSION['dictionary'][FR]["pleaseLogin"] = "Veuillez vous <a href='#ProfileView'>authentifier</a> avant d'utiliser cette fonction...";
	
	$_SESSION['dictionary'][FR]["requestSent"] = "Votre requête à bien été envoyé. Elle est maintenant en attente de validation par un administrateur de myStudent.";

	$_SESSION['dictionary'][FR]["IncompleteSend"] = "Votre demande ne sera pas validé devez entrer un Titre et du texte";
	
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
	
	$_SESSION['dictionary'][FR]["ontology2"] = "Catégorie";
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
	$_SESSION['dictionary'][FR]["view7"] = "Abonnement";
	
?>

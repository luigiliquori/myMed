<?php
	define("FR", "FR");
	define("IT", "IT");
	
	if(isset($_GET["language"])){
		$_SESSION['language'] = $_GET["language"];
	} else {
		if(!isset($_SESSION["language"])){
			$_SESSION["language"] = "FR";
		}
	}
	
	define("LG",  $_SESSION["language"]);
	
	$_SESSION['dictionary'] = array();
	
	//---------------------------------------------------------------------------
	// ITALIANO -----------------------------------------------------------------
	//---------------------------------------------------------------------------
	$_SESSION['dictionary'][IT]["view1"] = "Benvenuto";
	$_SESSION['dictionary'][IT]["welcome"] = 
	"<p>EURO C.I.N. GEIE (Gruppo Europeo di Interesse Economico) fondato nel 1994, dalle Camere di commercio transfrontaliere di Cuneo, Imperia e Nizza, si compone oggi di istituzioni e aziende che rappresentano i territori di Piemonte, Liguria e Provence Alpes Côte d’Azur. Oltre ai tre fondatori (Cuneo, Imperia e Nizza) gli altri membri sono le Camere di commercio di Asti, Alessandria, Genova, Unioncamere Piemonte, Autorità Portuale di Savona, Comune di Cuneo, BRE Banca Cuneo e GEAC Spa.</p>
	<p>Tra gli obiettivi del Gruppo la volontà di creare un’immagine globale e comune all’interno e all’esterno dell’Euroregione (chiamata delle Alpi del Mare) favorendo l’integrazione economica, culturale e scientifica, lo sviluppo dei flussi transfrontalieri e la promozione dei territori che ne fanno parte con le loro peculiarità e tradizioni.</p>
	<p>Attraverso myEurocin, il Gruppo si propone di presentare e suggerire ai visitatori le mete più suggestive, contemplando natura e benessere, curiosità storiche e artistiche e i numerosi prodotti tipici che caratterizzano l’Euroregione.</p>
	<p>I contenuti di myEurocin sono liberamente consultabili dai visitatori, ai quali chiediamo la loro collaborazione per migliorare l’applicazione, fornendo informazioni e suggerimenti. Ricordiamo agli utenti che l’inserimento di nuovi contenuti è possibile previa autenticazione al sito.</p>";
	
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
	
	$_SESSION['dictionary'][IT]["view2"] = "Cerca";
	$_SESSION['dictionary'][IT]["view3"] = "Pubblica";
	
	$_SESSION['dictionary'][IT]["option"] = "Opzioni";
	
	$_SESSION['dictionary'][IT]["back"] = "Indietro";
	
	$_SESSION['dictionary'][IT]["noResult"] = "Nessun risultato";
	
	$_SESSION['dictionary'][IT]["pleaseLogin"] = "È necessario autenticarsi per utilizzare questa funzione...";
	
	$_SESSION['dictionary'][IT]["requestSent"] = "La vostra richiesta è stata correttamente inviata. Un amministratore di myEuroCIN la controllerà e validerà.";
	
	$_SESSION['dictionary'][IT]["ontology0"] = "Titolo";
	
	$_SESSION['dictionary'][IT]["ontology1"] = "Località";
	$_SESSION['dictionary'][IT]["Nazione"][0] = "Alessandria";
	$_SESSION['dictionary'][IT]["Nazione"][1] = "Asti";
	$_SESSION['dictionary'][IT]["Nazione"][2] = "Cuneo";
	$_SESSION['dictionary'][IT]["Nazione"][3] = "Francia";
	$_SESSION['dictionary'][IT]["Nazione"][4] = "Genova";
	$_SESSION['dictionary'][IT]["Nazione"][5] = "Imperia";
	$_SESSION['dictionary'][IT]["Nazione"][6] = "Savona";
	
	$_SESSION['dictionary'][IT]["ontology2"] = "Lingua";
	$_SESSION['dictionary'][IT]["Ligua"][0] = "Italiano";
	$_SESSION['dictionary'][IT]["Ligua"][1] = "Francese";
	
	$_SESSION['dictionary'][IT]["ontology3"] = "Categorie";
	$_SESSION['dictionary'][IT]["Categorie"][0] = "Arte/Cultura";
	$_SESSION['dictionary'][IT]["Categorie"][1] = "Natura";
	$_SESSION['dictionary'][IT]["Categorie"][2] = "Tradizioni";
	$_SESSION['dictionary'][IT]["Categorie"][3] = "Enogastronomia";
	$_SESSION['dictionary'][IT]["Categorie"][4] = "Benessere";
	$_SESSION['dictionary'][IT]["Categorie"][5] = "Storia";
	$_SESSION['dictionary'][IT]["Categorie"][6] = "Religione";
	$_SESSION['dictionary'][IT]["Categorie"][7] = "Escursioni/Sport";
	
	$_SESSION['dictionary'][IT]["ontology4"] = "Testo";
	
	$_SESSION['dictionary'][IT]["view5"] = "Admin";
	
	//---------------------------------------------------------------------------
	// FRANCAIS -----------------------------------------------------------------
	//---------------------------------------------------------------------------
	$_SESSION['dictionary'][FR]["view1"] = "Bienvenu";
	$_SESSION['dictionary'][FR]["welcome"] =
		"Hello World, I'm a test application, and an android too. Viva la robolution, Death to mankind!";
	
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
	
	$_SESSION['dictionary'][FR]["view2"] = "Rechercher";
	$_SESSION['dictionary'][FR]["view3"] = "Publier";
	
	$_SESSION['dictionary'][FR]["option"] = "Option";
	
	$_SESSION['dictionary'][FR]["back"] = "Retour";
	
	$_SESSION['dictionary'][FR]["noResult"] = "Pas de résultat";
	
	$_SESSION['dictionary'][FR]["pleaseLogin"] = "Veuillez vous <a href='#ProfileView'>authentifier</a> avant d'utiliser cette fonction...";
	
	$_SESSION['dictionary'][FR]["requestSent"] = "Votre requête à bien été envoyé. Elle est maintenant en attente de validation par un administrateur de myEuroCIN.";
	
	$_SESSION['dictionary'][FR]["ontology0"] = "Titre";
	
	$_SESSION['dictionary'][FR]["ontology1"] = "Lieu";
	$_SESSION['dictionary'][FR]["Nazione"][0] = "Alessandria";
	$_SESSION['dictionary'][FR]["Nazione"][1] = "Asti";
	$_SESSION['dictionary'][FR]["Nazione"][2] = "Cuneo";
	$_SESSION['dictionary'][FR]["Nazione"][3] = "France";
	$_SESSION['dictionary'][FR]["Nazione"][4] = "Genova";
	$_SESSION['dictionary'][FR]["Nazione"][5] = "Imperia";
	$_SESSION['dictionary'][FR]["Nazione"][6] = "Savona";
	
	$_SESSION['dictionary'][FR]["ontology2"] = "Langue";
	$_SESSION['dictionary'][FR]["Ligua"][0] = "Italien";
	$_SESSION['dictionary'][FR]["Ligua"][1] = "Français";
	
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
	
	/*
	 * MyAlzheimer
	 */
	$_SESSION['dictionary'][FR]["PleaseCompleteProfile"] = "Ceci est votre premiere utilisation de l'application, veuillez";
	$_SESSION['dictionary'][FR]["CompleteProfile"] = "completer votre profil.";
	$_SESSION['dictionary'][FR]["myMemProfile"] = "Profil spécifique à l'application MyMem";
	$_SESSION['dictionary'][FR]["angel"] = "Aidant-naturel officiel";
	$_SESSION['dictionary'][FR]["angelName"] = "Nom de l'aidant-naturel officiel";
	$_SESSION['dictionary'][FR]["angelEmail"] = "E-mail de l'aidant-naturel officiel";
	$_SESSION['dictionary'][FR]["diseaseLevel"] = "Niveau de la maladie";
	$_SESSION['dictionary'][FR]["diseaseLevel_low"] = "Légère";
	$_SESSION['dictionary'][FR]["diseaseLevel_med"] = "Modérée";
	$_SESSION['dictionary'][FR]["diseaseLevel_high"] = "Avancée";
	$_SESSION['dictionary'][FR]["meds"] = "Médication";
	$_SESSION['dictionary'][FR]["callOrder"] = "Ordre d'appel des aidants";
	$_SESSION['dictionary'][FR]["acceptGeoLoc"] = "J'accepte d'être géolocalisé";
?>

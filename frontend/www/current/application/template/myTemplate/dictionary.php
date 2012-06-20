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
	
	$_SESSION['dictionary'][IT]["ontology0"] = "Key1";
	$_SESSION['dictionary'][IT]["ontology1"] = "Key2";
	$_SESSION['dictionary'][IT]["ontology2"] = "Key3";
	$_SESSION['dictionary'][IT]["ontology3"] = "Key4";
	$_SESSION['dictionary'][IT]["ontology4"] = "Key5";
	
	$_SESSION['dictionary'][IT]["view5"] = "Admin";
	
	//---------------------------------------------------------------------------
	// FRANCAIS -----------------------------------------------------------------
	//---------------------------------------------------------------------------
	$_SESSION['dictionary'][FR]["view1"] = "Bienvenu";
	$_SESSION['dictionary'][FR]["welcome"] =
		"<p>EURO C.I.N. GEIE (Groupement Européen d’Intérêt Économique) est né en 1994 entre les Chambres de commerce transfrontalières de Cuneo, Imperia et Nice et aujourd’hui est composé d’institutions et entreprises qui représentent les territoires du Piémont, de la Ligurie et de la Provence Alpes Côte d’Azur. Hormis les trois fondateurs (Cuneo, Imperia et Nice) les autres membres sont les Chambres de commerce de Asti, Alessandria, Genova, Mairie de Cuneo, Unioncamere Piemonte, Autorità Portuale di Savona, BRE Banca Cuneo e GEAC Spa.
		</p><p>
Parmi ses buts, favoriser l’intégration économique, culturelle et scientifique par le développement des flux transfrontaliers et par la promotion commune de son image et des ses territoires avec leurs atouts, aussi bien à l’intérieur qu’à l’extérieur de l’Eurorégion des “Alpes de la Mer”.
</p><p>
Grâce à myEurocin, le Groupement envisage de présenter aux visiteurs de l’application les endroits les plus attractifs avec une attention à la nature, au bien-être, ainsi qu’à l’historie, à l’art et aux nombreux produits typiques qui caractérisent l’Eurorégion.
</p><p>
Les contenus de myEurocin sont consultables par les visiteurs, auxquels nous demandons de collaborer à l’amélioration de l’application en nous adressant des infos et des suggestions. Nous rappelons aux utilisateurs que les commentaires aux textes sont possibles ayant créé leur compte au préalable..</p>";
	
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
	
	$_SESSION['dictionary'][FR]["ontology0"] = "Key1";
	$_SESSION['dictionary'][FR]["ontology1"] = "Key2";
	$_SESSION['dictionary'][FR]["ontology2"] = "Key3";
	$_SESSION['dictionary'][FR]["ontology3"] = "Key4";
	$_SESSION['dictionary'][FR]["ontology4"] = "Key5";
	
	$_SESSION['dictionary'][FR]["view5"] = "Admin";
	
?>
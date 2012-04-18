<?php
	define(FR, "FR");
	define(IT, "IT");
	
	if(isset($_GET["language"])){
		$_SESSION['language'] = $_GET["language"];
	} else {
		if(!isset($_SESSION["language"])){
			$_SESSION["language"] = "IT";
		}
	}
	
	define(LG,  $_SESSION["language"]);
	
	$_SESSION['dictionary'] = array();
	
	//---------------------------------------------------------------------------
	// ITALIANO -----------------------------------------------------------------
	//---------------------------------------------------------------------------
	$_SESSION['dictionary'][IT]["view1"] = "Benvenuto";
	$_SESSION['dictionary'][IT]["welcome"] = 
	"<p>EURO C.I.N. GEIE (Gruppo Europeo di Interesse Economico) fondato nel 1994, dalle Camere di commercio transfrontaliere di Cuneo, Imperia e Nizza, si compone oggi di istituzioni e aziende che rappresentano i territori di Piemonte, Liguria e Provence Alpes Côte d’Azur. Oltre ai tre fondatori (Cuneo, Imperia e Nizza) gli altri membri sono le Camere di commercio di Asti, Alessandria, Genova, Unioncamere Piemonte, Autorità Portuale di Savona, BRE Banca Cuneo e GEAC Spa.</p>
	<p>Tra gli obiettivi del Gruppo la volontà di creare un’immagine globale e comune all’interno e all’esterno dell’Euroregione (chiamata delle “Alpi del Mare) favorendo l’integrazione economica, culturale e scientifica, lo sviluppo dei flussi transfrontalieri e la promozione dei territori che ne fanno parte con le loro peculiarità e tradizioni.</p>
	<p>Attraverso myEurocin, il Gruppo si propone di presentare e suggerire ai visitatori le mete più suggestive, contemplando natura e benessere, curiosità storiche e artistiche e i numerosi prodotti tipici che caratterizzano l’Euroregione.</p>
	<p>I contenuti di myEurocin sono liberamente consultabili dai visitatori, al quale chiediamo collaborazione per migliorare l’applicazione, fornendo informazioni e suggerimenti. Ricordiamo agli utenti che l’inserimento di nuovi contenuti è possibile previa autenticazione al sito.</p>";
	
	$_SESSION['dictionary'][IT]["view4"] = "Profil";
	$_SESSION['dictionary'][IT]["name"] = "Nom";
	$_SESSION['dictionary'][IT]["firstName"] = "Prénom";
	$_SESSION['dictionary'][IT]["birthday"] = "Date de naissance";
	$_SESSION['dictionary'][IT]["update"] = "Mise à jour";
	$_SESSION['dictionary'][IT]["connect"] = "Connexion";
	$_SESSION['dictionary'][IT]["disconnect"] = "Deconnexion";
	$_SESSION['dictionary'][IT]["inscription"] = "Inscription";
	
	$_SESSION['dictionary'][IT]["view2"] = "Cercare";
	$_SESSION['dictionary'][IT]["view3"] = "Publicare";
	
	$_SESSION['dictionary'][IT]["option"] = "Optione";
	
	$_SESSION['dictionary'][IT]["back"] = "Retour";
	
	$_SESSION['dictionary'][IT]["noResult"] = "Pas de résultat";
	
	$_SESSION['dictionary'][IT]["ontology0"] = "Titolo";
	
	$_SESSION['dictionary'][IT]["ontology1"] = "Nazione";
	$_SESSION['dictionary'][IT]["Nazione"][0] = "Alessandria";
	$_SESSION['dictionary'][IT]["Nazione"][1] = "Asti";
	$_SESSION['dictionary'][IT]["Nazione"][2] = "Cuneo";
	$_SESSION['dictionary'][IT]["Nazione"][3] = "Francia";
	$_SESSION['dictionary'][IT]["Nazione"][4] = "Genova";
	$_SESSION['dictionary'][IT]["Nazione"][5] = "Imperia";
	$_SESSION['dictionary'][IT]["Nazione"][6] = "Savona";
	
	$_SESSION['dictionary'][IT]["ontology2"] = "Ligua";
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
		"<p>EURO C.I.N. GEIE (Gruppo Europeo di Interesse Economico) fondato nel 1994, dalle Camere di commercio transfrontaliere di Cuneo, Imperia e Nizza, si compone oggi di istituzioni e aziende che rappresentano i territori di Piemonte, Liguria e Provence Alpes Côte d’Azur. Oltre ai tre fondatori (Cuneo, Imperia e Nizza) gli altri membri sono le Camere di commercio di Asti, Alessandria, Genova, Unioncamere Piemonte, Autorità Portuale di Savona, BRE Banca Cuneo e GEAC Spa.</p>
		<p>Tra gli obiettivi del Gruppo la volontà di creare un’immagine globale e comune all’interno e all’esterno dell’Euroregione (chiamata delle “Alpi del Mare) favorendo l’integrazione economica, culturale e scientifica, lo sviluppo dei flussi transfrontalieri e la promozione dei territori che ne fanno parte con le loro peculiarità e tradizioni.</p>
		<p>Attraverso myEurocin, il Gruppo si propone di presentare e suggerire ai visitatori le mete più suggestive, contemplando natura e benessere, curiosità storiche e artistiche e i numerosi prodotti tipici che caratterizzano l’Euroregione.</p>
		<p>I contenuti di myEurocin sono liberamente consultabili dai visitatori, al quale chiediamo collaborazione per migliorare l’applicazione, fornendo informazioni e suggerimenti. Ricordiamo agli utenti che l’inserimento di nuovi contenuti è possibile previa autenticazione al sito.</p>";
	
	$_SESSION['dictionary'][FR]["view4"] = "Profil";
	$_SESSION['dictionary'][FR]["name"] = "Nom";
	$_SESSION['dictionary'][FR]["firstName"] = "Prénom";
	$_SESSION['dictionary'][FR]["birthday"] = "Date de naissance";
	$_SESSION['dictionary'][FR]["update"] = "Mise à jour";
	$_SESSION['dictionary'][FR]["connect"] = "Connexion";
	$_SESSION['dictionary'][FR]["disconnect"] = "Deconnexion";
	$_SESSION['dictionary'][FR]["inscription"] = "Inscription";
	
	$_SESSION['dictionary'][FR]["view2"] = "Rechercher";
	$_SESSION['dictionary'][FR]["view3"] = "Publier";
	
	$_SESSION['dictionary'][FR]["option"] = "Option";
	
	$_SESSION['dictionary'][FR]["back"] = "Retour";
	
	$_SESSION['dictionary'][FR]["noResult"] = "Pas de résultat";
	
	$_SESSION['dictionary'][FR]["ontology0"] = "Titre";
	
	$_SESSION['dictionary'][FR]["ontology1"] = "Lieu";
	$_SESSION['dictionary'][FR]["Nazione"][0] = "Alessandria";
	$_SESSION['dictionary'][FR]["Nazione"][1] = "Asti";
	$_SESSION['dictionary'][FR]["Nazione"][2] = "Cuneo";
	$_SESSION['dictionary'][FR]["Nazione"][3] = "Francia";
	$_SESSION['dictionary'][FR]["Nazione"][4] = "Genova";
	$_SESSION['dictionary'][FR]["Nazione"][5] = "Imperia";
	$_SESSION['dictionary'][FR]["Nazione"][6] = "Savona";
	
	$_SESSION['dictionary'][FR]["ontology2"] = "Langue";
	$_SESSION['dictionary'][FR]["Ligua"][0] = "Italiano";
	$_SESSION['dictionary'][FR]["Ligua"][1] = "Francese";
	
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
	
?>

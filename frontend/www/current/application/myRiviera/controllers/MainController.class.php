<?

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends AuthenticatedController {

	public /*String[]*/ $filterList = array(
				// CARF
// 				"ADAPEI",
// 				"ADERF",
// 				"ASSEDIC",
				"Banques",
				"Bibliotheque",
// 				"CCAS",
// 				"Cimetieres",
				"colleges",
				"Eglises",
// 				"EPCI",
// 				"EqptPublic",
// 				"Forts_militaires",
// 				"Fourriere",
// 				"FoyerRural",
// 				"GARES_SUD",
// 				"Halte_Garderie",
				"IUT",
				"Jardins",
// 				"LCM",
// 				"LYP",
// 				"LP",
// 				"LYT",
// 				"LEGTA",
// 				"EREA",
// 				"LCL",
// 				"LCM",
// 				"LPA",
// 				"LG",
				"Mairie",
				"Maisons_Retraites",
				"Maternelles",
				"Monuments",
				"OfficeDeTourisme",
				"Pizza_Emporter",
				"Police_municipale",
// 				"Ports",
// 				"POSTES",
				"Primaire",
				"Restaurants",
// 				"SCIENCES_PO",
// 				"STADES",
// 				"Travail_Temporaire",
// 				"Tresor_Public",
	
				// PAYS PAILLON
				"Transports",
				"Santé",
				"TourismeCulture",
				"AdressesUtilesServices",
				"Sports",
				"EducationEnfance"
		);
	
	public function handleRequest() {

		parent::handleRequest();
		
		if ($handle = opendir('img/pois')) {
			$pois = "";
			while (false !== ($file = readdir($handle))) {
				$pois .= $file . ",";
			}
			echo "<input id='poiIcon' type='hidden' value='" . $pois . "' />";
		}
		
		if($_POST['method'] == "publish") {
			$publish = new Publish($this);
			$responsejSon = $publish->send();
			$responseObject = json_decode($responsejSon);
				
			if($responseObject->status != 200) {
// 				$this->setError($responseObject->description);
				$this->setError("Une erreur interne s'est produite, veuillez réessayer plus tard...");
			} else {
				$this->setSuccess("Votre message a bien été transmis aux administrateurs de l'application<br />Merci de votre contribution!");
			}
		}

		// render View
		$this->renderView("main");
	}

}
?>
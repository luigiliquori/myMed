<?

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class LocalisationController extends AuthenticatedController {

	public /*String[]*/ $filterList = array(
		"Transports" => array("Transports", "GARESSUD", "Ports"),
		"Santé" => array("Santé", "MaisonsRetraites"),
		"Tourisme culture" => array("TourismeCulture", "Eglises", "Jardins", "Officesdutourisme", "Monuments", "Fortsmilitaires"),
		"Adresses utiles" => array("AdressesUtiles", "Mairie", "Banques", "Policemunicipale", "POSTES"),
		"Sports" => array("Sports", "STADES"),
		"Restaurants" => array("Restaurants", "PizzasEmporter"),
		"Education" => array("Education", "Bibliotheques", "IUT", "colleges", "Primaires", "Maternelles"),
		"Company" => array("Company")
	);
	
/*	public function handleRequest() {

		parent::handleRequest();
		
		
		
		if($_POST['method'] == "publish") {
			$publish = new Publish($this);
			$responsejSon = $publish->send();
			$responseObject = json_decode($responsejSon);
				
			if($responseObject->status != 200) {
				$this->setError("Une erreur interne s'est produite, veuillez réessayer plus tard...");
			} else {
				$this->setSuccess("Votre message a bien été transmis aux administrateurs de l'application<br />Merci de votre contribution!");
			}
		}

		// render View
		$this->renderView("main");
	}*/
	
	function defaultMethod() {
		if ($handle = opendir('img/pois')) {
			$pois = "";
			while (false !== ($file = readdir($handle))) {
				$pois .= "," . $file;
			}
			//html input can't be inserted there in dom
			//echo "<input id='poiIcon' type='hidden' value='" . $pois . "' />";
		}
		
		// render View
		$this->renderView("localisation");
	}
	
	function create() {
		$publish = new Publish($this);
		$responsejSon = $publish->send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status != 200) {
			$this->setError("Une erreur interne s'est produite, veuillez réessayer plus tard...");
		} else {
			$this->setSuccess("Votre message a bien été transmis aux administrateurs de l'application<br />Merci de votre contribution!");
		}
		
		// render View
		$this->renderView("localisation");
		
	}

}
?>
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
<?

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends AuthenticatedController {

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
	
	public function handleRequest() {
		$this->renderView("main");
	}
	
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
		$this->renderView("main");
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
		$this->renderView("main");
		
	}

}
?>
<?php 
require_once 'system/templates/handler/IRequestHandler.php';
require_once 'lib/dasp/beans/MDataBean.class.php';
require_once 'lib/dasp/request/Publish.class.php';
require_once 'lib/dasp/request/Subscribe.class.php';
require_once 'lib/dasp/request/Find.class.php';
require_once 'lib/dasp/request/GetDetail.class.php';
require_once 'lib/dasp/request/StartInteraction.class.php';

require_once 'system/templates/application/' . APPLICATION_NAME . '/lib/Convert.class.php';
 


/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MyApplicationHandler implements IRequestHandler {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() { 
		if(isset($_POST['method'])) {
			if($_POST['method'] == "find") {
				if( (!empty($_POST['Depart']) || !empty($_POST['DepartGeo'])) && !empty($_POST['Arrivee'])) {
					if ( empty($_POST['Depart']) ) {
						//we use lon & lat couple given by DepartGeo
						$dep = explode("&", $_POST['DepartGeo']);
						$geocode1 = json_decode('{' .
							'"status": "OK",' . 
							'"results": [ { "geometry": { "location":{ "lat": '.$dep[0].',"lng": '.$dep[1].'} } } ]' . 
							'}');
					} else {
						$geocode1 = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($_POST['Depart']) . "&sensor=true"));
					}
					$geocode2 = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($_POST['Arrivee']) . "&sensor=true"));

					if($geocode1->status == "OK" && $geocode2->status == "OK"){
						
						// Memorize the Starting and the ending point
						echo '<input id="geocodeStartingPoint" type="hidden" value="' . $geocode1->results[0]->geometry->location->lat . ',' . $geocode1->results[0]->geometry->location->lng . '" />';
						echo '<input id="geocodeEndingPoint" type="hidden" value="' . $geocode2->results[0]->geometry->location->lat . ',' . $geocode2->results[0]->geometry->location->lng . '" />';
						
						// CALL TO CITYWAY API
						$args = "&mode=transit" . 
						"&depLon=" . $geocode1->results[0]->geometry->location->lng .
						"&depLat=" . $geocode1->results[0]->geometry->location->lat .
						"&depType=7" . 
						"&arrLon=" . $geocode2->results[0]->geometry->location->lng .
						"&arrLat=" . $geocode2->results[0]->geometry->location->lat .
						"&arrType=7" . 
						"&departureTime=" . 
						sprintf('%d-%02d-%02d_%02d-%02d',$_POST['select-year'],$_POST['select-month'],$_POST['select-day'],$_POST['select-hour'],$_POST['select-minute']);
						$itineraire = file_get_contents(Cityway_URL . "/tripplanner/v1/detailedtrip/json?key=" . Cityway_APP_ID . $args);
						$itineraireObj = json_decode($itineraire);
						
						if(isset($itineraireObj->ItineraryObj->tripSegments)) {
							
							$this->success->itineraire->type = "Cityway";
							$this->success->itineraire->value = $itineraire;//$itineraireObj->ItineraryObj->tripSegments->tripSegment;
							
							// Construct the default POIs
							$j = 0;
							foreach($itineraireObj->ItineraryObj->tripSegments->tripSegment as $tripSegment) {
								$args = "&mode=transit" . 
								"&lon=" . $tripSegment->departurePoint->longitude .
								"&lat=" . $tripSegment->departurePoint->latitude .
								"&distance=150" . 
								"&TypePoint=-1" .
								"&POICategory=-1";
								$pois = json_decode(file_get_contents(Cityway_URL . "/display/v1/GetTripPointByWGS84/json?key=" . Cityway_APP_ID . $args));

								if($pois->TripPointServiceObj->Status->code == "0") {
									$i = 0;
									$tripSegment->poi = array();
									foreach($pois->TripPointServiceObj->TripPoint as $poi) {
										if(is_object($poi)){
											// Convert From Lambert2 to WGS64
											$convertion = new Convert($poi->x, $poi->y);
											$newCoord = $convertion->convertion();
											$poi->longitude = $newCoord[0]; //X
											$poi->latitude = $newCoord[1];  //Y
											$tripSegment->poi[$i++] = $poi;
										}
									}
									echo '<input id="poi_'.$j++.'" type="hidden" value='. urlencode(json_encode($tripSegment->poi)) .' />';
								}
							}
							
						} else {
							
							$this->success->itineraire->type = "Google";
							$this->success->itineraire->value = "";
						}
					} else {
						$this->error = "2"; $this->success->itineraire->type = "Erreur geocoding";
					}
				} else {
					$this->error = "3"; $this->success->itineraire->type = "Départ et/ou arrivée non valide";
				}
			} 
		}
	}
	
	/* --------------------------------------------------------- */
	/* Getter&Setter */
	/* --------------------------------------------------------- */
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*void*/ function setError(/*String*/ $error){
		return $this->error = $error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
	public /*void*/ function setSuccess(/*String*/ $success){
		return $this->success = $success;
	}
	
}
?>
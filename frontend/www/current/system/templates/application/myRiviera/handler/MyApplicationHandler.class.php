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
				if(isset($_POST['Départ']) && isset($_POST['Arrivée'])) {
					
					// CALL TO GOOGLE GEOCODE API
					$geocode1 = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($_POST['Départ']) . "&sensor=true"));
					$geocode2 = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($_POST['Arrivée']) . "&sensor=true"));

					// CALL TO CITYWAY API
					if($geocode1->status == "OK" && $geocode2->status == "OK"){
						
						// FORMAT date for cityway
						$tmp = preg_split("/[^0-9]+/", $_POST['date']);
												
						$args = "&mode=transit" . 
						"&depLon=" . $geocode1->results[0]->geometry->location->lng .
						"&depLat=" . $geocode1->results[0]->geometry->location->lat .
						"&depType=7" . 
						"&arrLon=" . $geocode2->results[0]->geometry->location->lng .
						"&arrLat=" . $geocode2->results[0]->geometry->location->lat .
						"&arrType=7" . 
						"&departureTime=" . $tmp[4]."-".$tmp[3]."-".$tmp[2]."_".$tmp[0]."-".$tmp[1];

						$itineraire = file_get_contents(Cityway_URL . "/tripplanner/v1/detailedtrip/json?key=" . Cityway_APP_ID . $args);
						$itineraireObj = json_decode($itineraire);
						
						if(isset($itineraireObj->ItineraryObj)) {
							
							$this->success->itineraire = $itineraireObj;
							$this->success->kml = Cityway_URL . "/tripplanner/v1/detailedtrip/kml?key=" . Cityway_APP_ID . $args;
							
							// Construct the default POIs
							foreach($itineraireObj->ItineraryObj->tripSegments->tripSegment as $tripSegment) {
								
								$args = "&mode=transit" . 
								"&lon=" . $tripSegment->departurePoint->longitude .
								"&lat=" . $tripSegment->departurePoint->latitude .
								"&distance=150" . 
								"&TypePoint=-1" .
								"&POICategory=-1";
								$pois = json_decode(file_get_contents(Cityway_URL . "/display/v1/GetTripPointByWGS84/json?key=" . Cityway_APP_ID . $args));

								if($pois->TripPointServiceObj->Status->code == 0) {
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
								}
							}
							
						} else {
							$this->error = "error with cityWay";
							echo '<script type="text/javascript">alert(\'' . $itineraire . '\');</script>';
						}
					} else {
						$this->error = "error with google geocode";
					}
				} else {
					$this->error = "bad parameters";
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
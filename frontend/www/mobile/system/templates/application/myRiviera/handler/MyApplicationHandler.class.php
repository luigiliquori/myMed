<?php 
require_once 'system/handler/IRequestHandler.php';
require_once 'system/beans/MDataBean.class.php';
require_once 'system/request/Publish.class.php';
require_once 'system/request/Subscribe.class.php';
require_once 'system/request/Find.class.php';
require_once 'system/request/GetDetail.class.php';
require_once 'system/request/StartInteraction.class.php';

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
					if($geocode1->status == "OK" && $geocode2->status == "OK"){
						// CALL TO CITYWAY API
						$itineraire = file_get_contents(
						Cityway_URL . "/tripplanner/v1/detailedtrip/json?key=" . Cityway_APP_ID . 
						"&mode=transit" . 
						"&depLon=" . $geocode1->results[0]->geometry->location->lng .
						"&depLat=" . $geocode1->results[0]->geometry->location->lat .
						"&depType=7" . 
						"&arrLon=" . $geocode2->results[0]->geometry->location->lng .
						"&arrLat=" . $geocode2->results[0]->geometry->location->lat .
						"&arrType=7" . 
						"&departureTime=" . $_POST['date'] . "_12-00"
						);
						
						$itineraireObj = json_decode($itineraire);
						if(isset($itineraireObj->ItineraryObj)) {
// 							echo '<script type="text/javascript">alert(\'' . $itineraire . '\');</script>';
							$this->success = $itineraireObj;
						} else {
							$this->error = "error with cityWay";
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
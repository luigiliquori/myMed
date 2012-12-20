<?

/**
 * Add multiple partnership from a json input
 */
class DBController extends AuthenticatedController {

	public function handleRequest() {

		parent::handleRequest();

		if(isset($_POST['method']) && $_POST['method'] == "addPartnership"){

			$partnerships = json_decode($_POST['data']);

			foreach($partnerships as $partnershipData){
				
				$partnership = new Partnership();
				$this->fillObj($partnership, $partnershipData);
// 				try {
					$partnership->publish();
					$this->success = "Published !";
// 				} catch (Exception $e) {
// 					$this->error = "error on publication !";
// 				}
	
			}
		}

		// render View
		$this->renderView("main");
	}
	
	/**
	 * Fill object with POST values
	 * @param unknown $obj
	 */
	private function fillObj($partnership, $partnershipData) {
	
		// theme
		$partnership->theme = $partnershipData->theme;
		
		// program
		$partnership->other = $partnershipData->programme;

		// title
		$partnership->title = $partnershipData->titre;
		
		// partnership content
		$partnership->text 	= 
		$partnershipData->descriptif .
		"<br />" .
		"<strong>Acteur principal:</strong>" . $partnershipData->leader . "<br />" .
		"<strong>Partenaires:</strong>" . $partnershipData->partenaires_france . "<br />" .
		"<strong>contact:</strong>" . $partnershipData->contact . "<br />" .
		"<strong>mail:</strong>" . $partnershipData->mail . "<br />" .
		"<strong>tel:</strong>" . $partnershipData->tel . "<br />" .
		"<strong>adresse:</strong>" . $partnershipData->adresse . "<br />";
		
	}
	
}

?>

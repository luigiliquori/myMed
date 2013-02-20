<?php 

class VolunteerController extends AuthenticatedController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		parent::handleRequest();
		
		switch ($_GET['method']){
			case 'show_all_volunteers':
				$this->search_all_volunteers();
				break;
		}
	}
	
	private function search_all_volunteers(){
		$volunteer = $this->getProfiles('volunteer');
		if($volunteer!=null)
			$this->result = $volunteer->results;
		else $this->result = array();
		
		$this->renderView("AllVolunteers");
	}
	
	function getProfiles($type) {
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":profiles:".$type, "predicates"=>array()));
		try{
			$res = $find->send();
			return $res;
		}
		catch(Exception $e){}
		return null;
	}
}

?>

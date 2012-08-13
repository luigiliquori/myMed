<? 
class MainController extends GuestOrUserController {
	public function defaultMethod() {
		
		// Switch on profile type
		if ($this->extendedProfile == null) {
			// Guest => show list of annonces
			$this->forwardTo("listAnnonces");	
		} else if ($this->extendedProfile instanceof ProfileNiceBenevolat) {
			$this->renderView("mainNiceBenevolat");
		} elseif($this->extendedProfile instanceof ProfileBenevole) {
			$this->forwardTo("listAnnonces");
		} elseif ($this->extendedProfile instanceof ProfileAssociation) {
			$this->forwardTo("listAnnonces");
		} else {
			throw new InternalError("Should not happen");
		}
	}	
}

?>
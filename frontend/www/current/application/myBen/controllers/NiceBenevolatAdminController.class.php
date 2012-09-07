<? 

/** 
 * Conctroller with actions that should be called duiretly 
 * (by entering URL manually in the navigator),
 * To init NiceBenevolat account :
 * http://<host>:<port>/application/myBen/index.php?action=NiceBenevolatAdmin&method=init
 */
class NiceBenevolatAdminController extends ProfileNiceBenevolatRequired {
	
	public function init() {
		
		// Subscribe to creation of new associations
		$req = new ProfileAssociation();
		$req->valid = "false";
		$req->subscribe();
		
		// Subscribe to creation of new benevoles
		// XXX Hack : Subscribe to both males and females to subscribe to all
		$req = new ProfileBenevole();
		$req->missions = array_keys(CategoriesMissions::$values);
		
		
		// Subscribe to all annonces
		//$req = new Annonce();
		//$req->promue = "false";
		//$req->subscribe();
		
		// Success message
		$this->success = _("Compte NiceBenevolat correctement activé");
		$this->forwardTo("main");
		
	}	
}

?>
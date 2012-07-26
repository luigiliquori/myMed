<?
class AnnonceController extends ExtendedProfileRequired {
		
	/** @var Annonce */
	public $annonce;

	// ----------------------------------------------------------------------
	// Helpers
	// ----------------------------------------------------------------------
	
	/** Fetch annonce from provided id */
	function fetchAnnonce() {
		
		// Id of the annonce
		$id = $_REQUEST['id'];
		
		// Find it
		$request = new Annonce();
		$request->id = $id;
		$res = $request->find();
		
		// Should have only one result
		if (sizeof($res) != 1) {
			throw new InternalError("Expected one result for Annonce(id=$id). Got " . sizeof($res));
		}
		
		// Take first one
		$this->annonce = $res[0];
		
	}
	
	function hasWriteAccess() {
		// The owner of the annonce should be the current user or NiceBenevolat
		return (
				$this->annonce->associationID == $this->extendedProfile->userID ||
				$this->extendedProfile instanceof ProfileNiceBenevolat);
	}
	
	/**
	 *  Check if current user has write credentials on this annonce.
	 *  Throw error if not.
	 */
	function checkWriteAccess() {
		if (!$this->hasWriteAccess()) throw new UserError("Vous n'avez pas les droits d'écriture sur cete annonce");
	}
	
	// ----------------------------------------------------------------------
	// Action handlers
	// ----------------------------------------------------------------------
	
	/** Creation of a new announce => show the form */
	function create()  {
		
		// TODO Check if association is trusted
		ProfileAssociationRequired::check();
		
		// Dummy empty annonce
		$this->annonce = new Annonce();
		$this->annonce->competences = $this->extendedProfile->competences;
		$this->annonce->begin = date(DATE_FORMAT);
		$this->renderView("createAnnonce");
	}
	
	/** Creation of a new "annonce" => form has been submitted */
	function doCreate()  {
		
		// TODO Check if association is trusted
		ProfileAssociationRequired::check();
		
		$this->annonce = new Annonce();
		$this->annonce->id = uniqid();
		
		// Set the associationID
		$this->annonce->associationID = $this->extendedProfile->userID;
		
		// Populate attributes 
		$this->annonce->populateFromRequest(array("associationID", "id","promue"));
		
		// Publish this announce	
		$this->annonce->publish();
		
		// Succes message
		$this->setSuccess("Votre annonce a bien été publiée");
		
		// Show a list of annonces
		$this->redirectTo("listAnnonces");

	}
	
	/** Delete one annonce */
	function delete()  {
	
		// TODO Check if association is trusted
		ProfileAssociationRequired::check();
	
		// Get the annonce
		$this->fetchAnnonce();
		
		// Check write access
		$this->checkWriteAccess();
		
		// Delete it
		$this->annonce->delete();
			
		// Fill success
		$this->setSuccess("Annonce supprimée");
		
		// Redirect to list
		$this->redirectTo("listAnnonces");
	
	}

	
	/** Details of one announce */
	function details()  {
	
		// Id of the annonce
		$id = $_REQUEST['id'];
		
		// Find it
		$request = new Annonce();
		$request->id = $id;
		$res = $request->find();
	
		// Should have only one result
		if (sizeof($res) != 1) {
			throw new InternalError("Expected one result for Annonce (id=$id). Got " . sizeof($res));
		}
	
		$this->annonce = $res[0];
	
		// Redirect to list
		$this->renderView("detailsAnnonce");
	}
	
	/** Edit one announce (show form) */
	function edit()  {
			
		// TODO Check if association is trusted
		ProfileAssociationRequired::check();
	
		// Get annonce
		$this->fetchAnnonce();
		
		// We should have write access to this annonce
		$this->checkWriteAccess();
	
		// Redirect to the annonce
		$this->renderView("editAnnonce");
	
	}
	
	/** Details of one announce */
	function doEdit()  {
		
		// TODO Check if association is trusted
		ProfileAssociationRequired::check();
	
		// Get the annonce
		$this->fetchAnnonce();
		
		// We should have write access to this annonce
		$this->checkWriteAccess();
		
		// Delete it
		$this->annonce->delete();
	
		// Populate the fields
		$this->annonce->populateFromRequest(array("associationID", "id", "promue"));
		
		// Publish it
		$this->annonce->publish();
		
		// Success
		$this->setSuccess("Annonce mise à jour");
		
		// Redirect to the details
		$this->details();
	}
	
	/** Promote the annonce */
	function promote()  {
	
		// TODO Check if association is trusted
		ProfileAssociationRequired::check();
	
		// Get the annonce
		$this->fetchAnnonce();
	
		// We should have write access to this annonce
		$this->checkWriteAccess();
	
		// Delete it
		$this->annonce->delete();
	
		// Populate the fields
		$this->annonce->promue = true;
	
		// Publish it
		$this->annonce->publish();
	
		// Success
		$this->setSuccess("Annonce mise à jour");
	
		// Redirect to the details
		$this->details();
	}



}
?>
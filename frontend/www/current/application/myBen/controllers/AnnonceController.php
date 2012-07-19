<?
class AnnonceController extends ExtendedProfileRequired {
		
	/** @var Annonce */
	public $annonce;


	/** Creation of a new announce => show the form */
	function create()  {
		// Dummy empty annonce
		$this->annonce = new Annonce();
		$this->annonce->competences = $this->extendedProfile->competences;
		$this->renderView("createAnnonce");
	}
	
	/** Creation of a new "annonce" => form has been submitted */
	function doCreate()  {
		
		$this->annonce = new Annonce();
		$this->annonce->id = uniqid();
		
		// Set the associationID
		$this->annonce->associationID = $this->extendedProfile->userID;
		
		// Populate attributes 
		$this->annonce->populateFromRequest(array("associationID", "id"));
		
		// Publish this announce	
		$this->annonce->publish();
		
		// Succes message
		$this->setSuccess("Votre annonce a bien été crée");
		
		// Show a list of annonces
		$this->redirect("listAnnonces");

	}
	
	/** Delete one annonce */
	function delete()  {
	
		// Id of the annonce
		$id = $_REQUEST['id'];

		// Find it
		$request = new Annonce();
		$request->id = $id;
		$res = $request->find();
		
		// Should have only one result
		if (sizeof($res) != 1) {
			throw new Exception("Excepted one result for Annonce(id=$id). Got " . sizeof($res));
		}
		
		// Take first one
		$res = $res[0];
		
		// Delete it
		$res->delete();
			
		// Fill success
		$this->setSuccess("Annonce supprimée");
		
		// Redirect to list
		$this->redirect("listAnnonces");
	
	}
	
	/** Delete one annonce */
	function details()  {
	
		// Id of the annonce
		$id = $_REQUEST['id'];
	
		debug("Hello");
		
		// Find it
		$request = new Annonce();
		$request->id = $id;
		$res = $request->find();
	
		// Should have only one result
		if (sizeof($res) != 1) {
			throw new Exception("Expected one result for Annonce (id=$id). Got " . sizeof($res));
		}
	
		$this->annonce = $res[0];
	
		// Redirect to list
		$this->renderView("detailsAnnonce");
	
	}


}
?>
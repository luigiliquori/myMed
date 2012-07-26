<?
class CandidatureController extends ExtendedProfileRequired {
		
	/** @var Candidature */
	public $candidature;

	/** Creation of a new announce => show the form */
	function create()  {
		
		// Only benevoles can postulate
		ProfileBenevoleRequired::check();
		
		// Get the annonce
		$annonceID = $_REQUEST['annonceID'];
		
		// Create empty candidature
		$this->candidature = new Candidature();
		$this->candidature->annonceID = $annonceID;
		
		$this->renderView("createCandidature");
	
	}
	
	/** Creation of a new "annonce" => form has been submitted */
	function doCreate()  {
		
		// Only benevoles can postulate
		ProfileBenevoleRequired::check();
		$this->candidature = new Candidature();
		$this->candidature->id = uniqid();
		$this->candidature->begin = date(DATE_FORMAT);
		
		// Populate all from request except "id" 
		$this->candidature->populateFromRequest(array("id"));
		
		$this->candidature->publish();
		
		// Confirmation
		$this->setSuccess("Votre candidature a été enregistrée");
		
		// Go back to the announce
		$this->forwardTo("annonce:details", array("id" => $this->candidature->annonceID));
		

	}
	
	/** Details of one candidature */
	function view()  {
	
		ProfileAssociationRequired::check();
		
		// Id of the annonce
		$id = $_REQUEST['id'];
		
		// Find it
		$request = new Candidature();
		$request->id = $id;
		$res = $request->find();
	
		// Should have only one result
		if (sizeof($res) != 1) {
			throw new InternalError("Expected one result for Annonce (id=$id). Got " . sizeof($res));
		}
	
		$this->candidature = $res[0];
	
		// Show details
		$this->renderView("detailsCandidature");
	
	}
	
	


}
?>
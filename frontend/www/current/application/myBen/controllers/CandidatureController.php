<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<?
class CandidatureController extends GuestOrUserController {
		
	/** @var Candidature */
	public $candidature;

	/** Creation of a new announce => show the form */
	function create()  {
		
		// Only benevoles can postulate
		ProfileBenevoleRequired::check();
		
		// Get the annonce
		$annonceID = $_REQUEST['annonceID'];
		$req = new Annonce();
		$req->id = $annonceID;
		$res = $req->find();
		$annonce = $res[0];
		
		// Check if annonce is passed
		if ($annonce->isPassed()) {
			$this->setError("Annonce passée : vous ne pouvez pas candidater");
			$this->forwardTo("Annonce:details", array("id" => $annonceID));
		}
		if (is_true($annonce->promue)) {
			$this->setError("Annonce promue : vous ne pouvez pas candidater");
			$this->forwardTo("Annonce:details", array("id" => $annonceID));
		}
		
		// Search for other responses for same offer/guy
		$req = new Candidature();
		$req->annonceID = $annonceID;
		$res = $req->find();
		foreach($res as $candidature) {
			if ($candidature->publisherID == $this->user->id) {
				$this->setError("Vous avez déjà répondu à cette annonce");
				$this->forwardTo("Annonce:details", array("id" => $annonceID));
			}
		}
		
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
		$this->setSuccess(_("Votre candidature a été enregistrée"));
		
		// Go back to the announce
		$this->forwardTo("annonce:details", array("id" => $this->candidature->annonceID));
		

	}
	
	/** Details of one candidature */
	function view()  {
	
		// Only associations can view that
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
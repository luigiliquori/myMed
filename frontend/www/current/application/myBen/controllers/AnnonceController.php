<?php
/*
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
 */
?>
<?
class AnnonceController extends GuestOrUserController {
		
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
		if (!$this->hasWriteAccess()) throw new UserError(_("Vous n'avez pas les droits d'écriture sur cette annonce"));
	}
	
	/** Populate from request */
	function populateAnnonce() {
		
		// Set the associationID
		if ($this->extendedProfile instanceof ProfileNiceBenevolat) {
				
			// For NiceBenevolat, we chose from a list of associations
			$this->annonce->associationID = $_REQUEST['associationID'];
				
		} else {
				
			// Otherwise the assocation is the publisher
			$this->annonce->associationID = $this->extendedProfile->userID;
		}
		
		// Populate attributes
		$this->annonce->populateFromRequest(array("associationID", "id","promue"));
		
	}
	
	// ----------------------------------------------------------------------
	// Action handlers
	// ----------------------------------------------------------------------
	
	/** Common request handler, called before custom methods */
	function handleRequest() {
		parent::handleRequest();
		
		// We need a token
		$this->getToken();
		
		// For NiceBenevolat, we need all the associations in a list assoId => assoName
		if ($this->extendedProfile instanceof ProfileNiceBenevolat) {
				
			// Fetch all valid associations
			$req = new ProfileAssociation();
			$req->valid = "true";
				
			$this->associations = array();
			$assos = $req->find();
			foreach ($assos as $asso) {
				$this->associations[$asso->userID] = $asso->name;
			}

			// Add Nice Bénévolat
			$this->associations[ProfileNiceBenevolat::$USERID] = _("Nice Bénévolat");

			// Sort them 
			asort($this->associations, SORT_STRING);
			
			// Add empty option
			$this->associations =array_merge(
					array("" => _("-- Choisir --")),
					$this->associations);
		}
	} 
	
	/** Creation of a new announce => show the form */
	function create()  {
		
		// Require a validated association 
		ProfileAssociationValidRequired::check();
		
		// Dummy empty annonce
		$this->annonce = new Annonce();
		$this->annonce->competences = $this->extendedProfile->competences;
		$this->annonce->begin = date(DATE_FORMAT);
		$this->renderView("createAnnonce");
		
	}
	
	/** Creation of a new "annonce" => form has been submitted */
	function doCreate()  {
		
		// Require a validated association 
		ProfileAssociationValidRequired::check();
		
		// Create a new annonce with uniq id
		$this->annonce = new Annonce();
		$this->annonce->id = uniqid();
		
		// Populate from request
		$this->populateAnnonce();
		
		// Publish this announce	
		$this->annonce->publish();
		
		// Make nice benevolat be notified of future canditatures
		$req = new Candidature();
		$req->annonceID = $this->annonce->id;
		$req->subscribe(ProfileNiceBenevolat::$USERID);
		
		// Succes message
		$this->setSuccess(_("Votre annonce a bien été publiée"));
		
		// Show the new annonce
		$this->forwardTo("annonce:details", array("id" => $this->annonce->id));

	}
	
	/** Delete one annonce */
	function delete()  {
	
		// Require a validated association 
		ProfileAssociationValidRequired::check();
	
		// Get the annonce
		$this->fetchAnnonce();
		
		// Check write access
		$this->checkWriteAccess();
		
		// Delete it
		$this->annonce->delete();
			
		// Fill success
		$this->setSuccess(_("Annonce supprimée"));
		
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
			
		// Require a validated association 
		ProfileAssociationValidRequired::check();
	
		// Get annonce
		$this->fetchAnnonce();
		
		// We should have write access to this annonce
		$this->checkWriteAccess();
	
		// Redirect to the annonce
		$this->renderView("editAnnonce");
	
	}
	
	/** Details of one announce */
	function doEdit()  {
		
		// Require a validated association 
		ProfileAssociationValidRequired::check();
	
		// Get the annonce
		$this->fetchAnnonce();
		
		// We should have write access to this annonce
		$this->checkWriteAccess();
		
		// Delete old version
		$this->annonce->delete();
	
		// Populate from request
		$this->populateAnnonce();
		
		// Publish new one
		$this->annonce->publish();
		
		// Success
		$this->setSuccess(_("Annonce mise à jour"));
		
		// Redirect to the details
		$this->details();
	}
	
	/** Promote the annonce */
	function promote()  {
	
		// Require a validated association 
		ProfileAssociationValidRequired::check();
	
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
		$this->setSuccess(_("Annonce mise à jour"));
	
		// Redirect to the details
		$this->details();
	}



}
?>
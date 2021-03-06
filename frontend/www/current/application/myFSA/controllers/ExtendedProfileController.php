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
<?php

/**
 *This class extends the default myMed user profile, common to every applications, with a profile specific
 * for this application.
 * This extended profile will be stored as a Publication in the database.
 */

class ExtendedProfileController extends AbstractController
{
	/**
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest(){
		if(!isset($_SESSION['ExtendedProfile']))
			$this->fetchExtendedProfile();
		
		if(isset($_POST['lang'])){ // UPDATE LANG
			$_POST['id'] = $_SESSION['user']->id;
			$_POST['firstName'] = $_SESSION['user']->firstName;
			$_POST['lastName'] = $_SESSION['user']->lastName;
			$_POST['email'] = $_SESSION['user']->email;
			$_POST['birthday'] = $_SESSION['user']->birthday;
			$_POST['profilePicture'] = $_SESSION['user']->profilePicture;
				
			$request = new Requestv2("v2/ProfileRequestHandler", UPDATE , array("user"=>json_encode($_POST)));
			
			$responsejSon = $request->send();
			$responseObject2 = json_decode($responsejSon);
				
			if($responseObject2->status != 200) {
				$this->error = $responseObject2->description;
			} else{
					
				$_SESSION['user'] = (object) array_merge( (array) $_SESSION['user'], $_POST);
			}
			$debugtxt  =  "<pre>LANGUUUUUUAGE";
			$debugtxt  .= var_export($_POST['lang'], TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			$this->redirectTo("?action=ExtendedProfile");
		}
		if (isset($_POST["profileFilled"]))
			$this->storeProfile();
		else if (isset($_SESSION['ExtendedProfile']) AND !empty($_SESSION['ExtendedProfile']))
			$this->showProfile();
		else
			$this->renderView("ExtendedProfileForm");
		
		$this->renderView("ExtendedProfileForm");
		
	}	
	
	public /*void*/ function storeProfile(){
		$_SESSION["profileFilled"] = $_POST["profileFilled"];
		
		if ($_POST["profileFilled"] == "company") {
			if(empty($_POST["ctype"])){
				$this->error = _("Company type field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["cname"])){
				$this->error = _("Company name field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["caddress"])){
				$this->error = _("Company Address field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["cnumber"])){
				$this->error = _("SIRET field can't be empty");
				debug_r($_SESSION['profileFilled']);
				$this->renderView("ExtendedProfileForm");
		    }else{
				$object = array(
						"type" => $_POST["ctype"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["cnumber"]
				);
		    }
		}else if ($_POST["profileFilled"]== "employer") {
			if(empty($_POST["euniv"])){
				$this->error = _("University field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["enumber"])){
				$this->error = _("Student number field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else{
				$object = array(
						"type" => $_POST["ecampus"],
						"name" => $_POST["euniv"],
						"address" => $_POST["estudies"],
						"number" => $_POST["enumber"]
				);
			}
		
		}else if ($_POST["profileFilled"] == "guest") {
			$object = "guest";
		}
	
		$extendedProfile = new ExtendedProfile($_SESSION['user'], $object);
		$extendedProfile->storeProfile($this);
		
		if (!empty($this->error))
			$this->renderView("ExtendedProfileForm");
		else {
			$_SESSION['ExtendedProfile'] = $extendedProfile;
			$this->success = _("Registration completed!");
			
			$this->renderView("ExtendedProfileDisplay");
		}
			
	}
	
	public /*void*/ function fetchExtendedProfile(){
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
	
		if (!empty($result)){
			$_SESSION['ExtendedProfile'] = $result;
		}
	
	
	}
	
	public /*void*/ function getExtendedProfile(){
		ExtendedProfile::getExtendedProfile($_SESSION['user']);
	}
	
	public /*void*/ function showProfile(){
		$this->renderView("ExtendedProfileDisplay");
	}

}
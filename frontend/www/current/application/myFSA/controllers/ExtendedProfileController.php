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
		
		if(isset($_POST['lang'])){
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
		
		if ($_POST["profileFilled"] == "company") {
			if(empty($_POST["ctype"])){
				//$this->error = _("Company type field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["cname"])){
				//$this->error = _("Company name field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["caddress"])){
				//$this->error = _("Company Address field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["cnumber"])){
				//$this->error = _("SIRET field can't be empty");
				$this->renderView("ExtendedProfileForm");
			//}
			//if(!empty($this->error) && $fromUpdate==false){
		    //	$this->renderView("ExtendedProfileForm");
		    }else{
				$object = array(
						"type" => $_POST["ctype"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["cnumber"]
				);
		    }

		}	
		else if ($_POST["profileFilled"]== "employer") {
			if(empty($_POST["cname"])){
				//$this->error = _("University field can't be empty");
				$this->renderView("ExtendedProfileForm");
			}else if(empty($_POST["tnumber"])){
				//$this->error = _("Student number field can't be empty");
				$this->renderView("ExtendedProfileForm");
			//}
			//if(!empty($this->error) && $fromUpdate==false){
			//	$this->renderView("ExtendedProfileForm", "#employer");
			}else{
				$object = array(
						"type" => $_POST["occupation"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["tnumber"]
				);
			}
		
		}
		else if ($_POST["profileFilled"] == "guest") {
			$object = "guest";
		}

		$_SESSION["profileFilled"] = $_POST["profileFilled"];
		$extendedProfile = new ExtendedProfile($_SESSION['user'], $object);
		
		$extendedProfile->storeProfile($this);
		if (!empty($this->error))
			$this->renderView("ExtendedProfileForm");
		else {
			$this->success = "Registration completed!";
			$this->redirectTo("main");
		}
			
	}
	
	public /*void*/ function getExtendedProfile(){
		
		ExtendedProfile::getExtendedProfile($_SESSION['user']);
	}
	
	public /*void*/ function showProfile(){

		
			$this->renderView("ExtendedProfileDisplay");

	}

}
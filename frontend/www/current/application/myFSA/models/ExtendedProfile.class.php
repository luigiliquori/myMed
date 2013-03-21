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
//require_once '../../../lib/dasp/request/PublishRequest.class.php';
//require_once '../../../lib/dasp/beans/DataBean.php';
//require_once '../../../lib/dasp/beans/OntologyBean.php';

class ExtendedProfile
{

	/*
	 * Attributes
	 */

	/**
	 * Id of the user
	 */
	public /*String*/ $user;
	
	/**
	 * Array object keeps informations about extended profile
	 *
	 */
	public /*Array*/ $object;
	
	
	
	public function __construct(/*String*/ $user, /*Array*/$object){
		
		// Check if user is defined
		if (empty($user))
			throw new Exception("User ID not defined.");
		else 
			$this->user = $user;
		
		// check the doctor
		if ( empty($object))
			throw new Exception("Object can not be empty");
		else		
			$this->object = $object;
		
	}
	
	

	
	/**
	 * The extended Profile is stored as a publication in the database.
	 * The predicate is composed of the name of the application, the user_ID + "extendedProfile"
	 */
	public /*void*/ function storeProfile(IRequestHandler $handler){
		
		/*
		 * Build the predicates Array
		*/
		$predicates[] = new OntologyBean("role", "ExtendedProfile", KEYWORD);
		
		/*
		 * Build the datas Array using keyword depended from the storing object
		*/
		if($_SESSION["profileFilled"] == "company"){			
			$datas[] = new OntologyBean("company", json_encode($this->object));
		}
		else if($_SESSION["profileFilled"] == "employer"){
			
			$datas[] = new OntologyBean("employer", json_encode($this->object));
		}
		else if($_SESSION["profileFilled"] == "guest"){
			
			$datas[] = new OntologyBean("guest", json_encode($this->object));
		}
		// Build the DataBean
		$dataBean = new DataBean($predicates, $datas);
		
		// Build the request
		$publish = new PublishRequest($handler, $dataBean);
		$publish->send();
		
	}
	
	
	/**
	 * This function is retrieving extended profile from the database
	 */
	public static /* List of ontologies */ function getExtendedProfile(IRequestHandler $handler, $user){
		
		$predicate = "roleExtendedProfile";
		
		$find = new FindRequest($handler, $predicate, $user);
		try{
			$result = $find->send();
		}
		catch(Exception $e){
			// Typical Exception raised : No publication found
			// which means the user don't have a ExtendedProfile yet
			return null;
		}
		$debugtxt  =  "<pre>PROOOOFILE FILLLED!!!!!!!!!!!!";
		$debugtxt  .= var_export($result, TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);

		$object = "";
			
		foreach ($result as $line){
			switch($line->key){
					
				case "company" :
					$_SESSION["profileFilled"] = "company";
					$object = json_decode($line->value, TRUE);
					break;
				case "employer" :
					$_SESSION["profileFilled"] = "employer";
					$object = json_decode($line->value, TRUE);
					break;
				case "guest" :
					$_SESSION["profileFilled"] = "guest";
					$object = json_decode($line->value, TRUE);
					break;
			}
		
		}
			
		return new ExtendedProfile($user, $object);
		
	}
	
	
	
	
}
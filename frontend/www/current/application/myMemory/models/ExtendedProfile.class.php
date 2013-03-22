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


// Constant for disease level
define('LOW'		, 1);
define('MODERATE'	, 2);
define('ADVANCED'	, 3);

/**
 *	This class extends the default myMed user profile, common to every applications, with a profile specific
 * for this application. Store anything you need.
 * Choices have been made that this extended profile will be stored as a Publication in the database.
 * Although, you can change it the way you want in your own application, as long as you use the column families
 * available in cassandra. 
 * 
 * @see ExtendedProfileController
 * @author David Da Silva
 * 
 */
class ExtendedProfile
{

	/*
	 * Attributes
	 */

	/**
	 * The ID of the myMed user to whom this ExtendedProfile is linked.
	 */
	public /*String*/ $user;
	
	
	/**
	 * 
	 * The user home adress. (house)
	 * @var String
	 */
	public /*String*/ $home;
	
	/**
	 * Alzheimer level of the user.
	 * LOW means occasional loss of memory, ADVANCED means that the user
	 * must be under surveillance at any time. 
	 * @var enum
	 */
	public /*enum*/ $diseaseLevel;
	
	/**
	 * Informations about the natural caregiver. Often a relative.
	 */
	public /*Contact*/ $careGiver;
	
	/**
	 * Informations about the attending doctor.
	 */
	public /*Contact*/ $doctor;
	
	/**
	 * Calling list of people to call/message in case of crisis.
	 * From 1 to 4, no duplicates, 1 means "called first" and 4 "called last".
	 * Array of Contact Objects.
	 */
	public /*Array<Contact>*/ $callingList;
	
	/**
	 * Calling list to be used only in the Auto-Call function.
	 * This can be different than the emergency callingList.
	 * Array of phone numbers
	 */
	public /*Array<String>*/ $callingListAutoCall;
	
	
	public /*float*/ $perimeter_home;
	public /*float*/ $perimeter_nearby;
	public /*float*/ $perimeter_far;
	
	public /*int*/   $autocall_frequency;
	
	public function __construct(/*String*/ $user,/*String*/ $home ,/*enum*/ $diseaseLevel,
	/*Contact*/ $careGiver, /*Contact*/$doctor,/*Array<Contact>*/ $callingList,
			 /*Array<String>*/ $callingListAutoCall, /*float*/ $perimeter_home=100,
			/*float*/ $perimeter_nearby=200, /*float*/ $perimeter_far=1000, $autocall_frequency=30){
		
		// Check if user is defined
		if (empty($user))
			throw new Exception("User ID not defined.");
		else 
			$this->user = $user;
		
		//check the address
		if (empty($home))
			throw new Exception("Address must not be empty");
		else
			$this->home = $home;
		
		// check the diseaseLevel
		if ($diseaseLevel < 1 OR $diseaseLevel > 3)
			throw new Exception("diseaseLevel should be an enum between 1 and 3");
		else 
			$this->diseaseLevel = $diseaseLevel;
		
		// check the careGiver
		if (empty($careGiver))
			throw new Exception("careGiver must not be empty");
		else
			$this->careGiver = $careGiver;
		
		
		// check the doctor
		if ( empty($doctor) )
			throw new Exception("Doctor must not be empty");
		else		
			$this->doctor = $doctor;
		
		
		// check the callingList
		if ( empty($callingList) )
			throw new Exception("Calling list must not be empty");
		else
			$this->callingList = $callingList;
		
		// Check the autocall parameters. Only if diseaseLevel = 3
		if($diseaseLevel == 3){

			// Check the calling list
			if (empty($callingListAutoCall))
				throw new Exception("Calling list for autocall must not be empty");
			else {
				if (sizeof($callingListAutoCall) != 3)
					throw new Exception("Calling list for autocall must have 3 phones number");
				else
					$this->callingListAutoCall = $callingListAutoCall;
			}
			// Others parameters are numbers only, with default values
			$this->perimeter_home = $perimeter_home;
			$this->perimeter_nearby = $perimeter_nearby;
			$this->perimeter_far = $perimeter_far;
			$this->autocall_frequency = $autocall_frequency;
		}
		
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
		 * Buid the datas Array
		 */
		$datas[] = new OntologyBean("home", json_encode($this->home));
		$datas[] = new OntologyBean("diseaseLevel", $this->diseaseLevel); // TODO : Ontology_ID = ENUM?
		$datas[] = new OntologyBean("careGiver", json_encode($this->careGiver));
		$datas[] = new OntologyBean("doctor", json_encode($this->doctor));
		$datas[] = new OntologyBean("callingList", json_encode($this->callingList));
		$datas[] = new OntologyBean("callingListAutoCall", json_encode($this->callingListAutoCall));
		$datas[] = new OntologyBean("perimeter_home", $this->perimeter_home);
		$datas[] = new OntologyBean("perimeter_nearby", $this->perimeter_nearby);
		$datas[] = new OntologyBean("perimeter_far", $this->perimeter_far);
		$datas[] = new OntologyBean("autocall_frequency", $this->autocall_frequency);
		
		// Build the DataBean
		$dataBean = new DataBean($predicates, $datas);
		
		// Build the request
		$publish = new PublishRequest($handler, $dataBean);
		$publish->send();
		
	}
	
	
	/**
	 * Retreive the ExtendedProfile of a user in the database
	 * @param String $user_id
	 * @return ExtendedProfile
	 */
	public static /* ExtendedProfile */ function getExtendedProfile(IRequestHandler $handler, $user){
		
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
		
		$home = "";
		$diseaseLevel = "";
		$careGiver_raw = "";
		$doctor_raw = "";
		$callingList_raw = "";
		$callingList = array();
		$callingListAutoCall = array();
		$perimeter_home = "";
		$perimeter_nearby = "";
		$perimeter_far = "";
		$autocall_frequency = "";
		
		

		foreach ($result as $line){
			switch($line->key){
					
				case "home" :
					$home = json_decode($line->value);
					break;
				case "callingList" :
					$callingList_raw = json_decode($line->value);
					break;
				case "careGiver" :
					$careGiver_raw = json_decode($line->value);
					break;
				case "doctor" :
					$doctor_raw = json_decode($line->value);
					break;
				case "diseaseLevel" :
					$diseaseLevel = json_decode($line->value);
					break;
				case "callingListAutoCall" :
					$callingListAutoCall = json_decode($line->value);
					break;
				case "perimeter_home" :
					$perimeter_home = json_decode($line->value);
					break;
				case "perimeter_nearby" :
					$perimeter_nearby = json_decode($line->value);
					break;
				case "perimeter_far" :
					$perimeter_far = json_decode($line->value);
					break;
				case "autocall_frequency" :
					$autocall_frequency = json_decode($line->value);
					break;
					
			}
		}
		
		try{
			$careGiver = new Contact("caregiver", 
									$careGiver_raw->nickname, 
									$careGiver_raw->firstname, 
									$careGiver_raw->lastname, 
									$careGiver_raw->address, 
									$careGiver_raw->email, 
									$careGiver_raw->phone);
			
			$doctor = new Contact("doctor", 
									$doctor_raw->nickname, 
									$doctor_raw->firstname, 
									$doctor_raw->lastname,
									$doctor_raw->address,
									$doctor_raw->email,
									$doctor_raw->phone);
			
			foreach($callingList_raw as $line){
				$callingList[] = new Contact("buddy", 
											$line->nickname, 
											$line->firstname, 
											$line->lastname,
											$line->address, 
											$line->email, 
											$line->phone);
			}
			
			return new ExtendedProfile($user, $home, $diseaseLevel, $careGiver, $doctor, $callingList, $callingListAutoCall, $perimeter_home, $perimeter_nearby, $perimeter_far, $autocall_frequency );
		}
		catch(Exception $e){
			return false;
		}			
	}
	
	
	
	
}
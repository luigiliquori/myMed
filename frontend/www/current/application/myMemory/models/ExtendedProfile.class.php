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
	 * Name + address + email + phone
	 * Could be replaced by a myMed User if needed.
	 */
	public /*Array*/ $careGiver;
	
	/**
	 * Informations about the attending doctor.
	 * Currently an array with Name, address, email, and phone number.
	 * Could be replaced by a myMed User if needed.
	 */
	public /*Array*/ $doctor;
	
	/**
	 * Calling list of people to call/message in case of crisis.
	 * From 1 to 4, no duplicates, 1 means "called first" and 4 "called last".
	 * Array of arrays. Each row contains an array of Name + address + phone Number
	 * Could be replaced by myMed users but NOT ADVISED :
	 * This list is meant to be used in case of emergency and should not require long queries on
	 * the database of even a internet connection at all.
	 */
	public /*Array*/ $callingList;
	
	
	
	public function __construct(/*String*/ $user,/*String*/ $home ,/*enum*/ $diseaseLevel,/*Array*/ $careGiver, /*Array*/$doctor,/*Array*/ $callingList){
		
		// Check if user is defined
		if (empty($user))
			throw new Exception("User ID not defined.");
		else 
			$this->user = $user;
		
		//check the adress
		if (empty($home))
			throw new Exception("Adress must not be empty");
		else
			$this->home = $home;
		
		// check the diseaseLevel
		if ($diseaseLevel < 1 OR $diseaseLevel > 3)
			throw new Exception("diseaseLevel should be an enum between 1 and 3");
		else 
			$this->diseaseLevel = $diseaseLevel;
		
		// check the careGiver
		if (empty($careGiver) OR empty($careGiver["name"]) OR empty($careGiver["address"]) OR empty($careGiver["email"]) OR empty($careGiver["phone"]))
			throw new Exception("careGiver must not be empty");
		else
			$this->careGiver = $careGiver;
		
		
		// check the doctor
		if ( empty($doctor) OR empty($doctor["name"]) OR empty($doctor["address"]) OR empty($doctor["email"]) OR empty($doctor["phone"]))
			throw new Exception("Doctor must not be empty");
		else		
			$this->doctor = $doctor;
		
		
		// check the callingList
		if ( empty($callingList) )
			throw new Exception("Calling list must not be empty");
		else {
			
			foreach($callingList as $line)
			{
				if ( empty($line["name"]) OR empty($line["phone"]) )
					throw new Exception("Calling list element is empty");
			}
		}
		$this->callingList = $callingList;
		
		
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
		$careGiver = "";
		$doctor = "";
		$callingList = "";
		
		
		foreach ($result as $line){
			switch($line->key){
					
				case "home" :
					$home = json_decode($line->value, TRUE);
					break;
				case "callingList" :
					$callingList = json_decode($line->value, TRUE);
					break;
				case "careGiver" :
					$careGiver = json_decode($line->value, TRUE);
					break;
				case "doctor" :
					$doctor = json_decode($line->value, TRUE);
					break;
				case "diseaseLevel" :
					$diseaseLevel = json_decode($line->value, TRUE);
					break;
			}
		}
		return new ExtendedProfile($user, $home, $diseaseLevel, $careGiver, $doctor, $callingList);			
	}
	
	
	
	
}
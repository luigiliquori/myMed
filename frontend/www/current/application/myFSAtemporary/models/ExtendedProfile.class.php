<?php
//require_once '../../../lib/dasp/request/PublishRequest.class.php';
//require_once '../../../lib/dasp/beans/DataBean.php';
//require_once '../../../lib/dasp/beans/OntologyBean.php';

/**
 *	This class extends the default myMed user profile, common to every applications, with a profile specific
 * for this application. Store anything you need.
 * Choices have been made that this extended profile will be stored as a Publication in the database.
 * Although, you can change it the way you want in your own application, as long as you use the column families
 * available in cassandra. 
 * 
 * @see ExtendedProfileController
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
	 * Name of the company
	 */
	public /*String*/ $companyName;
	
	/**
	 * Informations about the attending doctor.
	 * Currently an array with Name, email, and phone number.
	 * Could be replaced by a myMed User if needed.
	 */
	public /*Array*/ $doctor;
	
	
	
	public function __construct(/*String*/ $user,/*String*/ $companyName,/*Array*/$doctor){
		
		// Check if user is defined
		if (empty($user))
			throw new Exception("User ID not defined.");
		else 
			$this->user = $user;
		
		// check the companyName
		if (empty($companyName))
			throw new Exception("companyName must not be empty");
		else
			$this->companyName = $companyName;
		
		
		// check the doctor
		if ( empty($doctor) OR empty($doctor["name"]) OR empty($doctor["email"]) OR empty($doctor["phone"]))
			throw new Exception("Doctor must not be empty");
		else		
			$this->doctor = $doctor;
		
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
		$datas[] = new OntologyBean("doctor", json_encode($this->doctor));
		$datas[] = new OntologyBean("companyName", json_encode($this->companyName));
		
		// Build the DataBean
		$dataBean = new DataBean($predicates, $datas);
		
		// Build the request
		$publish = new PublishRequest($handler, $dataBean);
		$publish->send();
		
		
		
		
	}
	
	
	/**
	 * Retreive the ExtendedProfile of a user in the database
	 * @param String $user_id
	 * @return void. The result is put in the success of the Handled provided
	 */
	public static /* List of ontologies */ function getExtendedProfile(IRequestHandler $handler, $user){
		
		$predicate = "roleExtendedProfile";
		
		$find = new FindRequest($handler, $predicate, $user);
		return $find->send();
			
	}
	
	
	
	
}
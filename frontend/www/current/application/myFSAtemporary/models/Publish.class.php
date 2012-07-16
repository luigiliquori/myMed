<?php

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
class Publish
{

	/*
	 * Attributes
	 */
	
	/**
	 * The ID of the myMed user to whom this ExtendedProfile is linked.
	 */
	public /*String*/ $user;
	public /*String*/ $key1;
	/**
	 * Name of the company
	 */
	public /*String*/ $key2;
	
	/**
	 * Informations about the attending doctor.
	 * Currently an array with Name, email, and phone number.
	 * Could be replaced by a myMed User if needed.
	 */
	public /*String*/ $key3;
	public /*String*/ $publication;
	
	
	
	public function __construct(/*String*/ $user,/*String*/ $key1,/*String*/ $key2,/*String*/ $key3,/*String*/ $publication){
		
		// Check if user is defined
		if (empty($user))
			throw new Exception("User ID not defined.");
		else 
			$this->user = $user;
		
		// check the companyName
		if (empty($key1))
			throw new Exception("key1 must not be empty");
		else
			$this->key1 = $key1;
		
		
		// check the doctor
		if ( empty($key2) OR empty($key3) OR empty($publication))
			throw new Exception("Publication must not be empty");
		else{	
			$this->key2 = $key2;
			$this->key3 = $key3;
			$this->publication = $publication;
		}
	}
	
	

	
	/**
	 * The extended Profile is stored as a publication in the database.
	 * The predicate is composed of the name of the application, the user_ID + "extendedProfile"
	 */
	public /*void*/ function storePublication(IRequestHandler $handler){
		
		/*
		 * Build the predicates Array
		 */
		$predicates[] = new OntologyBean("rolePublish;", "blabla", KEYWORD);
		
		/*
		 * Buid the datas Array
		 */
		$datas[] = new OntologyBean("key1", json_encode($this->key1));
		$datas[] = new OntologyBean("key2", json_encode($this->key2));
		$datas[] = new OntologyBean("key3", json_encode($this->key3));
		$datas[] = new OntologyBean("publication", json_encode($this->publication));
		
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
	public static /* List of ontologies */ function getPublication(IRequestHandler $handler, $user){
		
		$predicate = "rolePublish;*";
		
		$find = new FindRequest($handler, $predicate, $user);
		return $find->send();
			
	}
	
	
	
	
}
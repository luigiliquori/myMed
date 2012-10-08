<?php
//require_once '../../../lib/dasp/request/Request.class.php';


class Reputation
{

	/*
	 * Attributes
	 */

	/**
	 * The ID of the myMed user to whom this ExtendedProfile is linked.
	 */
	public /*String*/ $user;
	public /*String*/ $consumer;
	public /*String*/ $predicate;
	public /*String*/ $feedback;

	
	
	//public function __construct(/*String*/ $user, /*String*/ $producer, /*String*/ $consumer, /*String*/ $reputation){
	public function __construct(/*String*/ $user,/*String*/ $consumer, /*String*/ $predicate, /*String*/ $feedback){
		// Check if user is defined
		if (empty($user))
			throw new Exception("User ID not defined.");
		else{
			$this->user = $user;
			$this->consumer = $consumer;
			$this->predicate = $predicate;
			$this->feedback = $feedback;
		}
			
	}	

	public /*void*/ function storeReputation(){
		
		$request = new Request("InteractionRequestHandler", 2);
		$request->addArgument("application",  $this->predicate);
		$request->addArgument("producer",  $this->user->id);
		$request->addArgument("consumer", $this->consumer);
		$request->addArgument("start",  time());
		$request->addArgument("end",  time());
		$request->addArgument("predicate",  $this->predicate);
		$request->addArgument("feedback",  $this->feedback);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
	}
	
	
	/**
	 * Retreive the ExtendedProfile of a user in the database
	 * @param String $user_id
	 * @return void. The result is put in the success of the Handled provided
	 */
	public static /* List of ontologies */ function getReputation(/*String*/ $user, /*String*/ $consumer){
		
		// Get the reputation of the user in each the application
		$request = new Request("ReputationRequestHandler", READ);
		$request->addArgument("application",  'myFSA');
		$request->addArgument("producer",  $user->id);					// Reputation of data
		$request->addArgument("consumer",  '');	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		$debugtxt  =  "<pre>getReeeeeeeeeeeeeeeeputation";
		$debugtxt  .= var_export($responseObject, TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);

		
	}
	
	
	
	
}
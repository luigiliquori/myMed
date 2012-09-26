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
		// Get the reputation of the user in each the application
		
		$request = new Request("InteractionRequestHandler", 2);
		$request->addArgument("application",  "myFSA");
		$request->addArgument("producer",  "Pub1");					// Reputation of data
		$request->addArgument("consumer", $_SESSION['user']->id);
		$request->addArgument("start",  time());
		$request->addArgument("end",  time());
		$request->addArgument("predicate",  "PUB_REP");
		$request->addArgument("feedback",  0.5);
		/*
		$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
		$debugtxt  .= var_export(UPDATE, TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);
		* */
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
	}
	
	
	/**
	 * Retreive the ExtendedProfile of a user in the database
	 * @param String $user_id
	 * @return void. The result is put in the success of the Handled provided
	 */
	public static /* List of ontologies */ function getReputation(IRequestHandler $handler, $user){

		
	}
	
	
	
	
}
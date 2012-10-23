<?php
//require_once '../../../lib/dasp/request/Request.class.php';


class Ranking
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
	public function __construct(/*String*/ $user,/*String*/ $consumer, /*String*/ $feedback){
		// Check if user is defined
		if (empty($user)){
			throw new Exception("User ID not defined.");
		
		$debugtxt  =  "<pre>EEEEEEEEEEErrrrror";
		$debugtxt .= "</pre>";
		debug($debugtxt);}
		else{
			$this->user = $user;
			$this->consumer = $consumer;
			$this->feedback = $feedback;
		}
			
	}	

	public /*void*/ function storeReputation(){
		
		$request = new Request("InteractionRequestHandler", 2);
		$request->addArgument("application",  'myFSA');
		$request->addArgument("producer",  $this->user);
		$request->addArgument("consumer", $this->consumer);
		$request->addArgument("start",  time());
		$request->addArgument("end",  time());
		$request->addArgument("predicate",  "ReputationOfPub");
		$request->addArgument("feedback",  $this->feedback);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
	}
	
	
	/**
	 * Retreive the ExtendedProfile of a user in the database
	 * @param String $user_id
	 * @return void. The result is put in the success of the Handled provided
	 */
	public static /* List of ontologies */ function getMark(){
		
		// Get the reputation of the user in each the application
		$request = new Request("ReputationRequestHandler", READ);
		$request->addArgument("application",  'myFSA');
		$request->addArgument("producer",  $_SESSION['user']->id);					// Reputation of data
		$request->addArgument("consumer",  '');	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		
		$tmp_rank = $responseObject->data->reputation;
		$_SESSION['rank'] = $tmp_rank*5;
		$debugtxt  =  "<pre>getReeeeeeeeeeeeeeeeputation";
		$debugtxt  .= var_export($responseObject, TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);

		
	}
	
	
	
	
}
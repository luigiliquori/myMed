<? 
class ReputationController extends ExtendedProfileRequired {
	
	/**
	 * contents seen by the user
	 *  format (contentId : authorId)
	 */
	public $usersSeen;
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		//fetch user's History
		$find = new DetailRequestv2($this, "users", $_SESSION['user']->id);
			
		try{
			$result = $find->send();
		} catch(Exception $e){}
		
		if (isset($result)){
			$this->usersSeen = $result;
		}
		
		// for each user's history entry fetch reputation of content and author
		foreach ($this->usersSeen as $k => $v){
			
		}
			
		if (isset($_POST["rep"])){
			$this->updateReputation();
		}

		$this->users = $result;
		
		$this->redirectTo("Main", null, "#rep");
	
		
	}
	
	public /*void*/ function getReputation( $id ){
	
		$rep = new Reputation($this, $id);
		$res=$rep->send();
		if (isset($res, $res->reputation))
			return $res->reputation;
		else
			return null;
		
	}
	
	public /*void*/ function updateReputation(){


	}
	
}
?>
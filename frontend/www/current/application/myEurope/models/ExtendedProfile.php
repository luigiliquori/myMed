<?php


class ExtendedProfile {

	public $id;
	
	public $users; //members
	public $partnerships; //partnerships owned or joined
	public $reputation; //partnerships owned or joined
	
	public $handler;
	
	
	public function __construct(IRequestHandler $handler, $id){
		$this->id = $id;
		$this->handler = $handler;
	}
	
	
	public function read(){
		
		$find = new RequestJson($this->handler, array("application"=>APPLICATION_NAME.":profiles", "id"=>$this->id));
		
		try{ $res = $find->send();
		} catch(Exception $e){
		}

		$this->users = array();
		$this->partnerships = array();
		foreach ($res->details as $k => $v){
			if (strpos($k, "user_") === 0){
				$this->users[] = $v;
			} else if(strpos($k, "part_") === 0){
				$this->partnerships[] = $v;
			}
		}
		sort($this->users);

		$rep =  new Reputationv2($handler, null, $this->id);
		$this->reputation = $rep->send();
		
	}
	
	public function readFromUser($user){
		
		$find = new RequestJson($this->handler, array("application"=>APPLICATION_NAME.":users", "id"=>$user));
		try{ $res = $find->send();
		} catch(Exception $e){
		}
		return $this->read($this->handler, $res->details->profile);
	}
	
}




?>
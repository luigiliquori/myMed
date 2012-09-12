<?php

class Document {
	
	public $id;
	public $title;
	public $expirationDate;
	public $user;
	
	public $reputation;
	
	public /* array of ExtendedProfile*/ $partnersProfiles;  // first is the owner, others who joined, might sort them by rep, at the end unshift partner at the top of list highlighted
	
	public $index;
	
	//construct methods
	
	//construct index from the request url values, post or get
	public function __construct($request) {
		$this->index = array();
		
		//the order matters because I was too lazy to sort it backend-side
		$this->index[] = new DataBeanv2("t", ENUM, $request['t']); //themes
		$this->index[] = new DataBeanv2("p", ENUM, $request['p']); //places
		$this->index[] = new DataBeanv2("r", ENUM, $request['r']); //roles of profiles
		$this->index[] = new DataBeanv2("c", ENUM, $request['c']); // call
		$this->index[] = new DataBeanv2("k", ENUM, $request['k']); // keywords
		
		if isset($request['date']){
			$this->expirationDate = $request['date'];
		}
		if isset($request['title']){
			$this->title = $request['title'];
		}
		if isset($request['text']){
			$this->text = $request['text'];
		}
	}
	
	public function init($title, $user){
		$this->title= $title;
		$this->user= $user;
	}
	
	public function search(IRequestHandler $handler) {
		$find = new RequestJson($handler,
				array("application"=>APPLICATION_NAME.":part", "predicates"=>$this->index));
		
		try{
			$res = $find->send();
		}catch (NoResultException $e) {

		}catch(Exception $e){
		}
		$handler->success = "";
		return (array) $res->results;
	}
	
	
	
	public function read(IRequestHandler $handler) {
		
		$rep =  new Reputationv2($handler, $this->user, $this->id);
		$this->reputation = $rep->send();
		
		$req = new RequestJson( $this, array("application"=>APPLICATION_NAME.":part","id"=>$this->id));
		
		try{
			$res = $req->send();
		}catch (NoResultException $e) {
			$this->details=new StdClass();
			$this->details->text = "<h2 style='text-align:center;'>"._("Contenu effacé par l'auteur")."</h2>";
		}catch(Exception $e){
		}
		
		$this->partnersProfiles = array();
		
		foreach ($this->details as $k => $v){
			if (strpos($k, "user_") === 0){
				$p = new ExtendedProfile($v)
				$this->partnersProfiles[$p->id]= $p->readFromUser();
			}
		}
		//sort them by rep
		
		//author
		$p = new ExtendedProfile($this->user);
		$authorProfile = $p->readFromUser();
		
		$this->partnersProfiles = array_merge(array($this->user=>$authorProfile, $this->partnersProfiles));
		
	}
	
	
	public function create(IRequestHandler $handler, $user) {
		
		$t = time();
		
		$data = array(
				"title" => $this->title,
				"time"=>$t,
				"user" => $user,
				"text" => !empty($this->text)?$this->text:"contenu vide"
		);
		
		$metadata = array(
				/* @todo more stuff here */
				"title" => $this->title,
				"time"=>$t,
				"user" => $user,
		);
		
		if (isset($this->expirationDate)){
			$data['expirationDate'] = $this->expirationDate;
			$metadata['expirationDate'] = $this->expirationDate;
		}
		
		$id = hash("md5", $t.$user);
		
		$publish = new RequestJson($handler,
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$id, "user"=>$user, "data"=>$data, "predicates"=>$index, "metadata"=>$metadata),
				CREATE);
		
		$publish->send();
	}
	
	public function update(IRequestHandler $handler) {
	
	
	}
	
	public function delete(IRequestHandler $handler) {
		//after a read, so you can update all people's profiles
	
	}
	
	
}









?>
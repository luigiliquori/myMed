<?php

class Document {
	
	public $id;
	public $namespace;
	public $user;
	
	public $handler;
	
	public $reputation;
	
	
	public function __construct(
			IRequestHandler $handler = null,
			$namespace = null,
			$id = null,
			$index = null,
			$data = null,
			$metadata = null) {
		
		$this->handler = $handler;
		$this->namespace = $namespace;
		$this->id = $id;
		$this->index = $index;
		$this->data = $data;
		$this->metadata = $metadata;
	}
	
	public function search() {
		$find = new RequestJson($this->handler,
				array("application"=>APPLICATION_NAME.":".$this->namespace, "predicates"=>$this->index));
		
		try{
			$res = $find->send();
		}catch (NoResultException $e) {

		}catch(Exception $e){
		}
		$this->handler->success = "";
		return (array) $res->results;
	}
	
	
	
	public function read() {
		
		$rep =  new Reputationv2($this->handler, $this->user, $this->id);
		$this->reputation = $rep->send();
		
		$req = new RequestJson( $this->handler, array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id));
		
		try{
			$res = $req->send();
		}catch (NoResultException $e) {
			$res->details=new StdClass();
			$res->details->text = "<h2 style='text-align:center;'>"._("Contenu effacé par l'auteur")."</h2>";
		}catch(Exception $e){
		}
		
		$this->handler->success = "";
		return (array) $res->details;
		
	}
	
	
	public function create() {
		
		$t = time();		
		$this->id = hash("md5", $t.$this->user);
		
		$publish = new RequestJson($this->handler,
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$this->id, "user"=>$this->user, "data"=>$this->data, "predicates"=>$this->index, "metadata"=>$this->metadata),
				CREATE);
		
		$publish->send();
	}
	
	public function update() {
		
		$publish = new RequestJson($handler,
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$this->id, "user"=>$this->user, "data"=>$this->data, "predicates"=>$this->index, "metadata"=>$this->metadata),
				UPDATE);
		
		$publish->send();
	
	}
	
	public function delete() {
		$publish = new RequestJson($this->handler,
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id),
				DELETE);

		
		$publish->send();
	
	}
	
	
}









?>
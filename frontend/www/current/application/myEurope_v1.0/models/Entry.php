<?php

/**
 * 
 * generic class of a data saved in our DB
 *
 */
class Entry {
	
	/*
	 * have to put them private later
	 */
	public $id;
	public $namespace;
	public $user;
	
	
	public function __construct(
			$namespace = null,
			$id = null,
			$data = null,
			$metadata = null,
			$index = array()) {

		$this->namespace = $namespace;
		$this->id = $id;
		$this->index = $index;
		$this->data = $data;
		$this->metadata = $metadata;
	}
	
	public function search() {

		$find = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace, "predicates"=>$this->index));
		
		$res = $find->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		} else {// Success
			return (array) $res->dataObject->results;
		}
	}
	
	public function read() {
		
		$req = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id));
		
		$res = $req->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		} else {// Success
			return (array) $res->dataObject->details;
		}
	}
	
	
	public function create() {
		
		$t = time();		
		$this->id = hash("md5", $t.$this->user);
		
		$publish = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$this->id, "user"=>$this->user, "data"=>$this->data, "predicates"=>$this->index, "metadata"=>$this->metadata),
				CREATE);
		
		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function update() {
		
		$publish = new RequestPubSub(				
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$this->id, "user"=>$this->user, "data"=>$this->data, "predicates"=>$this->index, "metadata"=>$this->metadata),
				UPDATE);
		
		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function delete() {
		$publish = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id),
				DELETE);

		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function setUser($user){
		$this->user = $user;
	}
	
	
}









?>